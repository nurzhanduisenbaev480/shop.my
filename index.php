<?php
define('VG_ACCESS', true);

header('Content-Type: text/html;charset=utf-8');

session_start();

require_once 'config.php';
require_once 'core/base/settings/internal_settings.php';
require_once 'lib/functions.php';

use core\base\controller\RouteController;
use core\base\exception\RouteException;

try{
    RouteController::getInstance()->route();
}catch (RouteException $exception){
    exit($exception->getMessage());
}

