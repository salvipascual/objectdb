<?php

	//**********************************************
	//	constants values 
	//**********************************************

	define("OBJECT_SAVED", 0x01, true);		// define an ODBObject saved into database
	define("OBJECT_NOT_SAVED", 0x02, true);	// define an ODBObject not in database
	define("OBJECT_DELETED", 0x03, true);	// define an ODBObject deleted with remove() function

	//**********************************************
	//	native object, please, extens from it for all user
	//	persistent objects and don't instanciate this class
	//**********************************************

	class ODBObject {

		private $state = OBJECT_NOT_SAVED;
		private $id = null;

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
		public function setId($id=null){
			if($this->state == OBJECT_SAVED) throw new ODBECannotChangeKey();
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
		public function setSaveStatus(){ 
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
		public function setDataFromArray($data){
			if($this->getState() == OBJECT_DELETED) throw new ODBEObjectNotExist();

			foreach($this as $key=>$value) {
				$element = each($data); // for make it work with ASSOCIATIVE arrays
				$this->$key = $element['value'];
			}
		}

		/*
		name: setDataFromParamsList
		overview: fill this object with a list of parameters
		params: 
			Array; object values, order as in database 
		returns: 
			none
		exceptions:
			ODBEObjectNotExist: Raised if try to use a deleted or inexistent object
		*/
		public function setDataFromParamsList(){
			if($this->getState() == OBJECT_DELETED) throw new ODBEObjectNotExist();

			$i=0; $args = func_get_args();	
			foreach($this as $key=>$value){
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
		public function getId(){
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
		public function getState(){
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
		public function getTableName(){
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
		public function getTableKeyName(){
			return "id_" . $this->getTableName(); 
			//	Al mejorar este algoritmo, descomentariar esto y eliminar arriba
			//	$db = new ODBConnection();
			//	return $db->getTableKey($this->getTableName());
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
		public function isSaved(){ 
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
		public function save(){
			if($this->getState() == OBJECT_DELETED) throw new ODBEObjectNotExist();

			if($this->isSaved()) { // update object
				$sql = "UPDATE ".$this->getTableName()." SET "; 
				foreach($this as $key=>$value) $sql .= "$key='$value', ";
				$where = " WHERE ".$this->getTableKeyName()."='".$this->id."'";
			}else{ // create a new object
				$sql = "INSERT INTO ".$this->getTableName()." SET ";
				foreach($this as $key=>$value) $sql .= "$key='$value', ";
			}
			$sql = substr_replace($sql,$where,strrpos($sql,","));

			$db = new ODBConnection();
			$db->query($sql);

			if(!$this->id){ // for object just saved with no id
				$lastObj = $db->getLastObject($this->getTableName());
				$this->id = $lastObj->getId();
			}
			if(!$this->isSaved()) $this->setSaveStatus(); // for all object just saved
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
		public function remove(){
			if($this->getState() == OBJECT_DELETED) throw new ODBEObjectNotExist();
			if(!$this->isSaved()) throw new ODBEObjectNotInDatabase();

			$db = new ODBConnection();
			$sql = "DELETE FROM ".$this->getTableName()." WHERE ".$this->getTableKeyName()."='".$this->id."'";
			$db->query($sql);

			$this->id = null;
			$this->state = OBJECT_DELETED;
			foreach($this as $key=>$value) $this->$key = null;
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
		public function relations($tbName){
			if($this->getState() == OBJECT_DELETED) throw new ODBEObjectNotExist();
			if(!$this->isSaved()) throw new ODBEObjectNotInDatabase("Save this object before relation it");

			$db = new ODBConnection();
			$ReltbName = $db->getRelationTableName($this->getTableName(),$tbName);
			$MytbName = $this->getTableName();
			$MytbNameId = $this->getTableKeyName();
			$tbNameId = $db->getTableKey($tbName);
			$sql = "SELECT $tbName.* FROM ($MytbName JOIN $ReltbName ON $ReltbName.$MytbNameId = $MytbName.$MytbNameId) JOIN $tbName ON $ReltbName.$tbNameId = $tbName.$tbNameId WHERE $MytbName.$MytbNameId='".$this->id."'";
			return $db->query($sql);
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
		public function addRelation($object){
			if($this->getState() == OBJECT_DELETED) throw new ODBEObjectNotExist();
			if(!$this->isSaved()) throw new ODBEObjectNotInDatabase("Save this object before relation it");
			if(!$object->isSaved()) throw new ODBEObjectNotInDatabase("Save this object before create a relation with it");

			$db = new ODBConnection();
			$tbName = $db->getRelationTableName($this->getTableName(),$object->getTableName());
			$sql = "INSERT INTO $tbName SET ".$this->getTableKeyName()."='".$this->getId()."', ".$object->getTableKeyName()."='".$object->getId()."'";
			$db->query($sql);
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
		public function removeRelation($object){
			if($this->getState() == OBJECT_DELETED) throw new ODBEObjectNotExist();
			if(!$this->isSaved()) throw new ODBEObjectNotInDatabase("Save this object before relation it");
			if(!$object->isSaved()) throw new ODBEObjectNotInDatabase("Save this object before create a relation with it");

			$db = new ODBConnection();
			$tbName = $db->getRelationTableName($this->getTableName(),$object->getTableName());
			$sql = "DELETE FROM $tbName WHERE ".$this->getTableKeyName()."='".$this->getId()."' AND ".$object->getTableKeyName()."='".$object->getId()."'";
			$db->query($sql);
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
		public function removeAllRelations($tbName){
			if($this->getState() == OBJECT_DELETED) throw new ODBEObjectNotExist();
			if(!$this->isSaved()) throw new ODBEObjectNotInDatabase("Save this object before relation it");

			$db = new ODBConnection();
			$tbRelationName = $db->getRelationTableName($this->getTableName(),$tbName);
			$sql = "DELETE FROM $tbRelationName WHERE ".$this->getTableKeyName()."='".$this->getId()."'";
			$db->query($sql);
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
		public function __toString(){
			if($this->getState() == OBJECT_DELETED) throw new ODBEObjectNotExist();

			$header='<th>'.$this->getTableKeyName().'</th>'; 
			$body='<td>'.$this->getId().'</td>'; 	
			foreach($this as $key=>$value) {
				$header .= "<th>$key</th>";
				$body .= "<td>$value</td>";
			}
			return '<table border="1"><theader><tr>'.$header.'</tr></theader><tbody><tr>'.$body.'<tr></tbody></table>';
		}
	}

?>