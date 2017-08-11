<?php

namespace Models;


class Image extends BaseModel {

    public
        $url,
        $property_id;

    protected static $table_name = 'images';

    public function save(){
        if($this->is_valid()){
            $stmt = self::connection()->prepare('INSERT INTO images (url, property_id) VALUES (:url, :property_id)');
            $stmt->bindParam(':url', $this->url);
            $stmt->bindParam(':property_id', $this->property_id);
            if($stmt->execute()){
                $this->id = self::connection()->lastInsertId();
                return $this;
            } else {
                return false;
            }
        } else{
            return false;
        }
    }

    private function is_valid() {
        return !empty($this->url);
    }
}