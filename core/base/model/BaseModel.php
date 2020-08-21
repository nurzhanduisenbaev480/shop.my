<?php

namespace core\base\model;

use core\base\controller\Singleton;
use core\base\exceptions\DbException;

class BaseModel
{
    use Singleton;

    protected $db;

    private function __construct()
    {
        $this->db = @new \mysqli(HOST, USER, PASSWORD, DB_NAME);

        if ($this->db->connect_error){
            throw new DbException('Ошибка подключение к базе данных: ' .
                $this->db->connect_errno . " " . $this->db->connect_error);
        }
        $this->db->query("SET NAMES UTF8");
    }
    final public function query($query, $crud = 'r', $return_id = false){
        $result = $this->db->query($query);
        if ($this->db->affected_rows === -1){
            throw new DbException(
                'Ошибка в SQL запросе: '
                .$query.' - '.$this->db->errno.' - '.$this->db->error
            );
        }
        switch ($crud){
            case 'r':
                if ($result->num_rows){
                    $results = [];
                    for ($i = 0; $i < $result->num_rows; $i++){
                        $results[] = $result->fetch_assoc();
                    }
                    return $results;
                }
                return false;
                break;
            case 'c':
                if ($return_id){
                    return $this->db->insert_id;
                }
                return true;
                break;
            default:
                return true;
                break;
        }

    }
}