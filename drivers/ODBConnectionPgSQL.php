<?php

/**
 * ObjectDB PostgreSQL Connection
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

class ODBConnectionPgSQL implements IODBConnection {

	public $connect = null;
	
	/**
	 * Constructor
	 *
	 * @exception ODBENotConnected - Raised if are problem with connection
	 * @return ODBConnectionMSSQL
	 */


	public function __construct() {
		// connect to database
		$this->connect = @pg_connect("host = " . ODBConnection::$host . " port = " . ODBConnection::$port . " user = " . ODBConnection::$user . " password = " . ODBConnection::$pass . " dbname = " . ODBConnection::$dbname);
		if (!$this->connect)
		throw new ODBENotConnected();

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
	public function query($sql, $native = false, $asObjects = false, $tbName = null) {

		$result = pg_query($this->connect, $sql);

		if (!$result)
		throw new ODBEWrongSQLSentence();

		if ($native === true) {
			$arr = array();
			if ($asObjects === true)
			while ($obj = pg_fetch_object($result))
			$arr[] = $obj;
			else
			$arr = pg_fetch_all($result);
			return $arr;
		}

		$listOfObjs = null;
		if ($result != 1) {
			if (pg_num_fields($result) > 0) {
				$tbName = pg_field_table($result, 0);
				while ($tbElement = pg_fetch_assoc($result)) {
					if (class_exists($tbName)) {
						$object = new $tbName();
						$tableKey = $object->getTableKeyName(); // obtain key name
						if (isset($tbElement[$tableKey])) {
							$object->setId($tbElement[$tableKey]); // add key for new object

							if (is_numeric($tbElement[$tableKey])) $object->setIdType(ODB_IDTYPE_AUTOINCREMENT);
							elseif (is_string($tbElement[$tableKey])) $object->setIdType(ODB_IDTYPE_UNIQUEID);

							unset($tbElement[$tableKey]); // delete key if exist
						}
						$object->setDataFromArray($tbElement);
						$object->setSaveStatus();
						$listOfObjs[] = $object;
					} else $listOfObjs[] = $tbElement;
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
		$tables = @pg_fetch_all(pg_query($this->connect, "select * from information_schema.tables where table_type='BASE TABLE' and table_schema<>'pg_catalog' AND table_schema<>'information_schema' order by table_schema"));
		if (is_array($tables))
		foreach ($tables as $table) {
			if ($table['table_schema'] != 'public')
			ODBConnection::$tables[] = $table['table_schema'] . "_" . $table['table_name'];
			else
			ODBConnection::$tables[] = $table['table_name'];
		}
	}

	/**
	 * Return the list of fields
	 *
	 * @param string $table - The name of the table
	 */
	public function getFieldsOf($table) {
		$arr = explode(".", $table);
		if (count($arr) == 2) {
			$table_schema = $arr[0];
			$table_name = $arr[1];
		} else {
			$table_schema = "public";
			$table_name = $table;
		}
		return @pg_fetch_all(pg_query($this->connect, "select * from information_schema.columns where table_schema='$table_schema' and table_name='$table_name';"));
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

		$fl = $this->getFieldsOf($table);

		if (is_array($fl)) foreach ($fl as $f) if ($f['column_name'] == $field) return true;

		return false;
	}

	/**
	 * Close the connection
	 *
	 */
	public function close(){
		pg_close($this->connect);
	}
}

// End of file