<?php
/**
 * Created by PhpStorm.
 * User: firomero
 * Date: 10/12/2015
 * Time: 1:19 PM
 */
// Straussn's JSON-Databaseclass
// Handle JSON-Files like a very, very simple DB. Useful for little ajax applications.
// Last change: 05-06-2012
// Version: 1.0b
// by Manuel Strauss, Web: http://straussn.eu, E-Mail: StrZlee@gmx.net, Skype: StrZlee
/*
	Example:
		$db = new JsonDB( "./path_to_my_jsonfiles/" );
		$result = $db -> select( "json_file_name_without_extension", "search-key", "search-value" );

			Example JSON-File:
				[
					{"ID": "0", "Name": "Hans Wurst", "Age": "12"},
					{"ID": "1", "Name": "Karl Stoascheissa", "Age": "15"},
					{"ID": "2", "Name": "Poidl Peidlbecka", "Age": "14"}
				]

		Method Overview:

			new JsonDB(".(path_to_my_jsonfiles/");
			JsonDB -> createTable("hello_world_table");
			JsonDB -> select ( "table", "key", "value" ) - Selects multible lines which contains the key/value and returns it as array
			JsonDB -> selectAll ( "table" )  - Returns the entire file as array
			JsonDB -> update ( "table", "key", "value", ARRAY ) - Replaces the line which corresponds to the key/value with the array-data
			JsonDB -> updateAll ( "table", ARRAY ) - Replaces the entire file with the array-data
			JsonDB -> insert ( "table", ARRAY , $create = FALSE) - Appends a row, returns true on success. if $create is TRUE, we will create the table if it doesn't already exist.
			JsonDB -> delete ( "table", "key", "value" ) - Deletes all lines which corresponds to the key/value, returns number of deleted lines
			JsonDB -> deleteAll ( "table" ) - Deletes the whole data, returns "true" on success
			new JsonTable("./data/test.json", $create = FALSE) - If $create is TRUE, creates table if it doesn't exist.
*/

namespace Secretary\src\server\driver;


class JsonDB {
    protected $path = "./";
    protected $fileExt = ".json";
    protected $tables = array();

    public function __construct($path) {
        if (is_dir($path)) $this->path = $path;
        else throw new \Exception("JsonDB Error: Database not found");
    }

    protected function getTableInstance($table, $create) {
        if (isset($tables[$table])) return $tables[$table];
        else return $tables[$table] = new JsonTable($this->path.$table, $create);
    }

    public function __call($op, $args) {
        if ($args && method_exists("JsonTable", $op)) {
            $table = $args[0].$this->fileExt;
            $create = false;
            if($op == "createTable")
            {
                return $this->getTableInstance($table, true);
            }
            elseif($op == "insert" && isset($args[2]) && $args[2] === true)
            {
                $create = true;
            }
            return $this->getTableInstance($table, $create)->$op($args);
        } else throw new \Exception("JsonDB Error: Unknown method or wrong arguments ");
    }

    public function setExtension($_fileExt) {
        $this->fileExt = $_fileExt;
        return $this;
    }
}