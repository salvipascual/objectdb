<?php
	require_once "../objectDB/objectDB-mysql-v3.0.php"; // this will include first
	require_once "student.php";

	// load all students in database
	$db = new objectDB();
	$students_list = $db->getObjs('student');

	// remove first student
	$students_list[0]->remove();

	// remove second student
	$db->removeObj($students_list[1]);
?>