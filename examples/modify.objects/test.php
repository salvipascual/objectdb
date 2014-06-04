<?php
	require_once "../objectDB/objectDB-mysql-v3.0.php"; // this will include first
	require_once "student.php";

	// load all saved students
	$db = new objectDB();
	$students_list = $db->getObjs('student');

	// catch first load student
	$student = $students_list[0];

	// change students parameters
	$student->name = 'Cristobal Colón';
	$student->age = 37;

	// save changed student
	$db->saveObj($student);
	// $student->save(); // this works too

	// show updated student
	echo $student;
?>