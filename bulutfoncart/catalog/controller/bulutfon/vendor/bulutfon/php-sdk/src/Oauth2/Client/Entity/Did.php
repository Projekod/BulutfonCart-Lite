<?php
/**
 * Created by PhpStorm.
 * User: htkaya
 * Date: 23/04/15
 * Time: 13:08
 */

namespace Bulutfon\OAuth2\Client\Entity;

class Did extends BaseEntity {
    protected $id;
    protected $number;
    protected $state;
    protected $destination_type;
    protected $destination_id;
    protected $destination_number;
    protected $destination;
    protected $working_hour;
    protected $working_hours;

    public function getArrayCopy()
    {
        return [
            'id' => $this->id,
            'number' => $this->number,
            'state' => $this->state,
            'destination_type' => $this->destination_type,
            'destination_id' => $this->destination_id,
            'destination_number' => $this->destination_number,
            'working_hour' => $this->working_hour,
            'working_hours' => $this->working_hours,
        ];
    }
}