<?php
namespace Bulutfon\OAuth2\Client\Provider;

use Bulutfon\OAuth2\Client\Entity\CallFlow;
use Bulutfon\OAuth2\Client\Entity\CdrObject;
use Bulutfon\OAuth2\Client\Entity\Did;
use Bulutfon\OAuth2\Client\Entity\Extension;
use Bulutfon\OAuth2\Client\Entity\Group;
use Bulutfon\OAuth2\Client\Entity\Origination;
use Bulutfon\OAuth2\Client\Entity\User;
use Bulutfon\OAuth2\Client\Entity\Cdr;
use Bulutfon\OAuth2\Client\Entity\WorkingHour;
use Guzzle\Http\Exception\BadResponseException;
use League\OAuth2\Client\Exception\IDPException;
use League\OAuth2\Client\Grant\GrantInterface;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Provider\AbstractProvider;

class Bulutfon extends AbstractProvider
{
    public $scopes = ['cdr'];
    public $uidKey = 'user_id';
    public $responseType = 'json';

    public $baseUrl = "https://api.bulutfon.com";
    public $authUrl = "https://www.bulutfon.com/oauth/authorize";
    public $tokenUrl = "https://www.bulutfon.com/oauth/token";

    public function urlAuthorize()
    {
        return $this->authUrl;
    }

    public function urlAccessToken()
    {
        return $this->tokenUrl;
    }

    public function getAccessToken($grant = 'authorization_code', $params = [])
    {
        if (is_string($grant)) {
            // PascalCase the grant. E.g: 'authorization_code' becomes 'AuthorizationCode'
            $className = str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $grant)));
            $grant = 'League\\OAuth2\\Client\\Grant\\' . $className;
            if (!class_exists($grant)) {
                throw new \InvalidArgumentException('Unknown grant "' . $grant . '"');
            }
            $grant = new $grant();
        } elseif (!$grant instanceof GrantInterface) {
            $message = get_class($grant) . ' is not an instance of League\OAuth2\Client\Grant\GrantInterface';
            throw new \InvalidArgumentException($message);
        }
        $defaultParams = [
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'redirect_uri' => $this->redirectUri,
            'grant_type' => $grant,
        ];
        $requestParams = $grant->prepRequestParams($defaultParams, $params);
        try {
            switch (strtoupper($this->method)) {
                case 'GET':
                    // @codeCoverageIgnoreStart
                    // No providers included with this library use get but 3rd parties may
                    $client = $this->getHttpClient();
                    $client->setBaseUrl($this->urlAccessToken() . '?' . $this->httpBuildQuery($requestParams, '', '&'));
                    $request = $client->get(null, null, $requestParams)->send();
                    $response = $request->getBody();
                    break;
                // @codeCoverageIgnoreEnd
                case 'POST':
                    $client = $this->getHttpClient();
                    $client->setSslVerification(false);
                    $client->setBaseUrl($this->urlAccessToken());
                    $request = $client->post(null, null, $requestParams)->send();
                    $response = $request->getBody();
                    break;
                // @codeCoverageIgnoreStart
                default:
                    throw new \InvalidArgumentException('Neither GET nor POST is specified for request');
                // @codeCoverageIgnoreEnd
            }
        } catch (BadResponseException $e) {
            // @codeCoverageIgnoreStart
            $response = $e->getResponse()->getBody();
            // @codeCoverageIgnoreEnd
        }
        switch ($this->responseType) {
            case 'json':
                $result = json_decode($response, true);
                if (JSON_ERROR_NONE !== json_last_error()) {
                    $result = [];
                }
                break;
            case 'string':
                parse_str($response, $result);
                break;
        }
        if (isset($result['error']) && !empty($result['error'])) {
            // @codeCoverageIgnoreStart
            echo "Hata olustu";
            die();
            throw new IDPException($result);
            // @codeCoverageIgnoreEnd
        }
        $result = $this->prepareAccessTokenResult($result);
        $accessToken = $grant->handleResponse($result);
        // Add email from response
        if (!empty($result['email'])) {
            $accessToken->email = $result['email'];
        }
        return $accessToken;
    }

    public function fetchProviderData($url, array $headers = [])
    {
        try {
            $client = $this->getHttpClient();
            $client->setBaseUrl($url);

            if ($headers) {
                $client->setDefaultOption('headers', $headers);
            }

            $request = $client->get()->send();
            $response = $request->getBody();
        } catch (BadResponseException $e) {
            // @codeCoverageIgnoreStart
            $raw_response = explode("\n", $e->getResponse());
            $response = $e->getResponse()->getBody();
            $response = json_decode($response);

            if($response->error == 'Token expired') {
                $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                header("Location: ". $this->redirectUri ."?refresh_token=true&back=".$actual_link);
            }
            throw new IDPException(end($raw_response));
            // @codeCoverageIgnoreEnd
        }

        return $response;
    }
    /* USER METHODS */

    public function urlUserDetails(AccessToken $token)
    {
        return $this->baseUrl."/me?access_token=".$token;
    }
    public function userDetails($response, AccessToken $token)
    {
        $user = new User();
        $email = (isset($token->email)) ? $token->email : null;
        $location = (isset($response->country)) ? $response->country : null;
        $description = (isset($response->status)) ? $response->status : null;
        $user->exchangeArray([
            'user' => $response->user,
            'pbx' => $response->pbx,
            'credit' => $response->credit,
        ]);
        return $user;
    }
    public function userUid($response, AccessToken $token)
    {
        return $response->user->email;
    }
    public function userEmail($response, AccessToken $token)
    {
        return (isset($token->email)) ? $token->email : null;
    }
    public function userScreenName($response, AccessToken $token)
    {
        return $response->name;
    }

    /* DID METHODS */

    protected function urlDid(AccessToken $token, $id = null)
    {
        $url = "";
        if($id) {
            $url = $this->baseUrl."/dids/". $id ."?access_token=".$token;
        } else {
            $url = $this->baseUrl."/dids?access_token=".$token;
        }
        return $url;
    }

    protected function fetchDids(AccessToken $token, $id = null)
    {
        $url = $this->urlDid($token, $id);

        $headers = $this->getHeaders($token);

        return $this->fetchProviderData($url, $headers);
    }

    protected function workingHours($working_hours)
    {
        $working_hour = new WorkingHour();
        $working_hour->exchangeArray([
            'monday' => $working_hours->monday,
            'tuesday' => $working_hours->tuesday,
            'wednesday' => $working_hours->wednesday,
            'thursday' => $working_hours->thursday,
            'friday' => $working_hours->friday,
            'saturday' => $working_hours->saturday,
            'sunday' => $working_hours->sunday,
        ]);

        return $working_hour;
    }

    protected function did($response, $id = null)
    {
        $did = new Did();
        $did->exchangeArray([
            'id' => $response->id,
            'number' => $response->number,
            'state' => $response->state,
            'destination_type' => $response->destination_type,
            'destination_id' => $response->destination_id,
            'destination_number' => $response->destination_number,
            'working_hour' => $response->working_hour,
            'working_hours' => ($response->working_hour && $id) ? $this->workingHours($response->working_hours) : null,
        ]);

        return $did;
    }
    protected function dids($response, AccessToken $token, $id = null) {
        if($id) {
            return $this->did($response->did, $id);
        } else {
            $dids = array();
            $response_dids = $response->dids;
            foreach($response_dids as $response_did) {
                $did = $this->did($response_did);
                array_push($dids, $did);
            }

            return $dids;
        }
    }

    public function getDids(AccessToken $token) {
        $response = $this->fetchDids($token);
        return $this->dids(json_decode($response), $token);
    }

    public function getDid(AccessToken $token, $id) {
        $response = $this->fetchDids($token, $id);
        return $this->dids(json_decode($response), $token, $id);
    }

    /* EXTENSION METHODS */

    protected function urlExtension(AccessToken $token, $id = null)
    {
        $url = "";
        if($id) {
            $url = $this->baseUrl."/extensions/". $id ."?access_token=".$token;
        } else {
            $url = $this->baseUrl."/extensions?access_token=".$token;
        }
        return $url;
    }

    protected function fetchExtensions(AccessToken $token, $id = null)
    {
        $url = $this->urlExtension($token, $id);

        $headers = $this->getHeaders($token);

        return $this->fetchProviderData($url, $headers);
    }

    protected function extension($response, $id = null)
    {
        $extension = new Extension();
        $extension->exchangeArray([
            'id' => $response->id,
            'number' => $response->number,
            'registered' => property_exists($response, "registered") ? $response->registered : null,
            'caller_name' => $response->caller_name,
            'email' => $response->email,
            'did' => $id ? $response->did : null,
            'acl' => $id ? $response->acl : null,
        ]);

        return $extension;
    }
    protected function extensions($response, AccessToken $token, $id = null) {
        if($id) {
            return $this->extension($response->extension, $id);
        } else {
            $extensions = array();
            $response_extensions = $response->extensions;
            foreach($response_extensions as $response_extension) {
                $extension = $this->extension($response_extension);

                array_push($extensions, $extension);

            }

            return $extensions;
        }
    }

    public function getExtensions(AccessToken $token) {
        $response = $this->fetchExtensions($token);
        return $this->extensions(json_decode($response), $token);
    }

    public function getExtension(AccessToken $token, $id) {
        $response = $this->fetchExtensions($token, $id);
        return $this->extensions(json_decode($response), $token, $id);
    }

    /* GROUP METHODS */

    protected function urlGroup(AccessToken $token, $id = null)
    {
        $url = "";
        if($id) {
            $url = $this->baseUrl."/groups/". $id ."?access_token=".$token;
        } else {
            $url = $this->baseUrl."/groups?access_token=".$token;
        }
        return $url;
    }

    protected function fetchGroups(AccessToken $token, $id = null)
    {
        $url = $this->urlGroup($token, $id);

        $headers = $this->getHeaders($token);

        return $this->fetchProviderData($url, $headers);
    }

    protected function group($response, AccessToken $token, $id = null)
    {
        $group = new Group();
        $group->exchangeArray([
            'id' => $response->id,
            'number' => $response->number,
            'name' => $response->name,
            'timeout' => $response->timeout,
            'extensions' => $id ? $this->extensions($response, $token) : null
        ]);

        return $group;
    }

    protected function groups($response, AccessToken $token, $id = null) {
        if($id) {
            return $this->group($response->group, $token, $id);
        } else {
            $groups = array();
            $response_groups = $response->groups;
            foreach($response_groups as $response_group) {
                $group = $this->group($response_group, $token);

                array_push($groups, $group);

            }

            return $groups;
        }
    }

    public function getGroups(AccessToken $token) {
        $response = $this->fetchGroups($token);
        return $this->groups(json_decode($response), $token);
    }

    public function getGroup(AccessToken $token, $id) {
        $response = $this->fetchGroups($token, $id);
        return $this->groups(json_decode($response), $token, $id);
    }

    /* CDR METHODS */

    protected function urlCdr(AccessToken $token, $uuid = null, $page, $params = [])
    {
        $url = "";
        $params['access_token'] = $token->accessToken;
        $par = http_build_query($params);
        if($uuid) {
            $url = $this->baseUrl."/cdrs/". $uuid ."?access_token=".$token;
        } else {
            $url = $this->baseUrl."/cdrs?page=". $page ."&". $par;
        }
        return $url;
    }

    protected function fetchCdrs(AccessToken $token, $uuid = null, $page, $params = [])
    {
        $url = $this->urlCdr($token, $uuid, $page, $params);
        $headers = $this->getHeaders($token);

        return $this->fetchProviderData($url, $headers);
    }

    protected function origination($response) {
        $originations = array();
        foreach($response as $o) {
            $origination = new Origination();
            $origination->exchangeArray([
                'destination' => $o->destination,
                'start_time' => $o->start_time,
                'answer_time' => $o->answer_time,
                'hangup_time' => $o->hangup_time,
                'result' => $o->result,
            ]);
            array_push($originations, $origination);
        }
        return $originations;
    }

    protected function callFlow($response)
    {
        $call_flows = array();
        foreach($response as $cf) {
            $call_flow = new CallFlow();
            $call_flow->exchangeArray([
                'callee' => $cf->callee,
                'start_time' => $cf->start_time,
                'answer_time' => $cf->answer_time,
                'hangup_time' => $cf->hangup_time,
                'redirection' => $cf->redirection,
                'redirection_target' => property_exists($cf, 'redirection_target') ? $cf->redirection_target : null,
                'origination' => property_exists($cf, 'origination') ? $this->origination($cf->origination) : null,
            ]);
            array_push($call_flows, $call_flow);
        }

        return $call_flows;
    }

    protected function cdr($response, $id = null) {
        $cdr = new Cdr();
        $cdr->exchangeArray([
            'uuid' => $response->uuid,
            'bf_calltype' => $response->bf_calltype,
            'direction' => $response->direction,
            'caller' => $response->caller,
            'callee' => $response->callee,
            'extension' => property_exists($response, 'extension') ? $response->extension : null,
            'call_price' => property_exists($response, 'call_price') ? $response->call_price : null,
            'call_time' => $response->call_time,
            'answer_time' => $response->answer_time,
            'hangup_time' => $response->hangup_time,
            'call_record' => $response->call_record,
            'hangup_cause' => $response->hangup_cause,
            'hangup_state' => $response->hangup_state,
            'call_flow' => property_exists($response, "call_flow") ? $this->callFlow($response->call_flow) : null,
        ]);
        return $cdr;
    }

    protected function cdrs($response, AccessToken $token, $uuid = null)
    {
        if($uuid) {
            return $this->cdr($response->cdr, $uuid);
        } else {
            $cdrs = array();
            $response_cdrs = $response->cdrs;
            foreach($response_cdrs as $response_cdr) {
                $cdr = $this->cdr($response_cdr, $uuid);

                array_push($cdrs, $cdr);

            }

            $pagination = $response->pagination;
            $cdrObj = new CdrObject();
            $cdrObj->exchangeArray([
                'cdrs' => $cdrs,
                'previous_page' => property_exists($pagination, "previous_page") ? $pagination->previous_page : null,
                'next_page' => property_exists($pagination, "next_page") ? $pagination->next_page : null,
                'page' => $pagination->page,
            ]);

            return $cdrObj;
        }
    }

    public function getCdrs(AccessToken $token, $params = [], $page = 1) {
        $response = $this->fetchCdrs($token, null, $page, $params);
        return $this->cdrs(json_decode($response), $token);
    }

    public function getCdr(AccessToken $token, $uuid) {
        $response = $this->fetchCdrs($token, $uuid, 1, []);
        return $this->cdrs(json_decode($response), $token, $uuid);
    }

    public function getUser(AccessToken $token) {
        return $this->getUserDetails($token);
    }
}
