<?php

namespace Controllers;

require_once 'BaseController.php';
require_once dirname(__FILE__) . '/../lib/QueryBuilder.php';
require_once dirname(__FILE__) . '/../lib/Paginator.php';

use Models\City;
use Models\DismissReason;
use Models\Image;
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

                $query->limit($per_page)->offset($per_page * ($page - 1));
                $properties = $query->get();

                $paginator = new \Paginator('properties', $total_count, $per_page, $_GET);
                self::smarty()->assign('paginator', $paginator);
            } else {
                $properties = [];
                self::smarty()->assign('error', 'You must select a city and an operation');
            }
        } else {
            $properties = [];
        }

        static::smarty()->assign('properties', $properties);
        if ($this->request_is_ajax()) {
            if (!isset($paginator)) {
                $paginator = null;
            }
            $properties_list = self::smarty()->fetch('partials/properties/properties_list.tpl');
            $this->render_json(['properties' => $properties_list, 'paginator' => (string)$paginator]);
        } else {
            $cities = City::all();

            if (!empty($_GET['city'])) {
                $neighborhoods = Neighborhood::where('city_id = :city', [':city' => $_GET['city']])->get();
                static::smarty()->assign('neighborhoods', $neighborhoods);
            }

            static::smarty()->assign('cities', $cities);
            static::smarty()->assign('location', 'properties');

            self::smarty()->display('properties/index.tpl');
        }
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
        $paginator   = new \Paginator('manage_properties', $total_count, $per_page, $_GET);

        $dismiss_reasons = DismissReason::all();

        self::smarty()->assign('properties', $properties);
        self::smarty()->assign('dismiss_reasons', $dismiss_reasons);

        self::smarty()->assign('paginator', $paginator);

        self::smarty()->assign('location', 'admin');
        self::smarty()->display('properties/manage_properties.tpl');
    }

    public function create() {
        if (self::smarty()->getTemplateVars('property') === null) {
            $property = Property::build([]);
            self::smarty()->assign('property', $property);
        }

        $neighborhoods = Neighborhood::all();
        self::smarty()->assign('neighborhoods', $neighborhoods);
        self::smarty()->assign('location', 'admin');
        self::smarty()->display('properties/create.tpl');
    }

    public function store() {
        $property = Property::build($_POST['property']);
        if ($property->save()) {
            $id         = $property->id();
            $upload_dir = "properties/${id}";
            $uploader   = new \ImageUploader($_FILES['photos']);
            if ($path_to_photos = $uploader->upload($upload_dir)) {
                foreach ($path_to_photos as $path) {
                    Image::create(['url' => $path, 'property_id' => $id]);
                }
                $_SESSION['success'] = 'Property created successfully.';
                $this->redirect_to('manage_properties');
            } else {
                $property->destroy();
                $_SESSION['property'] = $property;
                $_SESSION['error']    = 'Could not upload photos, try again';
                $this->redirect_to('create');
            }
        } else {
            $_SESSION['property'] = $property;
            $_SESSION['error']    = 'Invalid property, please fill out every field correctly.';
            $this->redirect_to('create');
        }
    }

    public function show($id) {
        $property = Property::find($id);
        self::smarty()->assign('property', $property);
        self::smarty()->assign('location', 'properties');

        self::smarty()->display('properties/show.tpl');
    }

    public function modal($id) {
        $property = Property::find($id);
        self::smarty()->assign('property', $property);

        self::smarty()->display('partials/properties/modal.tpl');
    }

    public function edit($id) {
        $property      = Property::find($id);
        $neighborhoods = Neighborhood::all();

        self::smarty()->assign('property', $property);
        self::smarty()->assign('neighborhoods', $neighborhoods);

        self::smarty()->assign('location', 'admin');
        self::smarty()->display('properties/edit.tpl');
    }

    public function update($id) {
        if ($property = Property::find($id)) {
            foreach ($_POST['property'] as $attr => $value) {
                $property->{$attr} = $value;
            }
            if ($property->update()) {
                $_SESSION['success'] = 'Property updated successfully.';
                $this->redirect_to('/properties/manage_properties');
            } else {
                $_SESSION['property'] = $property;
                $_SESSION['error']    = 'Invalid property, please fill out every field correctly.';
                $this->redirect_to('create');
            }
        } else {
            $this->render_json(['error' => 'Could not find Property.']);
        }
    }

    public function most_expensive(){
        $db = \DB::connect();
        $stmt = $db->prepare('SELECT id, operation, MAX(price) FROM properties GROUP BY operation');
        $stmt->execute();

        $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $properties = [];
        foreach ($results as $result){
            array_push($properties, Property::find($result['id']));
        }
        self::smarty()->assign('location', 'most_expensive');
        self::smarty()->assign('properties', $properties);
        self::smarty()->display('properties/most_expensive.tpl');
    }

    public function destroy($id) {
        $stmt = \DB::connect()->prepare('UPDATE properties SET deleted = TRUE, dismiss_reason_id = :reason WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':reason', $_POST['reason']);
        $stmt->execute();
        echo 'Property deleted successfully';
    }

    public function generate_pdf($id) {
        if ($property = Property::find($id)) {
            $generator = new \PDFGenerator($property);
            $generator->generate();
        } else {
            echo '<h1>Property not found</h1>';
        }
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