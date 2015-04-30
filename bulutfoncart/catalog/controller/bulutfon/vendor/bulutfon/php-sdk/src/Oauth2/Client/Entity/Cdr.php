<?php

namespace Bulutfon\OAuth2\Client\Entity;

class Cdr extends BaseEntity {
    protected $uuid;
    protected $bf_calltype;
    protected $direction;
    protected $caller;
    protected $callee;
    protected $extension;
    protected $call_time;
    protected $answer_time;
    protected $hangup_time;
    protected $call_price;
    protected $call_record;
    protected $hangup_cause;
    protected $hangup_state;
    protected $call_flow;


    public function getArrayCopy()
    {
        return [
            'uuid' => $this->uuid,
            'bf_calltype' => $this->bf_calltype,
            'direction' => $this->direction,
            'caller' => $this->caller,
            'callee' => $this->callee,
            'extension' => $this->extension,
            'call_time' => $this->call_time,
            'answer_time' => $this->answer_time,
            'hangup_time' => $this->hangup_time,
            'call_price' => $this->call_price,
            'call_record' => $this->call_record,
            'hangup_cause' => $this->hangup_cause,
            'hangup_state' => $this->hangup_state,
            'call_flow' => $this->call_flow,
        ];
    }
}