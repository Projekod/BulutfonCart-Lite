<?php
/**
 * Created by PhpStorm.
 * User: htkaya
 * Date: 23/04/15
 * Time: 13:07
 */

namespace Bulutfon\OAuth2\Client\Entity;

class Extension extends BaseEntity {
    protected $id;
    protected $number;
    protected $registered;
    protected $caller_name;
    protected $email;
    protected $did;
    protected $acl;

    public function getArrayCopy()
    {
        return [
            'id' => $this->id,
            'number' => $this->number,
            'registered' => $this->registered,
            'caller_name' => $this->caller_name,
            'email' => $this->email,
            'did' => $this->did,
            'acl' => $this->acl,
        ];
    }
}