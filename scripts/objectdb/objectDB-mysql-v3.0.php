<?php

	require_once "ODBException.php";
	require_once "ODBConnection.php";
	require_once "ODBObject.php";

	//**********************************************
	//	base class, don't create multiple instances
	//**********************************************

	class objectDB{

		static private $singletton = false;

		/*
		name: ODBCore
		overview: create a new controller object
		params: 
			none
		returns: 
			none
		exceptions:
			ODBEMoreThanOneInstance: Raised if try to instanciate this class more than one time
		*/
		public function objectDB(){
			if(objectDB::$singletton) throw new ODBEMoreThanOneInstance();
			objectDB::$singletton = true;
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
		public function query($sql){
			$db = new ODBConnection();
			return $db->query($sql);
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
		public function getObjs($tbName,$where=""){
			$db = new ODBConnection();
			if (!$db->testTable($tbName,$db)) throw new ODBETableNotDefined();

			$sql = "SELECT * FROM $tbName" . ($where ? " WHERE $where" : "");
			$objects = $db->query($sql);
			if(!$objects) return Array();
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
		public function saveObj($object){
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
		public function removeObj($object){
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
		public function remove($tbName,$where=""){
			$db = new ODBConnection();
			if (!$db->testTable($tbName,$db)) throw new ODBETableNotDefined();

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
		public function getLastObject($tbName){
			$db = new ODBConnection();
			if (!$db->testTable($tbName,$db)) throw new ODBETableNotDefined();

			return $db->getLastObject($tbName);
		}
	}
?>