<?php
	require_once "../objectDB/objectDB-mysql-v3.0.php"; // this will include first
	require_once "student.php";

	// remove all student with age>32 years
	$db = new objectDB();
	$db = $db->remove('student',"age>'32'");
?>