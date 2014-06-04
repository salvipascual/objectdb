<?php
	require_once "../objectDB/objectDB-mysql-v3.0.php"; // this will include first
	require_once "student.php";
	require_once "teacher.php";
	
	// create, set values and save a new student
	$student = new student();
	$student->setDataFromParamsList(3025,'Salvi Pascual',24,'color=orange,grossery=pie,serie=futurama');
	$student->save();

	// values of university teachers
	$teachers_values = Array(
		Array('math201','Stephen W. Hawking',37,MARRIED,date("Y-m-d")),
		Array('math201','Albert Einstain',68,MARRIED,date("Y-m-d")),
		Array('math201','Nicolas Coppernico',37,WIDOWED,date("Y-m-d")),
		Array('math201','Felix Varela',37,SINGLE,date("Y-m-d")),
		Array('math201','Meredick Jhones',37,DIVORCED,date("Y-m-d"))
	);

	// create and relation teachers
	$teachers = Array(count($teachers_values));
	for ($i=0; $i<count($teachers_values); $i++){
		// create and fill teacher
		$teachers[$i] = new teacher();
		$teachers[$i]->setDataFromArray($teachers_values[$i]);
		$teachers[$i]->save();
		// relation each teacher
		$student->addRelation($teachers[$i]);
	}

	//load and show all relations
	$teachers = $student->relations('teacher');
	foreach ($teachers as $teacher) echo $teacher;
?>