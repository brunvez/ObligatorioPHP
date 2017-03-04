<?php

namespace Controllers;

use Models\Property;

require_once 'BaseController.php';
require_once dirname(__FILE__) . '/../models/Property.php';


class HomeController extends BaseController {

    public static function index() {
        $latest_properties = Property::order_by(['id' => 'desc'])->limit(30)->get();

        static::smarty()->assign('title', 'Home');
        static::smarty()->assign('location', 'Home');
        static::smarty()->assign('properties', $latest_properties);

        static::smarty()->display('home/index.tpl');
    }

    public static function about() {
        foreach (getallheaders() as $name => $value) {
            echo "$name: $value\n";
        }
        static::smarty()->display('home/index.tpl');
    }
}