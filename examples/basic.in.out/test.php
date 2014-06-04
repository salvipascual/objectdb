<?php
	require_once "../objectDB/objectDB-mysql-v3.0.php"; // this will include first
	require_once "student.php";

	// create and set values for a new student
	$student = new student();
	$student->setDataFromParamsList(3025,'Salvi Pascual',24,'color=orange,grossery=pie,serie=futurama');
	
	// save ths new student in database
	$student->save();

	// load all students saved until now
	$db = new objectDB();
	$students_list = $db->getObjs('student');

	// print students 
	echo '<h1>List of students in database</h1>';
	for($i=0; $i<count($students_list); $i++)
		echo $students_list[$i] . '<br/>';
?>