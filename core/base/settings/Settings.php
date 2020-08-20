<?php

namespace core\base\settings;

class Settings
{
    static private $_instance;
    private $routes = [
        'admin' => [
            'name' => 'admin',
            'path' => 'core/admin/controller/',
            'hrUrl' => false
        ],
        'settings' => [
            'path' => 'core/base/settings/'
        ],
        'plugins' => [
            'path' => 'core/base/plugins/',
            'hrUrl' => false
        ],
        'user' => [
            'path' => 'core/user/controller/',
            'hrUrl' => true,
            'routes' => [

            ]
        ],
        'default' => [
            'controller' => 'IndexController',
            'inputMethod' => 'inputData',
            'outputMethod' => 'outputData'
        ]
    ];
    private $templateArr = [
        'text' => ['name', 'phone', 'address'],
        'textarea' => ['content', 'keywords']
    ];

    private function __construct()
    {
    }
    private function __clone()
    {
        // TODO: Implement __clone() method.
    }
    static public function get($property){
        return self::instance()->$property;
    }
    static public function instance(){
        if (self::$_instance instanceof self){
            return self::$_instance;
        }
        return self::$_instance = new self;
    }
    public function clueProperties($class){
        $baseProperties = [];
        foreach ($this as $propertyName => $propertyValue){
            $property = $class::get($propertyName);
            if (is_array($property) && is_array($propertyValue)){
//                $newProperty = array_merge_recursive($this->$propertyName, $property);
                $baseProperties[$propertyName] = array_replace_recursive($this->$propertyName, $property);
                // https://www.youtube.com/watch?v=Mj3mIdnVBwU&list=PLfWxkvC096mJzCJr7yQHCBM7IsM-pniPD&index=6 1:00:00
            }
        }
    }
}