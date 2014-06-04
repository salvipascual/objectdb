<?php

/**
 * @name objectDB base class
 * Static class
 */
abstract class objectDB {

	static $connection = null;
	static $url = null;

	/**
	 * connect
	 * @param <type> $url
	 */
	static function connect($url = null) {
		if ($url == null)
		$url = self::$url;
		if ($url == null)
		throw new ODBENoURLSupliedForConnect();
		self::$url = $url;

		$url = parse_url($url);

		if (isset($url['scheme']))
		ODBConnection::$dbtype = $url['scheme'];

		if (isset($url['user']))
		ODBConnection::$user = $url['user'];

		if (isset($url['pass']))
		ODBConnection::$pass = $url['pass'];

		if (isset($url['host']))
		ODBConnection::$host = $url['host'];

		if (isset($url['path']))
		ODBConnection::$dbname = substr($url['path'], 1);

		if (isset($url['port']))
		ODBConnection::$port = $url['port'];

		self::$connection = new ODBConnection();

		self::$connection->driver->createCacheTables();

		return self::$connection;
	}

	/**
	 *   Return connection
	 *   Singleton
	 */
	static function getConnection() {
		if (self::$connection == null)
		self::connect();
		return self::$connection;
	}

	/*
	 name: query
	 overview: executs a query, if is a SELECT, returns a list of objects
	 params:
	 String: a valid SQL query
	 returns:
	 Array: Persistents objects obtains from database or null
	 exceptions:
	 ODBEWrongSQLSentence: Raised if the SQL sentence are incorrect
	 */

	static function query($sql, $native = false, $asObjects = false, $tbName = null) {
		return self::getConnection()->query($sql, $native, $asObjects, $tbName);
	}

	/*
	 name: getObjs
	 overview: obtains object of type and filtred by a sql expresion
	 params:
	 String: name of the table to obtains all his objets
	 String: sql expresion to filter the results (ie: id_table='24')
	 returns:
	 Array: list of ODBObject found
	 exceptions:
	 ODBETableNotDefined: Raised if try to use not defined table
	 ODBEWrongSQLSentence: Raised if the SQL sentence are incorrect
	 */

	static function getObjs($tbName, $where = false, $fields = "*", $order = false, $group = false, $limit = false, $offset = false) {

		if (is_array($tbName)) {
			$arr = $tbName;

			//all arguments in one array' syntax

			$tbName = isset($arr['tbName']) ? $arr['tbName'] : null;
			$fields = isset($arr['fields']) ? $arr['fields'] : "*";
			$where = isset($arr['where']) ? $arr['where'] : false;
			$order = isset($arr['order']) ? $arr['order'] : false;
			$limit = isset($arr['limit']) ? $arr['limit'] : false;
			$offset = isset($arr['offset']) ? $arr['offset'] : false;
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
		} else{

			$sql = "SELECT $fields FROM $tbName" .
			($where === false ? "" : " WHERE $where") .
			($order === false ? "" : " ORDER BY $order ") .
			($offset === false ? "" : " OFFSET $offset ") .
			($limit === false ? "" : " LIMIT $limit ") .
			($group === false ? "" : " GROUP BY $group ");
		}
		// load data

		$objects = $db->query($sql, false, false, $tbName);

		if (!$objects)
		return array();

		return $objects;
	}

	/*
	 name: saveObj
	 overview: save this object to database
	 params:
	 ODBObject: not saved object to save in database or saved to replace
	 returns:
	 none
	 exceptions:
	 ODBEObjectNotExist: Raised if try to use a deleted or inexistent object
	 */

	static function saveObj($object) {
		$object->save();
	}

	/*
	 name: removeObj
	 overview: remove this object from database
	 params:
	 ODBObject: object to remove
	 returns:
	 Boolean: true if object remove, exceptions in other case
	 exceptions:
	 ODBEObjectNotInDatabase: Raised if object to delete not exist in database
	 ODBEObjectNotExist: Raised if try to use a deleted or inexistent object
	 */

	static function removeObj($object) {
		return $object->remove();
	}

	/*
	 name: remove
	 overview: remove objects from a table, filter for a sql expression
	 params:
	 String: name of the table to remove entrys
	 String: sql expresion to filter the results, blank to remove all (ie: id_table<='24')
	 returns:
	 none
	 exceptions:
	 ODBETableNotDefined: Raised if try to use not defined table
	 ODBEWrongSQLSentence: Raised if the SQL sentence are incorrect
	 */

	static function remove($tbName, $where="") {
		$db = self::getConnection();
		if (ODBConnection::testTable($tbName, $db) === false)
		throw new ODBETableNotDefined();

		$sql = "DELETE FROM $tbName" . ($where ? " WHERE $where" : "");
		$db->query($sql);
	}

	/*
	 name: getLastObject
	 overview: obtain the latest object saved in a table
	 params:
	 String: table name for obtain latest saved object
	 returns:
	 Object: Latest saved object
	 exceptions:
	 ODBETableNotDefined: Raised if try to use not defined table
	 */

	static function getLastObject($tbName) {
		if (!ODBConnection::testTable($tbName))
		throw new ODBETableNotDefined();

		return self::getConnection()->getLastObject($tbName);
	}

	/*
	 * getLengthOf
	 * return: Number of rows of specific table
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
	 */
	static function getFieldsOf($table) {
		return self::getConnection()->getFieldsOf($table);
	}

	/**
	 * Return TRUE if exists a field in table
	 */
	static function existsField($table, $field, $fl = null) {
		return self::getConnection()->existsField($table, $field, $fl);
	}

	/**
	 * drop
	 * @param <type> $tbName
	 * @return <type>
	 */
	static function dropIfExists($tbName) {
		if (!ODBConnection::testTable($tbName, self::getConnection()))
		return false;
		return self::drop($tbName);
	}

	/**
	 * drop
	 * @param <type> $tbName
	 * @return <type>
	 */
	static function drop($tbName) {
		self::getConnection()->driver->createCacheTables();
		if (!ODBConnection::testTable($tbName, self::getConnection()))
		throw new ODBETableNotDefined();

		$sql = "DROP TABLE $tbName;";
		self::query($sql, true, false, $tbName);
		self::getConnection()->driver->createCacheTables();
		return true;
	}

}
?>