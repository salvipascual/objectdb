<?php

/**
 * ObjectDB Microsoft SQL Connection
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY
 * or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License
 * for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program as the file LICENSE.txt; if not, please see
 * http://www.gnu.org/licenses/old-licenses/gpl-2.0.txt.
 *
 * @author Salvi Pascual <salvi.pascual@pragres.com>
 * @version 4.1
 * @link http://objectdb.pragres.com
 */

class ODBConnectionMSSQL implements IODBConnection {

	var $connect = null;

	/**
	 * Constructor
	 * 
	 * @exception ODBENotConnected - Raised if are problem with connection
	 * @return ODBConnectionMSSQL
	 */
	public function __construct() {

		// connect to database

		$this->connect = @mssql_connect(ODBConnection::$host, ODBConnection::$user, ODBConnection::$pass);
		
		if (!$this->connect) throw new ODBENotConnected();

		mssql_select_db(ODBConnection::$dbname, $this->connect);

		// create the cache of this db tables

		$this->createCacheTables();

		return $this->connect;
	}

	/**
	 * Executs a query, if is a SELECT, returns a list of objects
	 *
	 * @param string $sql - A valid SQL sentence
	 * @param boolean $native - Turn on/off to return a list of ODBObject or native PHP function's results
	 * @param boolean $asObjects - Return a list of objects or a list of arrays
	 * @param string $tbName - optional Table name
	 * @exception ODBEWrongSQLSentence - Raised if SQL sentence is Wrong
	 * @return array
	 */
	public function query($sql, $native = false, $asObjects = false, $tbName = "") {

		$result = mssql_query($sql, $this->connect);

		if (!$result){
			throw new ODBEWrongSQLSentence();
		}

		if ($result != 1) {
			if ($native === true) {
				$arr = array();
				if ($asObjects === true)
				while ($obj = mssql_fetch_object($result))
				$arr[] = $obj;
				else
				while ($row = mssql_fetch_array($result))
				$arr[] = $row;
				return $arr;
			}
		}

		$listOfObjs = null;
		if ($result != 1) {
			while ($tbElements = mssql_fetch_array($result, MYSQL_ASSOC)) {
				if (class_exists($tbName)) {
					$object = new $tbName();
					$tableKey = $object->getTableKeyName(); // obtain key name
					$object->setId($tbElements[$tableKey]); // add key for new object
					unset($tbElements[$tableKey]); // delete key if exist
					$object->setDataFromArray($tbElements);
					$object->setSaveStatus();
					$listOfObjs[] = $object;
				} else {
					$listOfObjs[] = $tbElements;
				}
			}
		}
		return $listOfObjs;
	}

	/**
	 * Create a chache of tables
	 *
	 */
	public function createCacheTables() {
		ODBConnection::$tables = array();

		$r = $this->query("SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE = \"BASE TABLE\";", true);

		foreach($r as $table){
			ODBConnection::$tables[] =  $table['TABLE_NAME'];
		}
	}

	/**
	 * Return the list of fields
	 *
	 * @param string $table - The name of the table
	 */
	public function getFieldsOf($table) {
		$result = $this->query("SELECT column_name FROM information_schema.columns WHERE table_name = \"$table\";");
		return $result;
	}

	/**
	 * Return TRUE if field exists
	 *
	 * @param string $table - The name of the table
	 * @param string $field - The name of the field
	 * @param array $fl - The list of fields loaded previusly
	 */
	public function existsField($table, $field, $fl = null) {
		if ($fl == null) $fl = $this->getFieldsOf($table);
		foreach ($fl as $f)	if ($f["Field"] == $field)	return true;
		return false;
	}

	/**
	 * Close the connection
	 *
	 */
	public function close(){
		mssql_close($this->connect);
	}
}

// End of file