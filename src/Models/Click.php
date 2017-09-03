<?php

namespace Shortener\Models;


class Click
{
    public $id;
    public $link_id;
    public $click_time;
    public $referer;

    public function __construct($id, $link_id, $click_time, $referer)
    {
        $this->id = $id;
        $this->link_id = $link_id;
        $this->click_time = $click_time;
        $this->referer = $referer;
    }
}