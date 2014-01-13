<?php
/* Adminsystems 3.6 Database Class
 * Database-Type: MySQL
 * Version: 1.1
 */

// +----------------------------------------------------------------------+
// | Adminsystems 3.6 Database Class:                                     |
// | Simple Access to MySQL Database using PHP                            |
// +----------------------------------------------------------------------+
// |                                                                      |
// | Copyright (C) 2010-2011  Aschauer Marvin, Austria                    |
// |                                                                      |
// | This program is free software; you can redistribute it and/or        |
// | modify it under the terms of the GNU General Public License          |
// | as published by the Free Software Foundation; either version 3       |
// | of the License, or (at your option) any later version.               |
// |                                                                      |
// | This program is distributed in the hope that it will be useful,      |
// | but WITHOUT ANY WARRANTY; without even the implied warranty of       |
// | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the        |
// | GNU General Public License for more details.                         |
// |                                                                      |
// | You should have received a copy of the GNU General Public License    |
// | along with this program; if not, write to the Free Software          |
// | Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA            |
// | 02111-1307, USA.                                                     |
// |                                                                      |
// | Author: Landsknecht87.at                                             |
// |                                                                      |
// |                                                                      |
// +----------------------------------------------------------------------+

function asys_db_err($err){
	exit($err);
}

class asys_database{

	function __construct(){
		$this->db_connect();
	}
	// connect function - use to connect to the database
	// if no db_server is given, the function loads the config values!
	function db_connect($db_server = '', $db_user = '', $db_pass = '', $db_name = '') {


		if($db_name == ''){ //asys
			global $conf; //asys
			@$con = mysql_connect($conf['db_server'],$conf['db_user'],$conf['db_pass']); //asys
			$db_name = $conf['db_name']; //asys
		}else{ //asys
			@$con = mysql_connect($db_server, $db_user, $db_pass);
		} //asys


		if (!$con)
		{
			die('MySQL error: Could not connect. Check your DATA/config.php file!' . mysql_error());
		}
		@$sel_db = mysql_select_db($db_name, $con);
		if (!$sel_db)
		{
			die('MySQL error: Database not found. Check your DATA/config.php file!');
		}
		$CONNECTION_STATE = true;
		// Setting the mysql charset
		mysql_query("SET NAMES 'utf8'");
		mysql_query("SET CHARACTER SET 'utf8'");

	}

	/*
	 * Escape String for DB
	 */
	public function escape($string){
		return mysql_real_escape_string($string);
	}


	/*
	 * Run a simple MySQL querry
	 */
	public function db_q($q){
		//echo '<br /><br />' . $q . '<br /><br />';
		$result = mysql_query($q);
		if (!$result){
			asys_db_err('MySQL Error: asys_mysql_q ' . mysql_error(), TRUE);
		}
		return $result;
	}

	/*
	 * Run a simple MySQL querry
	 */
	public function db_fetch_assoc($sql){
		$result = mysql_fetch_assoc($sql);
		if (!$result){
			asys_db_err('MySQL Error: asys_mysql_fetch_assoc ' .  mysql_error(), TRUE);
		}
		return $result;
	}

	public function db_fetch_row($sql){
		$result = mysql_fetch_row($sql);
		if (!$result){
			asys_db_err('MySQL Error: asys_mysql_fetch_row ' .  mysql_error(), TRUE);
		}
		return $result;
	}

	public function db_fetch_array($sql){
		$result = mysql_fetch_array($sql);
		if (!$result){
			asys_db_err('MySQL Error: asys_mysql_fetch_array ' .  mysql_error(), TRUE);
		}
		return $result;
	}

	/*
	 * Asys-mysql get Table fields
	 * Lists all Fields of a database table
	 * $table; the name of the table
	 * $exclude; the field-names to exclude
	 * $syntax_sort; TRUE: MySQL-Insert Syntax, else: Array
	 */

	public function db_get_fields($table, $exclude = '', $syntax_sort = FALSE){
		if($exclude == ''){
			$exclude = array();
		}

		$fields = array();
		$result = $this->db_q("SELECT * FROM  `$table`");
		if (!$result){
	  asys_db_err("Error while read database (asys_mysql_get_fields) " . mysql_error(), TRUE);
		}
		$fields_num = mysql_num_fields($result);
		for($i=0; $i<$fields_num; $i++)
		{
			$field = mysql_fetch_field($result);
			//
			if(!in_array($field->name, $exclude)){
				$fields[] = $field->name;
			}
		}

		if ($syntax_sort){
			$c_fields = count($fields);
			for($i=0; $i<$c_fields; $i++)
			{
				if($i == $c_fields -1){
					$fields_mysql .= '`' . $fields[$i] . '`';
				}else{
					$fields_mysql .= '`' . $fields[$i] . '`, ';
				}

			}
			return $fields_mysql;
		}else{
			return $fields;
		}

	}

	/*
	 * Asys-mysql insert function.
	 * $table; the name of the table
	 * $values; the values to insert.
	 * $fields; the fields of the table
	 */

	public function db_insert($table, $values, $fields = 'get_fields', $exclude = 'default'){
		 
		if($fields == 'get_fields'){
			if($exclude == 'default'){
				$exclude = array('ID', 'id');
			}
			$fields = $this->db_get_fields($table, $exclude, $syntax_sort = FALSE);
		}
		foreach($values as $value){
			$values_mysql[] = '\'' . $value . '\'';
		}
		$values_mysql = implode(', ', $values_mysql);

		foreach($fields as $value){
			$fields_mysql[] = '`' . $value . '`';
		}
		$fields_mysql = implode(', ', $fields_mysql);
		$result = $this->db_q("INSERT INTO `$table` ($fields_mysql) VALUES ($values_mysql);");
		if (!$result){
	  asys_db_err("MySQL: Error while insert record (asys_mysql_insert) " . mysql_error(), TRUE);
		}
		return mysql_insert_id();
	}

	/*
	 * Asys-mysql get select content
	 * get a single row of the db (where or first)
	 * $table; the name of the table
	 * $values; values to insert
	 * $fields; the selected fields (e.g. asys_mysql_get_fields)
	 * $where; WHERE
	 */
	public function db_update($table, $values, $where = '', $fields = 'get_fields'){

		if($fields == 'get_fields'){
			$fields = $this->db_get_fields($table);
		}

		$c_fields = count($fields);
		$sql = '';
		for($i=0; $i<$c_fields; $i++)
		{
			if($i == $c_fields -1){
				$sql .= '`' . $fields[$i] . '`' . ' = ' . '\'' . $values[$i] . '\'';
			}else{
				$sql .= '`' . $fields[$i] . '`' . ' = ' . '\'' . $values[$i] . '\'' . ', ';
			}
		}
		$result = $this->db_q("UPDATE `$table` SET $sql $where;");
		if (!$result){
	  asys_db_err("Error while update record (asys_mysql_update) " . mysql_error() . $sql, TRUE);
		}
	}

	/* Asys mysql delete
	 * $table; the name of the table
	 * $where; MySQL WHERE
	 */

	public function db_delete($table, $where){
		$result = $this->db_q("DELETE FROM `$table` $where");
		if (!$result){
	  asys_db_err("Error while delete record (asys_mysql_delete) " . mysql_error(), TRUE);
		}
	}

	/*
	 * Asys-mysql get select content
	 * get a single row of the db (where or first)
	 * $table; the name of the table
	 * $fields; the selected fields (e.g. asys_mysql_get_fields)
	 * $where; WHERE
	 */
	public function db_select_content($table, $where = '', $fields = 'get_fields'){
		$content_array = array();
		if($fields == 'get_fields'){
			$fields = $this->db_get_fields($table);
		}
		$fields_arr = $fields;
		$c_fields = count($fields_arr);
		$result = $this->db_q("SELECT * FROM `$table` $where LIMIT 1");
		if (!$result){
	  asys_db_err("Error while fetch record (asys_mysql_select_content) " . mysql_error(), TRUE);
		}
		$row = $this->db_fetch_assoc($result);
		for($i=0; $i<$c_fields; $i++){
			$content_array[$fields_arr[$i]] = $row[$fields_arr[$i]];
		}
		if($content_array[$fields_arr[0]] == ''){
			return FALSE;
		}else{
			return $content_array;
		}
	}

	/*
	 * Asys-mysql get select content
	 * get all rows of a database
	 * $table; the name of the table
	 * $fields; the selected fields (e.g. asys_mysql_get_fields) - array
	 * $where; WHERE
	 * return: array of database
	 */

	public function db_select_table($table, $noid = false, $fields = 'get_fields', $where = '', $limit = 500, $limit2 = 0){
		if($fields == 'get_fields'){
			if($noid){
				$fields = $this->db_get_fields($table, $exclude = array('ID', 'id'));
			}else{
				$fields = $this->db_get_fields($table);
			}
		}
		foreach($fields as $value){
			$fields_mysql[] = '`' . $value . '`';
		}
	 $fields_mysql = implode(', ', $fields_mysql);
	 
	 $result = $this->db_q("SELECT $fields_mysql FROM `$table` $where LIMIT $limit2, $limit");
	 $row_int = 0;
	 $mysql_table = array();
	 while($row = mysql_fetch_assoc($result))
	 {
	 	for($i=0; $i<count($fields); $i++){
	 		$mysql_table[$row_int][$fields[$i]] = $row[$fields[$i]];
	 	}
	 	$row_int++;
	 }
	 return $mysql_table;
	}

	/*
	 * Asys-mysql get select content
	 * get all rows of a database
	 * $table; the name of the table
	 * $fields; the selected fields (e.g. asys_mysql_get_fields) - array
	 * $where; WHERE
	 * return: array of database
	 */

	public function db_select_rows($table, $where = '', $noid = false, $fields = true , $limit = ''){
		if($fields){
			if($noid){
				$fields = $this->db_get_fields($table, $exclude = array('ID', 'id'));
			}else{
				$fields = $this->db_get_fields($table);
			}
		}
		foreach($fields as $value){
			$fields_mysql[] = '`' . $value . '`';
		}
	 $fields_mysql = implode(', ', $fields_mysql);
	 
	 $result = $this->db_q("SELECT $fields_mysql FROM `$table` $where $limit");
	 $row_int = 0;
	 $mysql_table = array();
	 while($row = mysql_fetch_assoc($result))
	 {
	 	for($i=0; $i<count($fields); $i++){
	 		$mysql_table[$row_int][$fields[$i]] = $row[$fields[$i]];
	 	}
	 	$row_int++;
	 }
	 return $mysql_table;
	}
}
?>