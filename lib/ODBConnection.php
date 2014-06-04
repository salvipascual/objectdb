<?php

/**
 * ObjectDB Connection Class Parent
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
 * @website http://objectdb.salvipascual.com
 */

class ODBConnection {

	static public $dbtype = null;
	static public $host = null;
	static public $port = null;
	static public $dbname = null;
	static public $user = null;
	static public $pass = null;
	static public $tables = null; /* cache list of tables */
	public $driver = null;

	/**
	 * Constructor
	 *
	 * @return ODBConnection
	 */
	public function __construct() {
		switch (ODBConnection::$dbtype) {
			case "mysql":
				$this->driver = new ODBConnectionMySQL();
				break;
			case "pgsql":
				$this->driver = new ODBConnectionPgSQL();
				break;
			case "mssql":
				$this->driver = new ODBConnectionMSSQL();
				break;
		}
		return $this->driver->connect;
	}

	/**
	 * Execute a SQL query
	 *
	 * @param string $sql - A valid SQL sentence
	 * @param boolean $native - Turn on/off to return a list of ODBObject or native PHP function's results
	 * @param boolean $asObjects - Return a list of objects or a list of arrays
	 * @param string $tbName
	 * @return array
	 */
	public function query($sql, $native = false, $asObjects = false, $tbName = null) {
		return $this->driver->query($sql, $native, $asObjects, $tbName);
	}

	/**
	 * Return TRUE if field exists
	 *
	 * @param string $table - The name of the table
	 * @param string $field - The name of the field
	 * @param array $fl - The list of fields loaded previusly
	 */
	public function existsField($table, $field, $fl = null) {
		return $this->driver->existsField($table, $field, $fl);
	}

	/**
	 * Return the list of fields
	 *
	 * @param string $table - The name of the table
	 * @return array
	 */
	public function getFieldsOf($table) {
		return $this->driver->getFieldsOf($table);
	}

	/**
	 * Returns the table key for an ODBObject
	 *
	 * @param string $tbName - Name of the table
	 * @return string - Name of the table key
	 */
	public function getTableKey($tbName) {
		return "id_" . $tbName;
	}

	/**
	 * Returns the name of a relation table
	 *
	 * @param string $tableA - Name of the table
	 * @param string $tableB - Name of the other table
	 * @exception ODBETableNotDefined - Raised if relation table was not defined
	 * @return string - Name of the relation table or exception if no relations between
	 */
	public function getRelationTableName($tableA, $tableB) {
		foreach (ODBConnection::$tables as $table) {
			$one = "relation_" . $tableA . "_" . $tableB;
			$two = "relation_" . $tableB . "_" . $tableA;
			if ($one == $table)	return $one;
			if ($two == $table)	return $two;
		}
		throw new ODBETableNotDefined("Relation between $tableA and $tableB not exist or relation table was not defined");
	}

	/**
	 * Obtain the latest object saved in a table
	 *
	 * @param string $tbName - Table name for obtain latest saved object
	 * @return ODBObject - Latest saved object
	 */
	public function getLastObject($tbName) {
		if (!ODBConnection::testTable($tbName))	throw new ODBETableNotDefined();

		$tbId = $this->getTableKey($tbName);

		if (ODBConnection::$dbtype == 'mssql')
		$results = $this->query("SELECT TOP 1 * FROM $tbName ORDER BY $tbId DESC ", false, false, $tbName);
		else
		$results = $this->query("SELECT * FROM $tbName ORDER BY $tbId DESC LIMIT 1", false, false, $tbName);

		return $results[0];
	}


	/**
	 * Test if a table exists
	 *
	 * @param string $tbName - Name of the table
	 * @return boolean
	 */
	static function testTable($tbName) {
		if (is_array(ODBConnection::$tables))
		foreach (ODBConnection::$tables as $table)
		if ($tbName == $table){
			return true;
		}
		return false;
	}
	
	/**
	 * Finish this connection with database
	 *
	 * @exception ODBEConnectionNotActive - Raised if connection closed previously
	 */
	public function close() {
		if ($this->driver->connect) {
			if (is_resource($this->driver->connect))
			$this->driver->close();
		}
		else
		throw new ODBEConnectionNotActive();
	}

	/**
	 * Destructor, finish this connection when object was destroy
	 * 
	 * @exception ODBEConnectionNotActive - Raised if connection closed previously
	 */
	public function __destruct() {
		$this->close();
	}
}

// End of file