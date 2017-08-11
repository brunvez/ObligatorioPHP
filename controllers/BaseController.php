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

        static::assign_logged_in(static::$smarty);
        static::session_to_smarty(static::$smarty);
        return static::$smarty;
    }

    /**
     * Renders the given data as JSON
     *
     * @param $data mixed
     */
    protected function render_json($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    protected function request_is_ajax() {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }

    /**
     * Redirects to the given URL
     *
     * @param $url
     */
    protected function redirect_to($url) {
        header("Location: $url");
    }

    /**
     * @param $smarty \Smarty
     */
    private static function assign_logged_in($smarty) {
        $user_is_logged = isset($_SESSION['user']) && $_SESSION['user'];
        $smarty->assign('user_is_logged', $user_is_logged);
        if ($user_is_logged) {
            $smarty->assign('user', $_SESSION['user']);
        }
    }

    /**
     * @param $smarty \Smarty
     */
    private static function session_to_smarty($smarty){
        foreach ($_SESSION as $key => $value) {
            if($key !== 'user'){
                $smarty->assign($key, $value);
                unset($_SESSION[$key]);
            }
        }
    }
}