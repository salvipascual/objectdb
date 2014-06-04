<?php
	require_once "../objectDB/objectDB-mysql-v3.0.php"; // this will include first
	require_once "student.php";
	require_once "teacher.php";
	
	// create, set values and save a new student
	$student = new student();
	$student->setDataFromParamsList(3025,'Salvi Pascual',24,'color=orange,grossery=pie,serie=futurama');
	$student->save();

	// create, set values and save a new teacher
	$teacher = new teacher();
	$teacher->setDataFromParamsList('math201','Stephen W. Hawking',37,MARRIED,date("Y-m-d"));
	$teacher->save();

	// create relation between
	$student->addRelation($teacher); // same than: $teacher->addRelation($student);
?>