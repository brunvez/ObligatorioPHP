<?php

namespace Models;


class Question extends BaseModel {

    protected static $table_name = 'questions';

    public
        $property_id,
        $date,
        $body,
        $response,
        $response_date;

    public function answer($response) {
        if (empty($response)) {
            return false;
        }
        $stmt = self::connection()->prepare("UPDATE questions SET response = :response, response_date = NOW() WHERE id = :id");
        $stmt->bindParam(':response', $response);
        $stmt->bindParam(':id', $this->id());
        if ($stmt->execute()) {
            $this->body = $response;
            return true;
        } else {
            return false;
        }
    }

    public function save() {
        if ($this->is_valid()) {
            $stmt = self::connection()
                        ->prepare('INSERT INTO questions (body, property_id, date) VALUES (:body, :property_id, NOW())')
            ;
            $stmt->bindParam(':body', $this->body);
            $stmt->bindParam(':property_id', $this->property_id);
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

    private function is_valid() {
        return !empty($this->body);
    }
}