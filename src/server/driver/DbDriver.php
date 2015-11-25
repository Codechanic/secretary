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
abstract class DbDriver
{
	public $name;
	public $log;
	protected $records;
	protected $connection;

	public function __construct($config=false){
		$this->log  = 'log/';
		$this->setting($config);
		$this->records = array();
		$this->connection = null;
	}
	public function trace(){ return $this->records; }
	public function log($msg){
		$date = date('Y/m/d h:i:s'); 
		error_log("Date: $date, $msg \n", 3, $this->log."secretary.log"); 
	}
	public function setting ($key=false, $value=false){
		if($key){
			if(is_array($key)) 
				foreach($key as $k=>$i) $this->{$k} = $i;
			else if($key) $this->{$key} = $value;
		}
		return $this;
	}
	protected function selected($sql){
		return (strtolower(substr(trim($sql), 0, 6)) == 'select');
	}

	abstract public function query($sql);
	abstract public function connect();
	abstract public function disconnect();
	abstract public function extract($count);
}
