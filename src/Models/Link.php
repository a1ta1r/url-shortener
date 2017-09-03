<?php

namespace Shortener\Models;


class Link
{
    public $id;
    public $user_id;
    public $full_link;
    public $short_link;

    public function __construct($id, $user_id, $fullLink, $shortLink)
    {
        $this->id = $id;
        $this->user_id = $user_id;
        $this->full_link = $fullLink;
        $this->short_link = $shortLink;
    }
}