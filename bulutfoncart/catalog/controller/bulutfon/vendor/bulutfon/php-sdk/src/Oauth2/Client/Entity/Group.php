<?php
/**
 * Created by PhpStorm.
 * User: htkaya
 * Date: 23/04/15
 * Time: 13:08
 */

namespace Bulutfon\OAuth2\Client\Entity;

class Group extends BaseEntity {
    protected $id;
    protected $number;
    protected $name;
    protected $timeout;
    protected $extensions;

    public function getArrayCopy()
    {
        return [
            'id' => $this->id,
            'number' => $this->number,
            'name' => $this->name,
            'timeout' => $this->timeout,
            'extensions' => $this->extensions
        ];
    }
}