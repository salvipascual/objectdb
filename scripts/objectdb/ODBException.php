<?php

	//**********************************************
	//	base exception class, please, do not use
	//**********************************************

	class ODBException extends Exception {

		public $message;

		function ODBException($message=null){
			$this->message = $message ? $message : mysql_error();
		}

		public function __toString(){
			return 'Raised by ObjectDB => '. get_class($this).': "'.$this->message.'"';
		}
	}

	//**********************************************
	//	list of aviable exceptions
	//**********************************************

	class ODBENotConnected 			extends ODBException{}	// Raised if are problem with database connection
	class ODBEConnectionNotActive 	extends ODBException{}	// Raised if connection closed previously
	class ODBEObjectNotInDatabase	extends ODBException{} 	// Raised if object to treat not exist in database
	class ODBEWrongSQLSentence 		extends ODBException{} 	// Raised if the SQL sentence are incorrect
	class ODBETableNotDefined 		extends ODBException{} 	// Raised if try to use not defined table 
	class ODBEClassNotDefined 		extends ODBException{}	// Raised if try to use not defined class
	class ODBECannotChangeKey 		extends ODBException{}	// Raised if try to change the key for one row
	class ODBEObjectNotExist 		extends ODBException{}	// Raised if try to use a deleted or inexistent object 
	class ODBEMoreThanOneInstance 	extends ODBException{}	// Raised if try to instanciate a singletton class more than one time

?>