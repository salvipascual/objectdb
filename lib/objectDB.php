<?php

/**
 * ObjectDB Global Class
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

abstract class objectDB {

	static $connection = null;
	static $url = null;
	static $version = '4.1';

	/**
	 * Establish connection
	 *
	 * @param string $url
	 * @return ODBConnection
	 */
	static function connect($url = null) {
		if ($url == null) $url = self::$url;
		if ($url == null) throw new ODBENoURLSupliedForConnect();
		self::$url = $url;

		$url = parse_url($url);

		if (isset($url['scheme']))	ODBConnection::$dbtype = $url['scheme'];
		if (isset($url['user'])) 	ODBConnection::$user = $url['user'];
		if (isset($url['pass']))	ODBConnection::$pass = $url['pass'];
		if (isset($url['host']))	ODBConnection::$host = $url['host'];
		if (isset($url['path'])) 	ODBConnection::$dbname = substr($url['path'], 1);
		if (isset($url['port'])) 	ODBConnection::$port = $url['port'];

		self::$connection = new ODBConnection();
		self::$connection->driver->createCacheTables();

		return self::$connection;
	}

	/**
	 * Return the connection
	 *
	 * @return ODBConnection
	 */
	static function getConnection() {
		if (self::$connection == null) self::connect();
		return self::$connection;
	}

	/**
	 * Execute a query, if is a SELECT, returns a list of objects
	 *
	 * @param string $sql - A valid SQL query
	 * @param boolean $native
	 * @param boolean $asObjects
	 * @param string $tbName
	 * @return array - Persistents objects obtains from database or null
	 * @exception ODBEWrongSQLSentence - Raised if the SQL sentence are incorrect
	 */
	static function query($sql, $native = false, $asObjects = false, $tbName = null) {
		return self::getConnection()->query($sql, $native, $asObjects, $tbName);
	}

	/**
	 * Obtains object of type and filtred by a sql expresion
	 *
	 * @param string or array $tbName - Name of the table to obtains all his objets or all params in one array
	 * @param string $where - SQL expresion to filter the results (ie: id_table='24')
	 * @param string $fields
	 * @param boolean $order
	 * @param boolean $group
	 * @param integer $limit
	 * @param integer $offset
	 * @return array - List of ODBObject found
	 * @exception ODBETableNotDefined - Raised if try to use not defined table
	 * @exception ODBEWrongSQLSentence - Raised if the SQL sentence are incorrect
	 */
	static function getObjs($tbName, $where = false, $fields = "*", $order = false, $group = false, $limit = false, $offset = false) {

		if (is_array($tbName)) {
			$arr = $tbName;

			// all arguments in one array's syntax

			$tbName = isset($arr['tbName']) ? $arr['tbName'] : null;
			$fields = isset($arr['fields']) ? $arr['fields'] : "*";
			$where = isset($arr['where']) ? $arr['where'] : false;
			$order = isset($arr['order']) ? $arr['order'] : false;
			$limit = isset($arr['limit']) ? $arr['limit'] : false;
			$offset = isset($arr['offset']) ? $arr['offset'] : 0;
			$group = isset($arr['group']) ? $arr['group'] : false;
		}

		// establish a databse connection
		$db = self::getConnection();

		// verify table
		if (!ODBConnection::testTable($tbName, $db))
		throw new ODBETableNotDefined();

		// building the query
		if (ODBConnection::$dbtype == 'mssql'){
			$sql = "SELECT ".($limit === false ? "" : " TOP $limit ")." $fields FROM $tbName" .
			($where === false ? "" : " WHERE $where") .
			($order === false ? "" : " ORDER BY $order ") .
			($group === false ? "" : " GROUP BY $group ");
		} elseif (ODBConnection::$dbtype == 'mysql'){
			$sql = "SELECT $fields FROM $tbName" .
			($where === false ? "" : " WHERE $where") .
			($order === false ? "" : " ORDER BY $order ") .
			($limit === false ? "" : " LIMIT $offset, $limit ") .
			($group === false ? "" : " GROUP BY $group ");
		} else {

			$sql = "SELECT $fields FROM $tbName" .
			($where === false ? "" : " WHERE $where") .
			($order === false ? "" : " ORDER BY $order ") .
			($offset === false ? "" : " OFFSET $offset ") .
			($limit === false ? "" : " LIMIT $limit ") .
			($group === false ? "" : " GROUP BY $group ");
		}
		// load data

		$objects = $db->query($sql, false, false, $tbName);

		if (!$objects) return array();
		
		return $objects;
	}

	/**
	 * Save this object to database
	 *
	 * @param ODBObject $object - Not saved object to save in database or saved to replace
	 * @exception ODBEObjectNotExist - Raised if try to use a deleted or inexistent object
	 */
	static function saveObj($object) {
		$object->save();
	}

	/**
	 * Remove this object from database
	 *
	 * @param ODBObject $object - Object to remove
	 * @return boolean - true if object remove, exceptions in other case
	 * @exception ODBEObjectNotInDatabase - Raised if object to delete not exist in database
	 * @exception ODBEObjectNotExist - Raised if try to use a deleted or inexistent object
	 */
	static function removeObj($object) {
		return $object->remove();
	}

	/**
	 * Remove objects from a table, filter for a sql expression
	 *
	 * @param string $tbName - Name of the table to remove entrys
	 * @param string $where - SQL expresion to filter the results, blank to remove all (ie: id_table<='24')
	 * @exception ODBETableNotDefined - Raised if try to use not defined table
	 * @exception ODBEWrongSQLSentence - Raised if the SQL sentence are incorrect
	 * @return boolean
	 */
	static function remove($tbName, $where="") {
		if ($tbName == "") return false;
		
		$db = self::getConnection();
		
		if (ODBConnection::testTable($tbName, $db) === false) throw new ODBETableNotDefined();
		
		$sql = "DELETE FROM $tbName" . ($where ? " WHERE $where" : "");
		
		$db->query($sql);
		
		return true;
	}

	/**
	 * Obtain the latest object saved in a table
	 *
	 * @param string $tbName
	 * @return ODBObject - Latest saved object
	 * @exception ODBETableNotDefined - Raised if try to use not defined table
	 */
	static function getLastObject($tbName) {
		if (!ODBConnection::testTable($tbName))	throw new ODBETableNotDefined();

		return self::getConnection()->getLastObject($tbName);
	}

	/**
	 * Number of rows of specific table
	 *
	 * @param string $tbName - Name of the table
	 * @param string $where - SQL expresion to filter the results, blank to remove all (ie: id_table<='24')
	 * @return integer
	 */
	static function getLengthOf($tbName, $where = "") {
		if (!ODBConnection::testTable($tbName))
		throw new ODBETableNotDefined();

		$sql = "SELECT count(*) as c FROM $tbName" . ($where ? " WHERE $where" : "");
		$r = self::query($sql, true, false, $tbName);
		return $r[0]['c'];
	}

	/**
	 * Obtain list of fields of specific table
	 *
	 * @param string $$tbName - Name of the table
	 * @return array
	 */
	static function getFieldsOf($tbName) {
		return self::getConnection()->getFieldsOf($tbName);
	}

	/**
	 * Return TRUE if exists a field in table
	 *
	 * @param string $table
	 * @param string $field
	 * @param string $fl
	 * @return boolean
	 */
	static function existsField($table, $field, $fl = null) {
		return self::getConnection()->existsField($table, $field, $fl);
	}

	/**
	 * Drop table if exists
	 *
	 * @param string $tbName - Name of the table
	 * @return boolean
	 */
	static function dropIfExists($tbName) {
		if (!ODBConnection::testTable($tbName, self::getConnection())) return false;
		return self::drop($tbName);
	}

	/**
	 * Drop table
	 *
	 * @param string $tbName - Name of the table
	 * @exception ODBETableNotDefined - Raised if table not exists
	 * @return boolean
	 */
	static function drop($tbName) {
		self::getConnection()->driver->createCacheTables();
		if (!ODBConnection::testTable($tbName, self::getConnection())) throw new ODBETableNotDefined();

		$sql = "DROP TABLE $tbName;";
		self::query($sql, true, false, $tbName);
		self::getConnection()->driver->createCacheTables();
		
		return true;
	}
}

// End of file