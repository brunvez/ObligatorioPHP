<?php

namespace Models;

require_once 'BaseModel.php';
require_once 'Neighborhood.php';

class Property extends BaseModel {

    public
        $type,
        $operation,
        $price,
        $square_meters,
        $rooms,
        $bathrooms,
        $garage,
        $title,
        $body,
        $neighborhood_id;

    private
        $neighborhood;

    protected static $table_name = 'properties';

    function save() {
        // TODO: implement
        return false;
    }

    /**
     * @return Neighborhood
     */
    public function neighborhood() {
        if (empty($this->neighborhood)) {
            $stmt = static::connection()->prepare('SELECT * FROM neighborhoods WHERE id = :neighborhood_id');
            $stmt->bindParam(':neighborhood_id', $this->neighborhood_id);
            $stmt->execute();
            $this->neighborhood = $stmt->fetchObject('\Models\Neighborhood');
        }
        return $this->neighborhood;
    }

    public function type(){
        return $this->type === 'A' ? 'Apartment' : 'House';
    }

    public function operation(){
        return $this->operation === 'R' ? 'Rental' : 'For Sale';
    }
}