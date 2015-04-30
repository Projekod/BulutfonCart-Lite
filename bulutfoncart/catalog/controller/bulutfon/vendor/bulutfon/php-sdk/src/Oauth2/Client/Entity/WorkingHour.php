<?php
/**
 * Created by PhpStorm.
 * User: htkaya
 * Date: 23/04/15
 * Time: 13:09
 */

namespace Bulutfon\OAuth2\Client\Entity;


class WorkingHour extends BaseEntity {
    protected $monday;
    protected $tuesday;
    protected $wednesday;
    protected $thursday;
    protected $friday;
    protected $saturday;
    protected $sunday;

    public function getArrayCopy()
    {
        return [
            'monday' => $this->monday,
            'tuesday' => $this->tuesday,
            'wednesday' => $this->wednesday,
            'thursday' => $this->thursday,
            'friday' => $this->friday,
            'saturday' => $this->saturday,
            'sunday' => $this->sunday,
        ];
    }

    
}