<?php

namespace Shortener\Models;


class Link
{
    public $id;
    public $user_id;
    public $fullLink;
    public $shortLink;

    public function __construct($id, $user_id, $fullLink, $shortLink)
    {
        $this->id = $id;
        $this->user_id = $user_id;
        $this->fullLink = $fullLink;
        $this->shortLink = $shortLink;
    }
}