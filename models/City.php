<?php

namespace Models;

require_once 'BaseModel.php';
require_once 'Neighborhood.php';

class City extends BaseModel {

    public           $name;
    protected static $table_name = 'cities';

    /**
     * @return array
     */
    function neighborhoods() {
        $stmt = static::connection()->prepare('SELECT * FROM neighborhoods WHERE neighborhoods.city_id = :city_id');
        $stmt->bindParam(':city_id', $this->id());
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_CLASS, 'Models\Neighborhood');
    }
}