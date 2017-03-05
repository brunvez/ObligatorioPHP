<?php

namespace Controllers;

use Models\Property;


class HomeController extends BaseController {

    public static function index() {
        $latest_properties = Property::order_by(['id' => 'desc'])->limit(30)->get();

        static::smarty()->assign('title', 'Home');
        static::smarty()->assign('location', 'Home');
        static::smarty()->assign('properties', $latest_properties);

        static::smarty()->display('home/index.tpl');
    }
}