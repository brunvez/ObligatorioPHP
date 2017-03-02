<?php

namespace Controllers;

use Models\City;
use Models\Neighborhood;

class CitiesController extends BaseController {

    public function neighborhoods($city_id) {
        $neighborhoods = Neighborhood::where('city_id = ?', $city_id)->get();

        $this->render_json($neighborhoods);
    }

    public function statistics() {
        $cities = City::all();

        self::smarty()->assign('cities', $cities);
        self::smarty()->assign('location', 'statistics');

        self::smarty()->display('cities/statistics.tpl');
    }

    public function properties_per_neighborhood($city_id) {
        $db  = \DB::connect();
        $sql = 'SELECT ROUND(AVG(properties.price / properties.square_meters)) AS average, 
        COUNT(properties.operation) AS total_count, neighborhoods.name, 
        properties.neighborhood_id, properties.operation
        FROM properties
        INNER JOIN neighborhoods ON neighborhoods.id = properties.neighborhood_id
        WHERE neighborhoods.city_id = :city_id
        GROUP BY properties.neighborhood_id, properties.operation
        ORDER BY neighborhoods.name';

        $stmt = $db->prepare($sql);
        $stmt->bindParam(':city_id', $city_id);
        if ($stmt->execute()) {
            $arr =$stmt->fetchAll(\PDO::FETCH_ASSOC);
            $this->render_json($arr);
        } else {
            $stmt->errorInfo();
        }
    }
}
