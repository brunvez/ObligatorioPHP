<?php

namespace Models;

require_once 'BaseModel.php';

class DismissReason extends BaseModel {

    public           $description;
    protected static $table_name = 'dismiss_reasons';


    function save() {
        $stmt = static::connection()->prepare('INSERT INTO dismiss_reasons (description) VALUES (:description)');
        $stmt->bindParam(':description', $this->description, \PDO::PARAM_STR);
        return $stmt->execute();
    }
}