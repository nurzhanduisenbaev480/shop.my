<?php

use core\base\exceptions\RouteException;

defined('VG_ACCESS') or die('Access denied');

const TEMPLATES = 'templates/default/';
const ADMIN_TEMPLATES = 'core/admin/view/';

const COOKIE_VERSION    = '1.0.0';
const CRYPT_KEY         = '';
const COOKIE_TIME       = 60;
const BLOCK_TIME        = 3;

const QTY               = 8;
const QTY_LINKS         = 3;

const ADMIN_CSS_JS = [
    'styles'  => [],
    'scripts' => []
];
const USER_CSS_JS = [
    'styles'  => [],
    'scripts' => []
];
function autoload($class_name){
    $class_name = str_replace('\\', '/', $class_name);
    if (!@include_once $class_name.'.php'){
        throw new RouteException('Неверное имя файла для подключения - '.$class_name);
    }
}
spl_autoload_register('autoload');