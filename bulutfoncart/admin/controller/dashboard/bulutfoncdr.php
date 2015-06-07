<?php
class ControllerDashboardBulutfoncdr extends Controller {
    public function index() {
        require DIR_CATALOG.'/controller/bulutfon/vendor/autoload.php';
        $key = $this->config->get('config_bulutfon_key');
        $secret = $this->config->get('config_bulutfon_secret');
        $refreshToken = $this->config->get('config_bulutfon_tokenRefresh');
        $data = array();

        if($key and $secret and $refreshToken){
            $redirectUrl = "https://".$this->config->get('config_ftp_hostname')."/bulutfonauth";
            $provider = new \Bulutfon\OAuth2\Client\Provider\Bulutfon([
                'clientId'          => $key,
                'clientSecret'      => $secret,
                'redirectUri'       => $redirectUrl,
                "SslVerification"   => false
            ]);
            $token = $provider->getAccessToken('refresh_token', [
                'refresh_token' => $refreshToken
            ]);


            $this->refreshData($token);
            $filters = []; // ['caller' => 'xxx', 'callee'=> 'yyy', 'time_limit' => 'day'] etc.


            if(isset($this->request->get["customer_id"]) and $customer_id = $this->request->get["customer_id"]){
                $this->load->model('sale/customer');
                if($customer = ($this->model_sale_customer->getCustomer($customer_id))){
                    $phone = $this->formatPhoneNumber($customer["telephone"]);
                    $filters["caller"] = $phone;
                }
            }

            $cdrObj = $provider->getCdrs($token, $filters, "1");
            $cdrsNew = array();
            if($cdrs = $cdrObj->cdrs){
                $tmp = array();
                foreach($cdrs as $cdr){
                    $tmp["bf_calltype"] = $cdr->bf_calltype;
                    $tmp["direction"] = $cdr->direction;
                    $tmp["caller"] = $cdr->caller;
                    $tmp["customer_id"] = $this->findCustomerFromPhone($tmp["caller"]);
                    $tmp["customer_link"] = '';
                    if($tmp["customer_id"]){
                        $tmp["customer_link"] = str_replace("&amp;","&",$this->url->link('sale/customer/edit', 'token=' . $this->session->data['token'] . '&customer_id=' . $tmp['customer_id'] , 'SSL'));
                    }
                    $tmp["callee"] = $cdr->callee;
                    $tmp["call_time"] = $cdr->call_time;
                    $tmp["answer_time"] = $cdr->answer_time;
                    $cdrsNew[] = (object) $tmp;
                }
                $data["cdrs"] =  $cdrsNew;
            }

        }
        return $this->load->view('dashboard/bulutfoncdr.tpl',$data);
    }
    public function formatPhoneNumber($number){
        if(strlen($number)==11){
            return "9".$number;
        }
        return $number;
    }
    public function refreshData($token){
        $this->db->query("DELETE FROM " . DB_PREFIX . "setting Where store_id=0 and `key`='config_bulutfon_token' ");
        $this->db->query("DELETE FROM " . DB_PREFIX . "setting Where store_id=0 and `key`='config_bulutfon_tokenRefresh' ");
        $this->db->query("DELETE FROM " . DB_PREFIX . "setting Where store_id=0 and `key`='config_bulutfon_expires' ");

        $sorgu = "insert into ".DB_PREFIX."setting(store_id,code,`key`,`value`,serialized) values(0,'config','config_bulutfon_token','".$token->accessToken."',0)";
        $this->db->query($sorgu);
        $sorgu = "insert into ".DB_PREFIX."setting(store_id,code,`key`,`value`,serialized) values(0,'config','config_bulutfon_tokenRefresh','".$token->refreshToken."',0)";
        $this->db->query($sorgu);
        $sorgu = "insert into ".DB_PREFIX."setting(store_id,code,`key`,`value`,serialized) values(0,'config','config_bulutfon_expires','".$token->expires."',0)";
        $this->db->query($sorgu);
    }
    public function findCustomerFromPhone($phoneNumber){

        $checkList = array();
        $checkList[] = $phoneNumber;
        $checkList[] = substr($phoneNumber,2,strlen($phoneNumber));

        foreach($checkList as $phone){
            if(strlen($phone)>=10){
                $sorgu = "select customer_id from ".DB_PREFIX."customer Where telephone like '%".$phone."%' limit 1";
                $sonuc = $this->db->query($sorgu);
                if($sonuc->num_rows>0){
                    return $sonuc->row["customer_id"];
                }
            }
        }
        return 0;
    }
}