<?php

namespace Models;

require_once 'BaseModel.php';
require_once 'Neighborhood.php';

class City extends BaseModel {

    public           $name;
    protected static $table_name = 'cities';

    function save() {
        $stmt = static::connection()->prepare('INSERT INTO cities (name) VALUES (:name)');
        $stmt->bindParam(':name', $this->name, \PDO::PARAM_STR);
        return $stmt->execute();
    }

    /**
     * @return array
     */
    function neighborhoods() {
        $stmt = static::connection()->prepare('SELECT * FROM neighborhoods WHERE neighborhoods.city_id = :city_id');
        $stmt->bindParam(':city_id', $this->id);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_CLASS, 'Models\Neighborhood');
    }
}