<?php

namespace Models;

require_once 'BaseModel.php';
require_once 'Neighborhood.php';
require_once 'Image.php';
require_once 'Question.php';

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
        $description,
        $neighborhood_id;

    private
        $neighborhood,
        $images,
        $questions;

    protected static $table_name = 'properties';

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

    public function images() {
        if (empty($this->images)) {
            $stmt = static::connection()->prepare('SELECT * FROM images WHERE property_id = :id');
            $stmt->bindParam(':id', $this->id());
            $stmt->execute();
            $this->images = $stmt->fetchAll(\PDO::FETCH_CLASS, '\Models\Image');
        }
        return $this->images;
    }

    public function first_image() {
        return count($this->images()) > 0 ? $this->images()[0] : null;
    }

    public function questions() {
        if (empty($this->questions)) {
            $stmt = static::connection()->prepare('SELECT * FROM questions WHERE property_id = :id');
            $stmt->bindParam(':id', $this->id());
            $stmt->execute();
            $this->questions = $stmt->fetchAll(\PDO::FETCH_CLASS, '\Models\Question');
        }
        return $this->questions;
    }

    public function type() {
        return $this->type === 'A' ? 'Apartment' : 'House';
    }

    public function operation() {
        return $this->operation === 'R' ? 'Rental' : 'For Sale';
    }

    public function has_garage() {
        return $this->garage ? 'Yes' : 'No';
    }

    public function price_per_square_meter(){
        if($this->square_meters == 0){
            return 0;
        } else {
            return round($this->price / $this->square_meters);
        }
    }

    public function save() {
        if ($this->is_valid()) {
            $stmt = self::connection()->prepare('INSERT INTO properties 
                (type, operation, price, square_meters, rooms, bathrooms, garage, title, description, neighborhood_id) 
         VALUES (:type, :operation, :price, :square_meters, :rooms, :bathrooms, :garage, :title, :description, :neighborhood_id)')
            ;
            $stmt->bindParam(':type', $this->type);
            $stmt->bindParam(':operation', $this->operation);
            $stmt->bindParam(':price', $this->price);
            $stmt->bindParam(':square_meters', $this->square_meters);
            $stmt->bindParam(':rooms', $this->rooms);
            $stmt->bindParam(':bathrooms', $this->bathrooms);
            $stmt->bindParam(':garage', $this->garage);
            $stmt->bindParam(':title', $this->title);
            $stmt->bindParam(':description', $this->description);
            $stmt->bindParam(':neighborhood_id', $this->neighborhood_id);
            if ($stmt->execute()) {
                $this->id = self::connection()->lastInsertId();
                return $this;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function update() {
        if ($this->is_valid()) {
            $stmt = self::connection()
                        ->prepare('UPDATE properties SET type = :type, operation = :operation, 
                                                        price = :price, square_meters = :square_meters, 
                                                        rooms = :rooms, bathrooms = :bathrooms, 
                                                        garage = :garage, title = :title, 
                                                        description = :description, neighborhood_id = :neighborhood_id 
                                                        WHERE id = :id')
            ;
            $stmt->bindParam(':id', $this->id());
            $stmt->bindParam(':type', $this->type);
            $stmt->bindParam(':operation', $this->operation);
            $stmt->bindParam(':price', $this->price);
            $stmt->bindParam(':square_meters', $this->square_meters);
            $stmt->bindParam(':rooms', $this->rooms);
            $stmt->bindParam(':bathrooms', $this->bathrooms);
            $stmt->bindParam(':garage', $this->garage);
            $stmt->bindParam(':title', $this->title);
            $stmt->bindParam(':description', $this->description);
            $stmt->bindParam(':neighborhood_id', $this->neighborhood_id);
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    private function is_valid() {
        $is_valid = true;
        $is_valid &= in_array($this->type, ['A', 'H']);
        $is_valid &= in_array($this->operation, ['R', 'S']);
        $is_valid &= is_numeric($this->price) && $this->price >= 0;
        $is_valid &= is_numeric($this->square_meters) && $this->square_meters > 0;
        $is_valid &= is_numeric($this->rooms) && $this->rooms > 0;
        $is_valid &= is_numeric($this->bathrooms) && $this->bathrooms > 0;
        $is_valid &= in_array($this->garage, ['1', '0', 'true', 'false']);
        $is_valid &= !empty($this->title);
        $is_valid &= !empty($this->neighborhood_id) && $this->neighborhood();
        return $is_valid;
    }
}