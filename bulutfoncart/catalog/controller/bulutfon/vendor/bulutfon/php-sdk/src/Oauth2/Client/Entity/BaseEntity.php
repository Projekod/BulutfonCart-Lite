<?php
namespace Bulutfon\OAuth2\Client\Entity;
class BaseEntity {
    public function __get($name)
    {
        if (!property_exists($this, $name)) {
            throw new \OutOfRangeException(sprintf(
                '%s does not contain a property by the name of "%s"',
                __CLASS__,
                $name
            ));
        }

        return $this->{$name};
    }

    public function __set($property, $value)
    {
        if (!property_exists($this, $property)) {
            throw new \OutOfRangeException(sprintf(
                '%s does not contain a property by the name of "%s"',
                __CLASS__,
                $property
            ));
        }

        $this->$property = $value;

        return $this;
    }

    public function __isset($name)
    {
        return (property_exists($this, $name));
    }

    public function exchangeArray(array $data)
    {
        foreach ($data as $key => $value) {
            $key = strtolower($key);
            if($this->__isset($key)) {
                $this->__set($key, $value);
            }
        }

        return $this;
    }
}