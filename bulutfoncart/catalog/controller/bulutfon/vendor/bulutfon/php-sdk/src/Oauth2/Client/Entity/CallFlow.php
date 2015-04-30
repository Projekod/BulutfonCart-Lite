<?php

namespace Bulutfon\OAuth2\Client\Entity;


class CallFlow extends BaseEntity {
    protected $callee;
    protected $start_time;
    protected $answer_time;
    protected $hangup_time;
    protected $redirection;
    protected $redirection_target;
    protected $origination;

    public function getArrayCopy()
    {
        return [
            'callee' => $this->callee,
            'start_time' => $this->start_time,
            'answer_time' => $this->answer_time,
            'hangup_time' => $this->hangup_time,
            'redirection' => $this->redirection,
            'redirection_target' => $this->redirection_target,
            'origination' => $this->origination,
        ];
    }
}