<?php
define('VG_ACCESS', true);

header('Content-Type: text/html;charset=utf-8');

session_start();

require_once 'config.php';
require_once 'core/base/settings/internal_settings.php';
require_once 'lib/functions.php';

use core\base\controller\RouteController;
use core\base\exceptions\DbException;
use core\base\exceptions\RouteException;

try{
    RouteController::instance()->route();
}catch (RouteException $exception){
    exit($exception->getMessage());
}catch (DbException $exception){
    exit($exception->getMessage());
}

