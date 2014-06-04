<?php
	require_once "../objectDB/objectDB-mysql-v3.0.php"; // this will include first
	require_once "teacher.php";

	// load last teacher in evaluation, to dismiss
	$db = new objectDB();
	$teacher = $db->getLastObject('teacher');

	// remove all relations with his older students
	$teacher->removeAllRelations('student');
?>