<?php

namespace Bulutfon\OAuth2\Client\Entity;


class CdrObject extends BaseEntity {

    protected $cdrs;
    protected $previous_page;
    protected $next_page;
    protected $page;

    public function getArrayCopy()
    {
        return [
            'cdrs' => $this->cdrs,
            'previous_page' => $this->previous_page,
            'next_page' => $this->next_page,
            'page' => $this->page,
        ];
    }
}