<?php

require_once('./lib/smarty/libs/Smarty.class.php');

class Login {
    static function index() {
        $smarty = new Smarty();

        $smarty->template_dir = 'views';
        $smarty->compile_dir = 'views_c';

        $style_urls = Array('css/login.css');

        $smarty->assign('title', 'Login');
        $smarty->assign('style_urls', $style_urls);
        $smarty->assign('script_urls', Array());
        $smarty->assign('show_nav', false);
        $smarty->assign('error', false);


        $smarty->display('login.tpl');
    }
}
