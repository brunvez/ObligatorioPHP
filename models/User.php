<?php

namespace Models;


class User extends BaseModel{

    public $username, $first_name, $last_name;

    public function full_name(){
        return "$this->first_name $this->last_name";
    }
}