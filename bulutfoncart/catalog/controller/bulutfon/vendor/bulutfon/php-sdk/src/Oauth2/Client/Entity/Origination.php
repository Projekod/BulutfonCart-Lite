<?php

namespace Bulutfon\OAuth2\Client\Entity;


class Origination extends BaseEntity{
    protected $destination;
    protected $start_time;
    protected $answer_time;
    protected $hangup_time;
    protected $result;

    public function getArrayCopy()
    {
        return [
            'destination' => $this->destination,
            'start_time' => $this->start_time,
            'answer_time' => $this->answer_time,
            'hangup_time' => $this->hangup_time,
            'result' => $this->result,
        ];
    }
}