<?php

class ODBConnectionMSSQL implements IODBConnection {
	var $connect = null;
	/*
	 name: ODBConnection
	 overview: start a connection when object create
	 params:
	 none
	 returns:
	 Integer: a conection to the database
	 exceptions:
	 ODBENotConnected: Raised if are problem with connection
	 */

	public function __construct() {
		// connect to database
		$this->connect = @mssql_connect(ODBConnection::$host, ODBConnection::$user, ODBConnection::$pass);
		if (!$this->connect)
		throw new ODBENotConnected();

		mssql_select_db(ODBConnection::$dbname, $this->connect);

		// create the cache of this db tables
		$this->createCacheTables();

		return $this->connect;
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

	/*
	 Create a chache of tables
	 */

	public function createCacheTables() {
		ODBConnection::$tables = array();
		
		$r = $this->query("SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE = \"BASE TABLE\";", true);

		foreach($r as $table){
			ODBConnection::$tables[] =  $table['TABLE_NAME'];
		}
	}

	/*
	 Obtain list of fields of specific table
	 */

	public function getFieldsOf($table) {
		$result = $this->query("SELECT column_name FROM information_schema.columns WHERE table_name = \"$table\";");
		return $result;
	}

	/*
	 Return TRUE if exists a field in table
	 */

	public function existsField($table, $field, $fl = null) {
		if ($fl == null)
		$fl = $this->getFieldsOf($table);
		foreach ($fl as $f)
		if ($f["Field"] == $field)
		return true;
		return false;
	}

	public function close(){
		mssql_close($this->connect);
	}
}
?>