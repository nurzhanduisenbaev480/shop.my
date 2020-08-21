<?php


namespace core\base\controller;


trait Singleton
{
    /**
     * @var $_instance
     */
    static private $_instance;

    /**
     * Singleton constructor.
     */
    private function __construct()
    {
    }

    /**
     *
     */
    private function __clone()
    {
        // TODO: Implement __clone() method.
    }

    /**
     * @return Singleton
     */
    static public function instance(){
        if (self::$_instance instanceof self){
            return self::$_instance;
        }
        return self::$_instance = new self;
    }
}