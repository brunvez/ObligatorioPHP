<?php

namespace Models;

require_once 'BaseModel.php';
require_once 'City.php';

class Neighborhood extends BaseModel {
    public           $name, $city_id;
    protected static $table_name = 'neighborhoods';
    private          $city;

    public function city() {
        if(!isset($city)){
            $this->city = array_pop(City::where('id = ?', $this->city_id)->get());
        }
        return $this->city;
    }

    public function average_price_per_square_meter(){
        $stmt = self::connection()->prepare('SELECT AVG(price / square_meters) FROM properties WHERE neighborhood_id = :id');
        $stmt->bindParam(':id', $this->id());

        $stmt->execute();
        return round($stmt->fetchColumn());
    }
}
