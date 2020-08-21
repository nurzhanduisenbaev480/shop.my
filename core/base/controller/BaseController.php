<?php

namespace core\base\controller;

use core\base\exception\RouteException;

abstract class BaseController
{
    protected $controller;
    protected $inputMethod;
    protected $outputMethod;
    protected $parameters;

    /**
     * @throws RouteException
     */
    public function route(){
        $controller = str_replace('/', '\\', $this->controller);
        try {
            $object = new \ReflectionMethod($controller, 'request');

            $args = [
                'parameters' => $this->parameters,
                'inputMethod' => $this->inputMethod,
                'outputMethod' => $this->outputMethod,
            ];
            $object->invoke(new $controller, $args);
        }catch (\ReflectionException $exception){
            throw new RouteException($exception);
        }

    }
    public function request($args){

    }
}