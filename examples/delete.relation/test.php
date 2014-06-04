<?php
	require_once "../objectDB/objectDB-mysql-v3.0.php"; // this will include first
	require_once "student.php";
	require_once "teacher.php";

	// load the last saved student
	$db = new objectDB();
	$student = $db->getLastObject('student');
	
	// load first relation of the load student 
	$teachers = $student->relations('teacher');
	$teacher = $teachers[0];

	// delete relation
	$student->removeRelation($teacher); // same than: $teacher->removeRelation($student);
?>