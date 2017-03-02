<?php

namespace Controllers;

require_once dirname(__FILE__) . '/../lib/smarty/libs/Smarty.class.php';

abstract class BaseController {
    protected static $smarty;

    /**
     * @return \Smarty
     */
    protected static function smarty() {
        if (!isset(static::$smarty)) {
            static::$smarty               = new \Smarty();
            static::$smarty->template_dir = 'views';
            static::$smarty->compile_dir  = 'views_c';
            static::$smarty->caching      = \Smarty::CACHING_OFF;
        }
        return static::$smarty;
    }

    protected function render_json($data){
        header('Content-Type: application/json');
        echo json_encode($data);
    }
}