<?php

namespace core\user\controller;

use core\base\controller\BaseController;
use core\base\exceptions\RouteException;

class IndexController extends BaseController
{
    protected function inputData(){
        $this->init();
        exit();
    }
}