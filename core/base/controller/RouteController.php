<?php

namespace core\base\controller;

use core\base\exceptions\RouteException;
use core\base\settings\Settings;

class RouteController extends BaseController
{
    use Singleton;
    /**
     * @var
     */
    protected $routes;

    /**
     * RouteController constructor.
     * @throws RouteException
     */
    private function __construct()
    {
        $address = $_SERVER['REQUEST_URI'];
        if (strrpos($address, '/') === strlen($address) - 1 && strrpos($address, '/') !== 0){
            // strrpos() находит позицию последнего совпадения
            // strpos() находит позицию первого совпадения
            $this->redirect(rtrim($address, '/'), 301);

        }
        $path = substr($_SERVER['PHP_SELF'], 0, strpos($_SERVER['PHP_SELF'], 'index.php'));
//        echo substr($address, strlen(PATH));
        if ($path === PATH){
            $this->routes = Settings::get('routes');
            if (!$this->routes){
                throw new RouteException('Отсуствуют маршруты в базовых настройках', 1);
            }
            //Интернет магазин с нуля на php Выпуск №6 контроллер системы маршрутов часть 1
            $route = '';
            $url = explode('/', substr($address, strlen(PATH)));
            if ($url[0] && $url[0] === $this->routes['admin']['alias']){
                // admin side
                array_shift($url);
                if ($url[0] && is_dir($_SERVER['DOCUMENT_ROOT'] . PATH . $this->routes['plugins']['path'] . $url[0])){
                    $plugin = array_shift($url);

                    $pluginSettings = $this->routes['settings']['path'] . ucfirst($plugin . 'Settings');
                    if (file_exists($_SERVER['DOCUMENT_ROOT'] . PATH . $pluginSettings . '.php')){
                        $pluginSettings = str_replace('/', '\\', $pluginSettings);
                        $this->routes = $pluginSettings::get('routes');
                    }
                    $dir = $this->routes['plugins']['dir'] ? '/' . $this->routes['plugins']['dir'] . '/' : '/';
                    $dir = str_replace('//', '/', $dir);
                    $this->controller = $this->routes['plugins']['path'] . $plugin . $dir;
                    $hrUrl = $this->routes['plugins']['hrUrl'];
                    $route = 'plugins';
                }else{
                    $this->controller = $this->routes['admin']['path'];
                    $hrUrl = $this->routes['admin']['hrUrl'];
                    $route = 'admin';
                }

            }else{
                // user side
                $this->controller = $this->routes['user']['path'];
                $hrUrl = $this->routes['user']['hrUrl'];
                $route = 'user';
            }
            $this->createRoute($route, $url);
            if ($url[1]){
                $count = count($url);
                $key = '';
                if (!$hrUrl){
                    $i = 1;
                }else{
                    $this->parameters['alias'] = $url[1];
                    $i = 2;
                }
                for (;$i<$count;$i++){
                    if (!$key){
                        $key = $url[$i];
                        $this->parameters[$key] = '';
                    }else{
                        $this->parameters[$key] = $url[$i];
                        $key = '';
                    }
                }
            }
//            pa($this->parameters);
//            echo $this->controller;

        }else{
            throw new RouteException('Не корректная директория сайта', 1);
        }
    }

    /**
     * @param $env
     * @param $url
     */
    private function createRoute($env, $url){ // env - user:admin, url -  Адресная строка
        $route = [];
//        echo $this->routes[$env]['routes'][$url[0]];
        if (!empty($url[0])){
            if ($this->routes[$env]['routes'][$url[0]]){
                $route = explode('/',$this->routes[$env]['routes'][$url[0]]);
                $this->controller .= ucfirst($route[0].'Controller');
            }else{
                $this->controller .= ucfirst($url[0].'Controller');
            }
        }else{
            $this->controller .= $this->routes['default']['controller'];
        }


        $this->inputMethod  = isset($route[1]) ? $route[1] : $this->routes['default']['inputMethod'];
        $this->outputMethod = isset($route[2]) ? $route[2] : $this->routes['default']['outputMethod'];

        return;
    }

}