<?php
/**
 *
 * @framework: Bycod
 * @package: Secretary
 * @version: 1.0
 * @description: This is simple and light lib for manage DBMS
 * @authors: Ing. Antonio Membrides Espinosa<amembrides@uci.cu>
 * @authors: Ing. Felix Ivan Romero Rodriguez<firomerorom4@gmail.com>
 * @license: MIT
 *
 *
 */
namespace Secretary\src\server\driver;
use SQLite3;

class DrSQLITE extends DbDriver
{
    public $dbm;
    public $path;

    public function __construct($config)
    {
        $this->path = ':memory:';
        $this->dbm = false;
        parent::__construct($config);
    }

    public function setting ($key=false, $value=false){
        parent::setting($key, $value);
        $file = $this->path.$this->name.'.db';
        $this->path = ($this->path != ':memory:' and file_exists($file)) ? $file : ':memory:';
    }
    public function query($sql)
    {
        $this->connect();
        $stm = @$this->dbm->prepare($sql);
        $out = @$stm->execute();
        if (!$out) {
            $this->log('ERROR: ' . $this->dbm->lastErrorMsg());
            return false;
        }
        $this->records[] = $sql;
        $out = $this->selected($sql) ? $this->extract($out) : $out;
        $this->disconnect();
        return $out;
    }

    public function connect()
    {
        //... en desarrollo e inestable ...
        $this->dbm = new SQLite3($this->path);
    }

    public function disconnect()
    {
        $this->dbm->close();
    }

    public function extract($results){
        if(!$results) return false;
        $res = array();
        while ($res[] = $results->fetchArray(SQLITE3_ASSOC));
        return $res;
    }
}
