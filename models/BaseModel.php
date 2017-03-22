<?php

namespace Models;

use JsonSerializable;

require_once dirname(__FILE__) . '/../config/connection.php';
require_once dirname(__FILE__) . '/../lib/QueryBuilder.php';

abstract class BaseModel implements JsonSerializable {

    protected static $conditions = [];
    protected        $id;
    private static   $table_name;
    protected static $db;

    //      Public Methods

    function __construct() {
    }

    /**
     * @return int
     */
    public function id() {
        return $this->id;
    }

    /**
     * Creates a new record from  set of properties, the record is an instance of
     * the class that calls this method
     *
     * @param $properties array
     * @return object
     */
    public static function build($properties) {
        $class             = new \ReflectionClass(get_called_class());
        $instance          = $class->newInstanceArgs();
        $public_properties = $class->getProperties(\ReflectionProperty::IS_PUBLIC);
        $public_properties = array_map(function (\ReflectionProperty $reflection) {
            return $reflection->getName();
        }
            , $public_properties);
        foreach ($properties as $property => $value) {
            if (in_array($property, $public_properties)) {
                $instance->{$property} = $value;
            }
        }
        return $instance;
    }


    /**
     * @param array $properties
     * @return string
     */
    public static function create($properties) {
        $instance = static::build($properties);
        return $instance->save();
    }

    public function jsonSerialize() {
        return get_object_vars($this);
    }

    /**
     * @param string $options SQL defining special options (e.g LIMIT, WHERE) NOT FOR USER INPUT
     * @return array
     */
    public static function all($options = null) {
        $table = static::$table_name;
        $sql   = "SELECT * FROM ${table} ${options}";
        if ($results = static::connection()->query($sql)) {
            return $results->fetchAll(\PDO::FETCH_CLASS, get_called_class());
        }
        return null;
    }

    public function destroy() {
        $table = static::$table_name;
        $stmt  = self::connection()->prepare("DELETE FROM ${table} WHERE id = :id");
        $stmt->bindParam(':id', $this->id());
        return $stmt->execute();
    }

    /**
     * @param $name
     * @param $arguments
     * @return \QueryBuilder|null returns a querier if the method is valid
     */
    public static function __callStatic($name, $arguments) {
        if (!method_exists(get_called_class(), $name)) {
            $query_builder = new \QueryBuilder(static::$table_name, get_called_class());
            if (method_exists('\QueryBuilder', $name)) {
                return call_user_func_array([$query_builder, $name], $arguments);
            }
        }
        return null;
    }


    /**
     * This method is implemented to stop PDO from setting variables
     * that are not defined (i.e. password on User)
     *
     * @param $name
     * @param $value
     */
    function __set($name, $value) {
    }

    // Private and protected methods

    /**
     * @return \PDO
     */
    protected static function connection() {
        if (!isset(static::$db)) {
            static::$db = \DB::connect();
        }
        return static::$db;
    }
}