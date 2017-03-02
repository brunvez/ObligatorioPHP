<?php
include_once 'models/Property.php';

$properties = \Models\Property::where('id > :id', [':id' => 1])
                              ->order_by(['price' => 'desc', 'square_meters' => 'asc'])
                              ->get()
;

foreach ($properties as $property) {
    echo json_encode(['price' => $property->price, 'square_meters' => $property->square_meters]);
    echo "\n";
}