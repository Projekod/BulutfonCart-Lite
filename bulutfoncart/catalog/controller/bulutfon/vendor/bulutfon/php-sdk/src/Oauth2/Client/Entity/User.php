<?php

namespace Bulutfon\OAuth2\Client\Entity;


class User extends BaseEntity
{
    protected $user;
    protected $pbx;
    protected $credit;

    public function getArrayCopy()
    {
        return [
            'user' => $this->user,
            'pbx' => $this->pbx,
            'credit' => $this->credit,
        ];
    }
}
