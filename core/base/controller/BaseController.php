<?php

namespace core\base\controller;

use core\base\exceptions\RouteException;
use core\base\settings\Settings;

abstract class BaseController
{
    use BaseMethods;
    protected $page;
    protected $errors;
    protected $controller;
    protected $inputMethod;
    protected $outputMethod;
    protected $parameters;

    protected $styles;
    protected $scripts;

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
            throw new RouteException($exception->getMessage());
        }

    }

    /**
     * @param $args
     */
    public function request($args){
        $this->parameters = $args['parameters'];
        $inputData  = $args['inputMethod'];
        $outputData = $args['outputMethod'];

        $data = $this->$inputData();
        if (method_exists($this, $outputData)){
            $page = $this->$outputData($data);
            if ($page){
                $this->page = $page;
            }

        }
        elseif($data){
            $this->page = $data;
        }
        if ($this->errors){
            $this->writeLog($this->errors);
        }
        $this->getPage();
    }

    /**
     * @param string $path
     * @param array $parameters
     * @return false|string
     * @throws RouteException
     * @throws \ReflectionException
     */
    protected function render($path='', $parameters = []){
        extract($parameters);
        if (!$path){
            $class = new \ReflectionClass($this);
            $namespace = str_replace('\\', '/', $class->getNamespaceName().'\\');
            $routes = Settings::get('routes');
            if ($namespace === $routes['user']['path']){
                $template = TEMPLATES;
            }else{
                $template = ADMIN_TEMPLATES;
            }

            $path = $template . explode('controller', strtolower((new \ReflectionClass($this))->getShortName()))[0];
        }
        ob_start();
        if (!@include_once $path . '.php') throw new RouteException('Отсуствует шаблон - '.$path);
        return ob_get_clean();
    }

    /**
     *
     */
    protected function getPage(){
        if (is_array($this->page)){
            foreach ($this->page as $block){
                echo $block;
            }
        }else{
            echo $this->page;
        }
        exit();
    }

    /**
     * @param bool $admin
     */
    protected function init($admin = false){
        if (!$admin){
            if (USER_CSS_JS['styles']){
                foreach (USER_CSS_JS['styles'] as $item){
                    $this->styles[] = PATH . TEMPLATES . trim($item,'/');
                }
            }
            if (USER_CSS_JS['scripts']){
                foreach (USER_CSS_JS['scripts'] as $item){
                    $this->scripts[] = PATH . TEMPLATES . trim($item,'/');
                }
            }
        }else{
            if (ADMIN_CSS_JS['styles']){
                foreach (USER_CSS_JS['styles'] as $item){
                    $this->styles[] = PATH . ADMIN_TEMPLATES . trim($item,'/');
                }
            }
            if (ADMIN_CSS_JS['scripts']){
                foreach (USER_CSS_JS['scripts'] as $item){
                    $this->scripts[] = PATH . ADMIN_TEMPLATES . trim($item,'/');
                }
            }
        }
    }
}