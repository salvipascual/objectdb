<?php

//**********************************************
//	constants values
//**********************************************

define("OBJECT_SAVED", 0x01, true);  // define an ODBObject saved into database
define("OBJECT_NOT_SAVED", 0x02, true); // define an ODBObject not in database
define("OBJECT_DELETED", 0x03, true);
define("ODB_DATAFIELD_UNKNOWN", 0x04, true);
define("ODB_IDTYPE_AUTOINCREMENT", 0x05, true);
define("ODB_IDTYPE_UNIQUEID", 0x06, true);
define("ODB_TYPE_STRING", "string", true);
define("ODB_TYPE_NUMERIC", 9999, true);
define("ODB_TYPE_BOOLEAN", false, true);

// define an unknown data field ("null" are not equal that "unknown")
// define an ODBObject deleted with remove() function

abstract class ODBObject {

	private $state = OBJECT_NOT_SAVED;
	private $id = null;

	/*
	 Constructor
	 */

	public function __construct($params = array(), $save = true) {
		if (count($params) === 0)
		$save = false;

		foreach ($params as $key => $value) {
			$this->$key = $value;
		}

		if ($save == true)
		$this->save();
	}

	/**
	 * getSQLIdValue
	 * Return a sql query part for specify a id correctly
	 */
	private function getSQLIdValue() {
		if (isset($this->id_type))
		switch ($this->id_type) {
			case ODB_IDTYPE_AUTOINCREMENT:
				return $this->id;
				break;
			case ODB_IDTYPE_UNIQUEID:
				return "'{$this->id}'";
				break;
		}
		return $this->id;
	}

	/**
	 * setIdType
	 * Establish the id type
	 */
	public function setIdType($type) {
		$this->id_type = $type;
	}

	/**
	 * setIdType
	 * Establish the id type
	 */
	public function getIdType() {
		return $this->id_type;
	}

	/*
	 name: setId
	 overview: sets the table key for this row, but only for not saved object
	 params:
	 String: table key
	 returns:
	 none
	 exceptions:
	 ODBECannotChangeKey: Raised if try to change the key for one row
	 */

	public function setId($id = null) {
		if ($this->state == OBJECT_SAVED)
		throw new ODBECannotChangeKey();
		$this->id = $id;
	}

	/*
	 name: setSaveStatus
	 overview: mark as saved; one saved object cannot be mark as unsaved again
	 params:
	 none
	 returns:
	 none
	 exceptions:
	 none
	 */

	public function setSaveStatus() {
		$this->state = OBJECT_SAVED;
	}

	/*
	 name: setDataFromArray
	 overview: fill this object with an Array of information
	 params:
	 Array; object values, order as in database
	 returns:
	 none
	 exceptions:
	 ODBEObjectNotExist: Raised if try to use a deleted or inexistent object
	 */

	public function setDataFromArray($data) {
		if ($this->getState() == OBJECT_DELETED)
		throw new ODBEObjectNotExist();

		$public_prop = $this->getPublicProperties();
		foreach ($public_prop as $key => $value) {
			if (isset($data[$key]) || isset($this->$key)) {
				$element = each($data); // for make it work with ASSOCIATIVE arrays
				$this->$key = $element['value'];
			} else {
				$this->$key = ODB_DATAFIELD_UNKNOWN;
			}
		}
	}

	/*
	 name: setDataFromParamsList
	 overview: fill this object with a list of parameters
	 params:
	 Params; object values, order as in database
	 returns:
	 none
	 exceptions:
	 ODBEObjectNotExist: Raised if try to use a deleted or inexistent object
	 */

	public function setDataFromParamsList($params) {

		if ($this->getState() == OBJECT_DELETED)
		throw new ODBEObjectNotExist();

		$i = 0;
		$args = func_get_args();
		foreach ($this->getPublicProperties() as $key => $value) {
			$this->$key = $args[$i++];
		}
	}

	/*
	 name: getId
	 overview: obtains the table key for this row
	 params:
	 none
	 returns:
	 String: the table id for this row
	 exceptions:
	 none
	 */

	public function getId() {
		return $this->id;
	}

	/*
	 name: getState
	 overview: obtains the object state
	 params:
	 none
	 returns:
	 Constants: object state -> OBJECT_SAVED, OBJECT_NOT_SAVED, OBJECT_DELETED
	 exceptions:
	 none
	 */

	public function getState() {
		return $this->state;
	}

	/*
	 name: getTableName
	 overview: obtains the table name for this object
	 params:
	 none
	 returns:
	 String: the table name for this row
	 exceptions:
	 none
	 */

	public function getTableName() {
		return get_class($this);
	}

	/*
	 name: getTableKeyName
	 overview: returns the table key for this object
	 params:
	 none
	 returns:
	 String: name of the table key
	 exceptions:
	 none
	 */

	public function getTableKeyName() {
		return "id_" . $this->getTableName();
		//	Al mejorar este algoritmo, descomentariar esto y eliminar arriba
		//	$db = new ODBConnection();
		//	return $db->getTableKey($this->getTableName());
	}

	/*
	 name: getPublicProperties
	 overview: get the user entries properties for this clase
	 params:
	 none
	 returns:
	 Array; name of properties entry by user
	 exceptions:
	 none
	 */

	public function getPublicProperties() {
		$getFields = create_function('$obj', 'return get_object_vars($obj);');
		$arr = $getFields($this);

		foreach ($arr as $key => $item)
		if ($key != 'id_type')
		$narr[$key] = $item;

		return $narr;
	}

	public function haveId() {
		$classname = get_class($this);
		$wi = true;
		eval('if (isset('.$classname.'::$without_id)) $wi = '.$classname.'::$without_id;');
		return $wi;
	}

	/*
	 name: isSaved
	 overview: specify if object is in database
	 params:
	 none
	 returns:
	 Boolean: true if object is in database, false in other case
	 exceptions:
	 none
	 */

	public function isSaved() {
		return ($this->state == OBJECT_SAVED) ? true : false;
	}

	/*
	 name: save
	 overview: save this object to database
	 params:
	 none
	 returns:
	 none
	 exceptions:
	 ODBEObjectNotExist: Raised if try to use a deleted or inexistent object
	 */

	public function save() {

		if ($this->getState() == OBJECT_DELETED)
		throw new ODBEObjectNotExist();

		$public_properties = $this->getPublicProperties();

		if ($this->isSaved()) { // update object
			$sql = "UPDATE " . $this->getTableName() . " SET ";
			$i = 1;
			foreach ($public_properties as $key => $value)
				if (is_numeric(trim($value)))
					$sql .= ( ($i++ > 1) ? "," : "") . "$key = $value ";
				elseif (is_bool($value) || $value ==="false" || $value ==="true"){
					if (ODBConnection::$dbtype == 'mssql')
						$sql .= $value == true ? "$key = 't'" : "$key = 'f'";
					else
					if (ODBConnection::$dbtype == 'mysql')
						$sql .= (($i++>1) ?",":"").($value === true || "$value" ==="true" || $value === 1 || "$value" === "1"? "$key = 1 ":"$key = 0");
					else
						$sql .= $value == true || $value === "true"? "$key = true " : "$key = false";
				} else {
					if (ODBConnection::$dbtype == 'mssql')
						$sql .= ( ($i++ > 1) ? "," : "") . "$key = \"$value\" ";
					else
						$sql .= ( ($i++ > 1) ? "," : "") . "$key = '$value' ";
				}
					

			$sql .= " WHERE {$this->getTableKeyName()} = {$this->getId()} ";
		} else {
			// create a new object
			$sql = "INSERT INTO " . $this->getTableName() . " (";

			$withid = $this->haveId();

			if ( $withid === true)
			if (isset($this->id_type))
			if ($this->id_type === ODB_IDTYPE_UNIQUEID)
			$sql .= $this->getTableKeyName() . ', ';


			$first = true;
			foreach ($public_properties as $key => $value) {
				if ($first === false)
				$sql.=",";
				$sql .= $key;
				$first = false;
			}
			$sql.= ") VALUES ( ";

			if ($withid === true)
			if (isset($this->id_type))
			if ($this->id_type === ODB_IDTYPE_UNIQUEID) {
				$idgen = uniqid(date("ymdhis"), true);
				$sql .= "'$idgen', ";
			}

			$first = true;
			foreach ($public_properties as $key => $value) {
				if ($first === false)
				$sql.=",";
				if (is_numeric(trim($value)))
					$sql .= "$value";
				elseif (is_bool($value)){
					if (ODBConnection::$dbtype == 'mssql')
						$sql .= $value == true ? "'t'" : "'f'";
					else
						$sql .= $value == true ? "true" : "false";
				}
				else{
					if (ODBConnection::$dbtype == 'mssql')
						$sql .= "\"$value\"";
					else
						$sql .= "'$value'";
				}
				$first = false;
			}
			$sql .= ");";
		}
		//$sql = substr_replace($sql,$where,strrpos($sql,","));

		objectDB::query($sql);

		$id_type = ODB_IDTYPE_AUTOINCREMENT;
		if (isset($this->id_type))
		$id_type = $this->id_type;

		if ($this->haveId() === true) {
			if ($id_type === ODB_IDTYPE_AUTOINCREMENT)
				if (!$this->id) { // for object just saved with no id
					$lastObj = objectDB::getLastObject($this->getTableName());
					if ($lastObj != null)
						$this->id = $lastObj->getId();
				}
		}
		if (!$this->isSaved())
		$this->setSaveStatus(); // for all object just saved





	}

	/*
	 name: remove
	 overview: remove this object from database
	 params:
	 none
	 returns:
	 Boolean: true if object remove, exceptions in other case
	 exceptions:
	 ODBEObjectNotInDatabase: Raised if object to delete not exist in database
	 ODBEObjectNotExist: Raised if try to use a deleted or inexistent object
	 */

	public function remove() {
		if ($this->getState() == OBJECT_DELETED)
		throw new ODBEObjectNotExist();
		if (!$this->isSaved())
		throw new ODBEObjectNotInDatabase();

		$sql = "DELETE FROM {$this->getTableName()} WHERE {$this->getTableKeyName()} = {$this->getSQLIdValue()}";
		objectDB::query($sql);

		$this->id = null;
		$this->state = OBJECT_DELETED;
		foreach ($this as $key => $value)
		$this->$key = null;
		return true;
	}

	/*
	 name: relations
	 overview: create a new relation between two objects
	 params:
	 String: name of the table to obtain relations
	 returns:
	 Array: list of ODBObject relationed with this object
	 exceptions:
	 ODBEObjectNotInDatabase: Raised if this object not exist in database
	 ODBETableNotDefined: Raised if relation table was not defined
	 ODBEObjectNotExist: Raised if try to use a deleted or inexistent object
	 */

	public function relations($tbName) {
		if ($this->getState() == OBJECT_DELETED)
		throw new ODBEObjectNotExist();
		if (!$this->isSaved())
		throw new ODBEObjectNotInDatabase("Save this object before relation it");

		$ReltbName = objectDB::getRelationTableName($this->getTableName(), $tbName);
		$MytbName = $this->getTableName();
		$MytbNameId = $this->getTableKeyName();
		$tbNameId = objectDB::getTableKey($tbName);
		$sql = "SELECT $tbName.* FROM ($MytbName JOIN $ReltbName ON $ReltbName.$MytbNameId = $MytbName.$MytbNameId) JOIN $tbName ON $ReltbName.$tbNameId = $tbName.$tbNameId WHERE $MytbName.$MytbNameId = {$this->getSQLIdValue()}";
		return objectDB::query($sql, false, false, $tbName);
	}

	/*
	 name: addRelation
	 overview: create a new relation between two objects
	 params:
	 Object: ODBObject to relation with
	 returns:
	 none
	 exceptions:
	 ODBEObjectNotInDatabase: Raised if at least one object not exist in database
	 ODBETableNotDefined: Raised if relation table was not defined
	 ODBEObjectNotExist: Raised if try to use a deleted or inexistent object
	 */

	public function addRelation($object) {
		if ($this->getState() == OBJECT_DELETED)
		throw new ODBEObjectNotExist();
		if (!$this->isSaved())
		throw new ODBEObjectNotInDatabase("Save this object before relation it");
		if (!$object->isSaved())
		throw new ODBEObjectNotInDatabase("Save this object before create a relation with it");

		$tbName = objectDB::getRelationTableName($this->getTableName(), $object->getTableName());
		$sql = "INSERT INTO $tbName ({$this->getTableKeyName()},{$object->getTableKeyName()}) VALUES ({$this->getSQLIdValue()},{$object->getSQLIdValue()});";
		objectDB::query($sql);
	}

	/*
	 name: removeRelation
	 overview: remove a new relation between two objects
	 params:
	 Object: ODBObject to destroy relation with
	 returns:
	 none
	 exceptions:
	 ODBEObjectNotInDatabase: Raised if at least one object not exist in database
	 ODBETableNotDefined: Raised if relation table was not defined
	 ODBEObjectNotExist: Raised if try to use a deleted or inexistent object
	 */

	public function removeRelation($object) {
		if ($this->getState() == OBJECT_DELETED)
		throw new ODBEObjectNotExist();
		if (!$this->isSaved())
		throw new ODBEObjectNotInDatabase("Save this object before relation it");
		if (!$object->isSaved())
		throw new ODBEObjectNotInDatabase("Save this object before create a relation with it");

		$tbName = objectDB::getRelationTableName($this->getTableName(), $object->getTableName());
		$sql = "DELETE FROM $tbName WHERE {$this->getTableKeyName()} = {$this->getSQLIdValue()} AND {$object->getTableKeyName()} = {$object->getSQLIdValue()}";
		objectDB::query($sql);
	}

	/*
	 name: removeAllRelations
	 overview: remove a new relation between two objects
	 params:
	 String: name of the table to destroy all relation with
	 returns:
	 none
	 exceptions:
	 ODBEObjectNotInDatabase: Raised if this object not exist in database
	 ODBETableNotDefined: Raised if relation table was not defined
	 ODBEObjectNotExist: Raised if try to use a deleted or inexistent object
	 */

	public function removeAllRelations($tbName) {
		if ($this->getState() == OBJECT_DELETED)
		throw new ODBEObjectNotExist();
		if (!$this->isSaved())
		throw new ODBEObjectNotInDatabase("Save this object before relation it");

		$tbRelationName = objectDB::getRelationTableName($this->getTableName(), $tbName);
		$sql = "DELETE FROM $tbRelationName WHERE {$this->getTableKeyName()} = {$this->getSQLIdValue()};";
		objectDB::query($sql);
	}

	/*
	 name: __toString
	 overview: prints the content of this object
	 params:
	 none
	 returns:
	 none
	 exceptions:
	 ODBEObjectNotExist: Raised if try to use a deleted or inexistent object
	 */

	public function __toString() {
		if ($this->getState() == OBJECT_DELETED)
		throw new ODBEObjectNotExist();

		$header = '<th>' . $this->getTableKeyName() . '</th>';
		$body = '<td>' . $this->getId() . '</td>';

		foreach ($this->getPublicProperties() as $key => $value) {
			$header .= "<th>$key</th>";
			$body .= "<td>$value</td>";
		}
		return '<table border="1"><theader><tr>' . $header . '</tr></theader><tbody><tr>' . $body . '<tr></tbody></table>';
	}

	/**
		Create a table for storage records!
	*/

	public function install($id_type = ODB_IDTYPE_AUTOINCREMENT) {

		$db = objectDB::getConnection();
		$db->driver->createCacheTables();
		$props = $this->getPublicProperties();

		if (!ODBConnection::testTable($this->getTableName())) {
			$sql = "CREATE TABLE {$this->getTableName()} ({$this->getTableKeyName()}";

			switch ($this->getIdType()) {
				default:
					switch (ODBConnection::$dbtype) {
						case "mysql":
							$sql .= ' INT(11) KEY AUTO_INCREMENT ';
							break;
						case "pgsql":
							$sql .= ' SERIAL PRIMARY KEY ';
							break;
						case "mssql":
							$sql .= ' INT IDENTITY(1,1) PRIMARY KEY ';
							break;
					}
					break;
						case ODB_IDTYPE_UNIQUEID:
							$sql .= ' VARCHAR PRIMARY KEY ';
							break;
			}
			foreach ($props as $key => $value) {
				$type = "varchar";
				if (is_numeric($value))
					$type = "numeric";
				if (is_bool($value))
					if (ODBConnection::$dbtype == 'mssql')
						$type = "char";
					else
						$type = "bool";
				if (is_string($value))
					$type = "varchar(256)";
/*
					if (ODBConnection::$dbtype == 'mssql')
						$type = "varchar(256)";
					else
						$type = "varchar";
*/
				$sql .= ", $key $type";
			}
			$sql .= ")";
			objectDB::query($sql);
		} else {
			$sql = "";
			$fl = objectDB::getConnection()->getFieldsOf($this->getTableName());
			foreach ($props as $key => $value) {
				if (objectDB::existsField($this->getTableName(), $key, $fl) == false) {
					$type = "varchar";
					if (is_numeric($value))
						$type = "numeric";
					if (is_bool($value))
						if (ODBConnection::$dbtype == 'mssql')
							$type = "char";
						else
							$type = "bool";
					if (is_string($value))
						if (ODBConnection::$dbtype == 'mssql')
							$type = "varchar(256)";
						else
							$type = "varchar";
					$sql .= "ALTER TABLE " . $this->getTableName() . " ADD COLUMN $key $type;";
				}
			}
			if ($sql != "") {
				objectDB::query($sql);
			}
		}

		$db->driver->createCacheTables();
	}

}
?>