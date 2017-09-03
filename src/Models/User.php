<?php

namespace Shortener\Models;


class User
{
    public $id;
    public $email;
    public $name;
    public $passhash;

    public function __construct($id, $email, $name, $passhash)
    {
        $this->id = $id;
        $this->email = $email;
        $this->name = $name;
        $this->passhash = $passhash;
    }
}