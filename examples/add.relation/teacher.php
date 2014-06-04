<?php
	// constants for marital status
	define("SINGLE", 	0x01, true);
	define("MARRIED", 	0x02, true);
	define("DIVORCED",	0x03, true);
	define("WIDOWED", 	0x04, true);

	class teacher extends ODBObject{
		public $professorship_number;
		public $name;
		public $experience_time;
		public $marital_status; // constants
		public $contract_date;  // date
	}
?>