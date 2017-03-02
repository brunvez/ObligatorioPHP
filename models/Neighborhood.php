<?php

namespace Models;

require_once 'BaseModel.php';
require_once 'City.php';

class Neighborhood extends BaseModel {
    public           $name, $city_id;
    protected static $table_name = 'neighborhoods';
    private          $city;


    public function save() {
        $stmt = static::connection()->prepare('INSERT INTO neighborhoods (name) VALUES (:name)');
        $stmt->bindParam(':name', $this->name, \PDO::PARAM_STR);
        return $stmt->execute();
    }

    public function city() {
        if(!isset($city)){
            $this->city = array_pop(City::where('id = ?', $this->city_id)->get());
        }
        return $this->city;
    }

}
