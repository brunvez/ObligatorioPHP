<?php

const HOST     = 'localhost';
const DATABASE = 'real_estate';
const USER     = 'root';
const PASSWORD = 'root';

class DB {
    private static $conn;

    /**
     * @return PDO
     */
    public static function connect() {
        if (!isset(static::$conn)) {
            try {
                static::$conn = new PDO('mysql:host=' . HOST . ';dbname=' . DATABASE, USER, PASSWORD);
                static::$conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
                static::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
            } catch (PDOException $e) {
                exit('Could not connect to database');
            }
        }
        return static::$conn;
    }
}

