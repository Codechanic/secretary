<?php
/**
 * Created by PhpStorm.
 * User: firomero
 * Date: 10/12/2015
 * Time: 9:56 AM
 */

namespace Secretary\src\server\driver;


class DrJSON extends DbDriver{

    protected $relations = array();
    protected $path;

    public function __construct($config=false, $path = 'data/'){
       parent::__construct($config);
        $this->path = $path;
    }

    public function query($sql)
    {
        // TODO: Implement query() method.
    }



    public function connect()
    {
        if (is_dir($this->path)) {

            $this->relations = array();
            if ($dh = opendir($this->path)) {
                while (($file = readdir($dh)) !== false) {
                    $this->relations[]= $file ;
                }
                closedir($dh);
            }
        }

        return $this->relations;
    }

    public function disconnect()
    {
        $this->relations = array();
    }

    public function extract($count)
    {
        return sizeof($this->relations);
    }

    /*
     *
     */
}