<?php

namespace Controllers;

require_once 'BaseController.php';
require_once dirname(__FILE__) . '/../lib/QueryBuilder.php';

use Models\City;
use Models\Neighborhood;
use Models\Property;


class PropertiesController extends BaseController {

    public function index() {
        if (empty($_GET)) {
            $query = Property::limit(30);
        } else {
            $query = $this->build_search_query($_GET);

            $page     = !empty($_GET['page']) ? (int)$_GET['page'] : 1;
            $per_page = !empty($_GET['per_page']) ? (int)$_GET['per_page'] : 10;

            $total_count = $query->count();
            self::smarty()->assign('total_count', $total_count);

            // $query->limit($per_page)->offset($per_page * ($page - 1));
        }
        $properties = $query->get();
        $cities     = City::all();

        if (!empty($_GET['city'])) {
            $neighborhoods = Neighborhood::where('city_id = :city', [':city' => $_GET['city']])->get();
            static::smarty()->assign('neighborhoods', $neighborhoods);
        }

        static::smarty()->assign('properties', $properties);
        static::smarty()->assign('cities', $cities);
        static::smarty()->assign('location', 'properties');

        self::smarty()->display('properties/index.tpl');
    }

    public function create() {
        echo 'create';
    }

    public function store() {
        echo 'store';
    }

    public function show($id) {
        $property = Property::find($id);
        self::smarty()->assign('property', $property);

        self::smarty()->display('properties/show.tpl');
    }

    public function edit($id) {
        echo "edit ${id}";
    }

    public function update($id) {
        echo json_encode($_POST);
        echo "update ${id}";
    }

    public function destroy($id) {
        echo "destroy ${id}";
    }

    private function build_search_query($params) {
        if (isset($params['operation']) && !empty($params['operation'])
            && isset($params['city']) && !empty($params['city'])
        ) {
            $query = Property::where('operation = :operation', [':operation' => $params['operation']])
                             ->joins('INNER JOIN neighborhoods ON neighborhoods.id = properties.neighborhood_id')
                             ->where('neighborhoods.city_id = :city', [':city' => $params['city']])
            ;
            if (!empty($params['neighborhood'])) {
                $query->where('neighborhood_id = :neighborhood', [':neighborhood' => $params['neighborhood']]);
            }

            if (!empty($params['type'])) {
                $query->where('type = :type', [':type' => $params['type']]);
            }

            if (!empty($params['rooms'])) {
                $query->where('rooms = :rooms', [':rooms' => $params['rooms']]);
            }

            if (!empty($params['garage'])) {
                $query->where('garage = :garage', [':garage' => $params['garage']]);
            }

            if (!empty($params['price_from'])) {
                $query->where('price >= :price_from', [':price_from' => $params['price_from']]);
            }

            if (!empty($params['price_to'])) {
                $query->where('price <= :price_to', [':price_to' => $params['price_to']]);
            }

            if (!empty($params['order_by'])) {

                if (!empty($params['direction'])) {
                    $query->order_by([$params['order_by'] => $params['direction']]);
                } else {
                    $query->order_by($params['order_by']);
                }
            }
            return $query;
        } else {
            $_SESSION['error'] = 'You must select a city and an operation';
            return Property::limit(30);
        }
    }
}