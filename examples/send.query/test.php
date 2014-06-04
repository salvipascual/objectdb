<?php
	require_once "../objectDB/objectDB-mysql-v3.0.php"; // this will include first
	require_once "student.php";

	// load the 10 first students with age>25 and orange as color preference
	$db = new objectDB();
	$students_list = $db->query("SELECT * FROM student WHERE age>'25' AND preferences LIKE '%color=orange%' LIMIT 10");

	// print students 
	echo '<h1>List of students in database with age>25 and orange as color preference</h1>';
	for($i=0; $i<count($students_list); $i++)
		echo $students_list[$i] . '<br/>';

	// delete the last student with prefear serie is 24 hours (nothing personal)
	$db->query("DELETE FROM student WHERE preferences LIKE '%serie=24 hours%' ORDER BY id_student DESC LIMIT 1");
?>