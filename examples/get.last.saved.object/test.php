<?php
	require_once "../objectDB/objectDB-mysql-v3.0.php"; // this will include first
	require_once "student.php";

	// load the last saved object
	$db = new objectDB();
	$last_student = $db->getLastObject('student');

	// print the last saved student
	echo $last_student;
?>