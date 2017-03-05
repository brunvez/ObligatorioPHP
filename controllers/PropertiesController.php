<?php

namespace Controllers;

require_once 'BaseController.php';
require_once dirname(__FILE__) . '/../lib/QueryBuilder.php';

use Models\City;
use Models\DismissReason;
use Models\Neighborhood;
use Models\Property;


class PropertiesController extends BaseController {

    public function index() {
        if (!empty($_GET)) {
            if (!empty($_GET['operation']) && !empty($_GET['city'])) {
                $query    = $this->build_search_query($_GET);
                $page     = !empty($_GET['page']) ? (int)$_GET['page'] : 1;
                $per_page = !empty($_GET['per_page']) ? (int)$_GET['per_page'] : 10;

                $total_count = $query->count();
                self::smarty()->assign('total_count', $total_count);

                // $query->limit($per_page)->offset($per_page * ($page - 1));
                $properties = $query->get();
            } else {
                $properties = [];
                self::smarty()->assign('error', 'You must select a city and an operation');
            }
        } else {
            $properties = [];
        }

        $cities = City::all();

        if (!empty($_GET['city'])) {
            $neighborhoods = Neighborhood::where('city_id = :city', [':city' => $_GET['city']])->get();
            static::smarty()->assign('neighborhoods', $neighborhoods);
        }

        static::smarty()->assign('properties', $properties);
        static::smarty()->assign('cities', $cities);
        static::smarty()->assign('location', 'properties');

        self::smarty()->display('properties/index.tpl');
    }

    public function manage_properties() {
        $page     = !empty($_GET['page']) ? (int)$_GET['page'] : 1;
        $per_page = !empty($_GET['per_page']) ? (int)$_GET['per_page'] : 100;

        $query = Property::where('deleted = :deleted', [':deleted' => false]);

        $total_count = $query->count();
        $properties  = $query->limit($per_page)
                             ->offset($per_page * ($page - 1))
                             ->get()
        ;

        $dismiss_reasons = DismissReason::all();

        $last_page = round($total_count / $per_page) == $page - 1;

        self::smarty()->assign('properties', $properties);
        self::smarty()->assign('dismiss_reasons', $dismiss_reasons);

        self::smarty()->assign('next_page', $page + 1);
        self::smarty()->assign('last_page', $last_page);

        self::smarty()->assign('previous_page', $page - 1);
        self::smarty()->assign('first_page', $page == 1);

        self::smarty()->assign('location', 'admin');
        self::smarty()->display('properties/manage_properties.tpl');
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
        $stmt = \DB::connect()->prepare('UPDATE properties SET deleted = TRUE, dismiss_reason_id = :reason WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':reason', $_POST['reason']);
        $stmt->execute();
        echo 'Property deleted successfully';
    }

    /**
     * @param $params
     * @return \QueryBuilder
     */
    private function build_search_query($params) {
        $query = Property::where('operation = :operation', [':operation' => $params['operation']])
                         ->where('deleted = FALSE')
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
    }
}