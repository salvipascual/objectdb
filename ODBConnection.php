<?php

	//**********************************************
	//	connections class, please, configure connection parameters
	//**********************************************

	class ODBConnection {

		/* start - configure */
		static public $host   = "localhost";
		static public $port   = "3306";
		static public $dbname = "test";
		static public $user   = "root";
		static public $pass   = "root";
		/* end - configure */

		public $connect = null;

		static private $tables = null; // cache list of this db tables

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
		public function ODBConnection(){
			// connect to database
			$this->connect = @mysql_connect(ODBConnection::$host.":".ODBConnection::$port, ODBConnection::$user, ODBConnection::$pass,true);
			if(!$this->connect) throw new ODBENotConnected();
			mysql_select_db(ODBConnection::$dbname);

			// create the cache of this db tables
			if(!ODBConnection::$tables){
				$result = mysql_list_tables(ODBConnection::$dbname,$this->connect);
				for($i=0; $i<mysql_num_rows($result); $i++)
					ODBConnection::$tables[] = mysql_tablename($result,$i);
			}

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
		public function query($sql)
		{
			$result = mysql_query($sql,$this->connect);
			if(!$result) throw new ODBEWrongSQLSentence();

			$listOfObjs = null;
			if($result!=1){
				$tbName = mysql_field_table($result,0);
				while($tbElements = mysql_fetch_array($result, MYSQL_ASSOC)){
					$object = new $tbName();
					$tableKey = $object->getTableKeyName(); // obtain key name
					$object->setId($tbElements[$tableKey]); // add key for new object
					unset($tbElements[$tableKey]); // delete key if exist
					$object->setDataFromArray($tbElements);
					$object->setSaveStatus();
					$listOfObjs[] = $object;
				}
			}
			return $listOfObjs;
		}

		/*
		name: getTableKey
		overview: returns the table key for an ODBObject
		params: 
			String: name of the table to obtain id
		returns:
			String: name of the table key
		exceptions:
			none
		*/	
		public function getTableKey($tbName){
			return "id_" . $tbName;
		}

		/*
		name: getRelationTableName
		overview: returns the name of a relation table
		params: 
			String: name of the table
			String: name of the other table
		returns:
			String: name of the relation table or exception if no relations between
		exceptions:
			ODBETableNotDefined: Raised if relation table was not defined
		*/
		public function getRelationTableName($tableA,$tableB){
			foreach(ODBConnection::$tables as $table){
				$one = "relation_".$tableA."_".$tableB;
				$two = "relation_".$tableB."_".$tableA;
				if($one == $table) return $one;
				if($two == $table) return $two;
			}
			throw new ODBETableNotDefined("Relation between $tableA and $tableB not exist or relation table was not defined");
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
			if (!$this->testTable($tbName)) throw new ODBETableNotDefined();

			$tbId = $this->getTableKey($tbName);
			$results = $this->query("SELECT * FROM $tbName ORDER BY $tbId DESC LIMIT 1");
			return $results[0];
		}

		/*
		name: testTable
		overview: test if a table exist
		params: 
			String: table name for test
		returns:
			Boolean: true if table defined, false not defined
		exceptions:
			none
		*/
		public function testTable($tbName){
			foreach(ODBConnection::$tables as $table)
				if($tbName==$table) return true;
			return false;
		}

		/*
		name: close
		overview: finish this connection with database
		params: 
			none 
		returns: 
			none
		exceptions:
			ODBEConnectionNotActive: Raised if connection closed previously
		*/
		public function close(){
			if($this->connect) mysql_close($this->connect);
			else throw new ODBEConnectionNotActive();
		}

		/*
		name: __destruct
		overview: finish this connection when object was destroy
		params: 
			none 
		returns: 
			none
		exceptions:
			ODBEConnectionNotActive: Raised if connection closed previously
		*/	
		public function __destruct(){
			$this->close();
		}
	}

?>