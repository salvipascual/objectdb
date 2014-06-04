<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1" />
		<title>ObjectDB official website - Documentation</title>
		
		{% head_inclusions %}
		
	</head>
	<body>
		{% top %}

		<div id="content" class="left-indent right-indent">

		<h1>Getting Started</h1>
		<p>The following is a detailed example of how to configure and launch an  application that performs simple read-write access to a database using objectDB.  It was developed with PHP v5.x and MySQL v5.x; it must work for other versions  of MySQL and other database systems, depending of the objectDB library used. Because  of the poor support provided by PHP v4.x for  object-oriented applications, objectDB does not work for these versions or  lower.</p>
		<p>Three  basic actions should be taken to create easy access to the database:</p>
	  <ul>

			<li>Creating  tables in the database.</li>
			<li>Creating  objectDB classes.</li>
			<li>Setting  up the access of objectDB to the database.</li>
		</ul>


		<h2>Creating tables in the database</h2>
		<p>When creating tables in the database, the only absolute requirement is that the key of the table is numeric, autoincrementable and by the name <i>id_tablename</i>. It can be created a table supported by objectDB, for a MySQL database with the following script in SQL language:</p>

<pre class="sh_sql">
CREATE TABLE student (
	id_student INT(11) KEY AUTO_INCREMENT,
	identification_number VARCHAR(11) NOT NULL,
	name varchar(50) NOT NULL,
	age INT(2) NULL,
	preferences TEXT
);
</pre>
		<p>In the above example, note that the field 'identification_number' is an alternative if an index, that does not meet the requirements expected by the library, is needed.</p>

		
		<h2>Creating  objectDB classes</h2>
		<p>All classes that store data must extend <i>ODBObject</i> class, defined in the core of objectDB. This  class, at the same time as serves as a basis for encapsulating information, has  special features that facilitate the work of the programmer. The subject will  be discussed thoroughly in  later sections. Besides the above, each attribute of the class will be defined  as public and its name must match the name of the table fields. By convention the  order of attributes must also match the order of the fields in the table, but  the above is not required.</p>
		<p>Below is shown a PHP language sample about how  it would be defined a class to interact with the table defined above:</p>

<pre class="sh_php">
class student extends ODBObject{
	public $identification_number;
	public $name;
	public $age;
	public $preferences;
}
</pre>
		<p>Although not essential, by convention each class  should be described in a file by the name of <em>class</em><i>_name.php</i>; for the case of the above, it would be <i>student.php</i>. This file may begin with the inclusion of <i>ODBObject.php</i> file using this statement:</p>
<pre class="sh_php">
require_once &quot;pathToFile/ODBObject.php&quot;;
</pre>

		<h2>Setting up the access of objectDB to the database.</h2>
		<p>The first lines of <i>ODBConnection</i> class are static parameters which must be  configured for each server. These parameters can be set dynamically, increasing  the security of applications using direct loading of a file.</p>
<pre class="sh_php">
...
/* start - configure */
static public $host   = &quot;localhost&quot;;
static public $port   = &quot;3306&quot;;
static public $dbname = &quot;test&quot;;
static public $user   = &quot;root&quot;;
static public $pass   = &quot;&quot;;
/* end - configure */
...
</pre>
		<p>The explanation for the previous parameters is  as follows:</p>
	  <dl>

			<dt>$host</dt><dd>Address (IP or DNS) of the server where the database is (localhost for the machine itself).</dd>
			<dt>$port</dt><dd>Port  for accessing the database.</dd>
			<dt>$dbname</dt><dd>Name  of the database.</dd>
			<dt>$user</dt><dd>Username  to access the database.</dd>
			<dt>$pass</dt><dd>Password  to access the database.</dd>
		</dl>
		<p>After configuring the connection to the  database, set the table and objectDB class, simple and functional read-write  accesses can be performed. This requires creating a new file called <i>test.php</i> with the following content:</p>
<pre class="sh_php">
require_once &quot;../objectDB/objectDB-mysql-v3.0.php&quot;; // this will include first
require_once &quot;student.php&quot;;

// create and set values for a new student
$student = new student();
$student-&gt;setDataFromParamsList(3025,'Salvi Pascual',24,'color=orange,grossery=pie,serie=futurama');
	
// save ths new student in database
$student-&gt;save();

// load all students saved until now
$db = new objectDB();
$students_list = $db-&gt;getObjs('student');

// print students 
echo 'List of students in database';
for($i=0; $i&#60count($students_list); $i++)
	echo $students_list[$i];
</pre>
		<p>Preceding code creates a new student, assigns  values to it, passing them as sorted parameters  based on the fields in the table (excluding the key field) to <i>setDataFromParamsList</i> method and then saves the object in the  database. Finally it createsa new <i>objectDB</i> object and uses it to load from the database all  saved students up to now and then they are displayed. In  sections below will be explained in greater detail the steps in this process. Visit <a href="https://github.com/salvipascual/objectdb/archive/example-basic.in.out.zip">this link</a> to download the source code for the example  above.</p>



		<h1>Advanced Studies</h1>

		
		<h2>Debugging the content of an  object</h2>
		<p>ObjectDB may display the contents of an object and  simplify for programmers the debugging process. Any object that inherits from <i>ODBObject</i>, when is displayed on screen (via echo or printf)  produces a table with the relationship field name and value. In the example  below shows the entered code and the resulting table.</p>
<pre class="sh_php">
require_once &quot;../objectDB/objectDB-mysql-v3.0.php&quot;; // this will include first
require_once &quot;student.php&quot;;

// create and set values for a new student
$student = new student();
$student-&gt;setDataFromParamsList(3025,'Salvi Pascual',24,'color=orange,grossery=pie,serie=futurama');

// print this object in debug mode
echo $student;

</pre>
		<p>The table below shows the output of the previous  script</p>
		<table border="1"><tr><th>id_student</th><th>identification_number</th><th>name</th><th>age</th><th>preferences</th></tr><tbody><tr><td>1</td><td>3025</td><td>Salvi Pascual</td><td>24</td><td>color=orange,grossery=pie,serie=futurama</td><tr></tbody></table>
		<p>The  debugging algorithm&rsquo;s output may be changed by overwriting the <i>__toString</i> method  of the <i>ODBObject</i> class.  After it is overwritten, the text string returned by the new method will be  displayed when a variable is printed. The following code shows how this could  be done for the student class, defined in the previous section.</p>

<pre class="sh_php">
class student extends ODBObject{
	public $identification_number;
	public $name;
	public $age;
	public $preferences;
	
	function __toString(){
		return 'My identification number is:' . $this-&gt;identification_number;
	}
}
</pre>


		<h2>Fill in the attributes of  an object</h2>
		<p>There are three ways to fill the attributes of  an object in objectDB:</p>
	  <dl>
			<dt>Insert  data manually</dt><dd>Useful  for modifying one or a few attributes of the object or for objects with a small  amount of attributes. Not recommended for use as  it enlarges and obfuscates the resulting code.</dd>

			<dt>By the <i>setDataFromArray</i> method of the <i>ODBObject</i> class</dt><dd>Allows  filling the attributes of the object using a string array passed by parameter  to the <i>setDataFromArray</i> method.  Mainly it is used to create objects with information obtained dynamically.</dd>
			<dt>By using the <i>setDataFromParamsList</i> method of the <i>ODBObject</i> class</dt><dd>Fills  in the attributes of the object with the parameters passed to the <i>setDataFromParamsList</i> method.  It is comfortable and its use is recommended for most situations, however care  must be taken to insert the right amount of parameters and in the order defined  in the table.</dd>
		</dl>
		<p>No  matter which way it could be used, it should never be tried to insert/change  the key attribute of the table, which could, depending on the situation,  generate an error or include unwanted behavior. Here is a code that describes  the three ways to fill/modify attributes of an object.</p>
<pre class="sh_php">
require_once &quot;../objectDB/objectDB-mysql-v3.0.php&quot;; // this will include first
require_once &quot;student.php&quot;;

// create a new student object, with blank attributes
$student = new student();

// change attributes manually
$student-&gt;identification_number = '3025'; // changes is allow because this is not the table key
$student-&gt;name = 'Salvi Pascual';
$student-&gt;age = 37;
$student-&gt;preferences = 'color=orange,grossery=pie,serie=futurama';

// change attributes by setDataFromArray
$attributes = Array('3025','Salvi Pascual','24','color=orange,grossery=pie,serie=futurama');
$student-&gt;setDataFromArray($attributes);

// change attributes by setDataFromParamsList
$student-&gt;setDataFromParamsList(3025,'Salvi Pascual',24,'color=orange,grossery=pie,serie=futurama');
</pre>


		<h2>Send a custom query to the server</h2>
		<p>However  objectDB eliminates highly writing code in the SQL language, and encourages  using predefined functions that cover most of the needs of the programmer, sometimes  it is needed to send queries made by the programmer to the server to perform  complex searches. It is for this reason that the <i>query</i> method  of the <i>objectDB</i> controlling class is used. In  the following example, first it is obtained and displayed the top ten students  whose age is over 25 years and prefer the orange color and then deletes the  last student whose favorite serial is 24 hours (nothing personal).</p>

<pre class="sh_php">
require_once &quot;../objectDB/objectDB-mysql-v3.0.php&quot;; // this will include first
require_once &quot;student.php&quot;;

// load the 10 first students with age&gt;25 and orange as color preference
$db = new objectDB();
$students_list = $db-&gt;query(&quot;SELECT * FROM student WHERE age&gt;'25' AND preferences LIKE '%color=orange%' LIMIT 10&quot;);

// print students 
echo 'List of students in database with age&gt;25 and orange as color preference';
for($i=0; $iquery(&quot;DELETE FROM student WHERE preferences LIKE '%serie=24 hours%' ORDER BY id_student DESC LIMIT 1&quot;);
</pre>
		<p>Note  that for the case of SELECT type queries the method returns an array of objects  (such as the method <i>getObjs</i>); for  queries unanswered, such as DELETE, INSERT, etc. the method returns NULL. To  download the code for this example, visit <a href="https://github.com/salvipascual/objectdb/archive/example-send.query.zip">this link</a></p>

		
		<h2>Modify saved objects</h2>
		<p>When  an object is saved in the database, its value can be uploaded and changed. This  is necessary to obtain the object by using the <i>getObjs</i> function  of the <i>objectDB</i> controlling  class (exemplified in the previous section), change their values and save it  again by using the <i>save</i> function  of the <i>ODBObject</i> class  (also shown in the previous section) or by <i>saveObj</i> function  of the <i>objectDB</i> controlling  class. The code then takes place due to the use of this latter form, not  illustrated previously and continues the previous example. To download the  source files of this example, you can visit <a href="https://github.com/salvipascual/objectdb/archive/example-modify.objects.zip">this link</a>.</p>

<pre class="sh_php">
require_once &quot;../objectDB/objectDB-mysql-v3.0.php&quot;; // this will include first
require_once &quot;student.php&quot;;

// load all saved students
$db = new objectDB();
$students_list = $db-&gt;getObjs('student');

// catch first load student
$student = $students_list[0];

// change students parameters
$student-&gt;name = 'Cristobal Col&oacute;n';
$student-&gt;age = 37;

// save changed student
$db-&gt;saveObj($student);
// $student-&gt;save(); // this works too

// show updated student
echo $student;
</pre>


		<h2>Get the last saved object</h2>
		<p>In  general it is necessary to load the last stored object in the database. This  functionality is extremely useful when, for example, need show only the last  comment from a user or most current news. Versatile mechanisms exist to perform  this action with objectDB, for example, to load all items of a table by means  of <i>getObjs</i>and  go through the list that the method returns to the end, or (in an effortless perspective)  to implement the <i>query</i> method  (previously seen) with the SQL language code shown below:</p>

<pre class="sh_php">
$db = new objectDB();
$result = $db-&gt;query(&quot;SELECT * FROM student ORDER BY id_student DESC LIMIT 1&quot;);
</pre>
		<p>But to really take advantage of objectDB cache resources, and minimize accesses to the database, it is preferable to use the <i>getLastObject</i> method of the <i>objectDB</i> controlling class. The code below shows an example of its use; its sources can be downloaded via <a href="https://github.com/salvipascual/objectdb/archive/example-get.last.saved.object.zip">this link</a>.</p>
<pre class="sh_php">
require_once &quot;../objectDB/objectDB-mysql-v3.0.php&quot;; // this will include first
require_once &quot;student.php&quot;;

// load the last saved object
$db = new objectDB();
$last_student = $db-&gt;getLastObject('student');

// print the last saved student
echo $last_student;
</pre>

		<h2>Delete an object from the database</h2>
		<p>There are two ways to eliminate a single object from the database. The first is to use the <i>remove</i> method, typical of the <i>ODBObject</i> class and existing for any object that inherits from it. The second one is by the <i>removeObj</i> method of the <i>objectDB</i> controlling class, which must have as a parameter the object to delete. Then the two first students of the table are removed, if both exist. The source code for this example can be downloaded via <a href="https://github.com/salvipascual/objectdb/archive/example-single.delete.zip">this link</a>.</p>

<pre class="sh_php">
require_once &quot;../objectDB/objectDB-mysql-v3.0.php&quot;; // this will include first
require_once &quot;student.php&quot;;

// load all students in database
$db = new objectDB();
$students_list = $db-&gt;getObjs('student');

// remove first student
$students_list[0]-&gt;remove();

// remove second student
$db-&gt;removeObj($students_list[1]);
</pre>


		<h2>Removing multiple objects of the database</h2>
		<p>By using the ways discussed above a single object can be deleted by a reference to it. This is inefficient if the purpose consists in deleting all entries in a table, or many entries that satisfy a given condition. For that objectDB provides the <i>remove</i> method, located in the controlling class <i>objectDB</i>, which should be given as parameters the name of the table on which to act and an optional expression in SQL language to filter the removal. Failure to pass this expression to <i>remove</i> method, it will delete all such stored objects in the database. The code below details this behavior; use <a href="https://github.com/salvipascual/objectdb/archive/example-multiple.delete.zip">this link</a> to download it.</p>

<pre class="sh_php">
require_once &quot;../objectDB/objectDB-mysql-v3.0.php&quot;; // this will include first
require_once &quot;student.php&quot;;

// remove all student with age&gt;32 years
$db = new objectDB();
$db = $db-&gt;remove('student',&quot;age&gt;'32'&quot;);
</pre>



		<h1>Relations between tables</h1>
		<p>One  of the main capabilities of objectDB is the ease to create relationships  between tables in the database without modifying the established tables&rsquo; model  and objects. The main objectives of objectDB, is that the programmer is able to  create, delete and access relationships between tables with a few lines of  code, and using a simple API to learn and use without having advanced knowledge  of SQL. Behind the ease of use to relate tables, is once again the cache engine  of objectDB. This highly reduces the accesses to the database server and  thereby raises the final performance of applications which use the library.</p>


		<h2>Create a relationship table</h2>

		<p>To relate two tables objectDB, the first step is to create a table of relationship (or nexus) between the two tables to connect. The relation table name must consist of the string <i>relation</i>, followed  by the character underscore ( _ ), then  the name of the first table to relate, again the underscore character and  finally the name of the second table to relate. As an example, the name of a  table link between the <i>student</i> table and a new table named teacher would be <i>relation_student_teacher</i>. The order of the names of the tables that make up the relation table is irrelevant, so it might as well to write <i>relation_student_teacher</i> or <i>relation_teacher_student</i>.		
        </p>Relation tables must consist of three fields:
  <dl>

			<dt>Key to the link table</dt><dd>It represents the key field of the relation table. This fulfills the same requirements as the rest of the tables; must be numeric, autoincrementable and by the name of <i>id_tablename</i>. As an example, the table <i>relation_student_teacher</i> would have as key name <i>id_relation_student_teacher</i>.</dd>
			<dt>Key to the first table to relate</dt><dd>This field is not a key attribute; it has the same name as the key to the first table to relate. For example, to the nexus table <i>relation_student_teacher</i>, this field would be <i>id_student</i>.</dd>

			<dt>Key to the second table to relate</dt><dd>This field is not a key attribute; it has the same name as the key to the second table to relate. For example, to the nexus table <i>relation_student_teacher</i>, this field would be <i>id_teacher</i>.</dd>
		</dl>
		<p>The example below shows a snippet of code in SQL language, optimized for MySQL v5.x generated by the <i>student</i> table (used so far), the new table <i>teacher</i> and the link table to relate the two.</p>

<pre class="sh_sql">
CREATE TABLE student (
	id_student INT(11) KEY AUTO_INCREMENT,
	identification_number VARCHAR(11) NOT NULL,
	name varchar(50) NOT NULL,
	age INT(2) NULL,
	preferences TEXT
);

CREATE TABLE teacher (
	id_teacher INT(11) KEY AUTO_INCREMENT,
	professorship_number VARCHAR(11) NOT NULL,
	name varchar(50) NOT NULL,
	experience_time INT(2) NULL,
	marital_status TINYINT(1),
	last_university_payment_date DATE
);

CREATE TABLE relation_student_teacher (
	id_relation_student_teacher INT(11) KEY AUTO_INCREMENT,
	id_student INT(11),
	id_teacher INT(11)
);
</pre>
		<p>Below is the content for the <i>teacher.php</i> file, which defines the class corresponding to the table with the same name. The <i>student</i> class, located in the file <i>student.php</i>, is shown in previous sections. No need to create a class that represents the relation table, due to the process of working with relations never required to instantiate it.</p>
<pre class="sh_php">
// constants for marital status
define(&quot;SINGLE&quot;, 	0x01, true);
define(&quot;MARRIED&quot;, 	0x02, true);
define(&quot;DIVORCED&quot;,	0x03, true);
define(&quot;WIDOWED&quot;, 	0x04, true);

class teacher extends ODBObject{
	public $professorship_number;
	public $name;
	public $experience_time;
	public $marital_status; // constants
	public $contract_date;  // date
}
</pre>

		<h2>Define a relationship between two objects</h2>
		<p>The method <i>addRelation</i> of the <i>ODBObject</i> class defines a relationship between two objects. An object of the <i>ODBObject</i> type must be passed as parameter to this method, existing in the database (it must be saved before) and which had previously created a relation table. If any of the objects, which are tried to connect, does not exist in the database, an exception <i>ODBEObjectNotInDatabase</i> is thrown; as objectDB policy does not save objects without the direct request of the developer. Below is an example of how to relate two objects, <i>student</i> and <i>teacher</i> type, defined previously. To download the same code, you can access <a href="https://github.com/salvipascual/objectdb/archive/example-add.relation.zip">this link</a>.</p>

<pre class="sh_php">
require_once &quot;../objectDB/objectDB-mysql-v3.0.php&quot;; // this will include first
require_once &quot;student.php&quot;;
require_once &quot;teacher.php&quot;;

// create, set values and save a new student
$student = new student();
$student-&gt;setDataFromParamsList(3025,'Salvi Pascual',24,'color=orange,grossery=pie,serie=futurama');
$student-&gt;save();

// create, set values and save a new teacher
$teacher = new teacher();
$teacher-&gt;setDataFromParamsList('math201','Stephen W. Hawking',37,MARRIED,date(&quot;Y-m-d&quot;));
$teacher-&gt;save();

// create relation between
$student-&gt;addRelation($teacher); // same than: $teacher-&gt;addRelation($student);
</pre>
		<p>Since a student is related to his teacher the same way as the teacher with his student, the <i>$student-&gt;addRelation($teacher);</i> line can be exchanged for <i>$teacher-&gt;addRelation($student);</i> and get the same result. It will throw an exception if a relationship for two objects is saved more than once.</p>

		
		<h2>Get the relationship to an object</h2>
		<p>After relationships between objects have been created, the next is to learn how to consult them easily. For this purpose <i>ObjectDB</i> offers the <i>relations</i> method of the <i>ODBObject</i> class. It receives as parameter a string representing the name of the class to query, and returns the list of objects related to the object from which was queried. In the example below this operation is illustrated. If you want to download the source code, visit <a href="https://github.com/salvipascual/objectdb/archive/example-get.relations.zip">this link</a>.<p>

<pre class="sh_php">
require_once &quot;../objectDB/objectDB-mysql-v3.0.php&quot;; // this will include first
require_once &quot;student.php&quot;;
require_once &quot;teacher.php&quot;;
	
// create, set values and save a new student
$student = new student();
$student-&gt;setDataFromParamsList(3025,'Salvi Pascual',24,'color=orange,grossery=pie,serie=futurama');
$student-&gt;save();

// values of university teachers
$teachers_values = Array(
	Array('math201','Stephen W. Hawking',37,MARRIED,date(&quot;Y-m-d&quot;)),
	Array('math201','Albert Einstain',68,MARRIED,date(&quot;Y-m-d&quot;)),
	Array('math201','Nicolas Coppernico',37,WIDOWED,date(&quot;Y-m-d&quot;)),
	Array('math201','Felix Varela',37,SINGLE,date(&quot;Y-m-d&quot;)),
	Array('math201','Meredick Jhones',37,DIVORCED,date(&quot;Y-m-d&quot;))
);

// create and relation teachers
$teachers = Array(count($teachers_values));
for ($i=0; $isetDataFromArray($teachers_values[$i]);
	$teachers[$i]-&gt;save();
	// relation each teacher
	$student-&gt;addRelation($teachers[$i]);
}

//load and show all relations
$teachers = $student-&gt;relations('teacher');
foreach ($teachers as $teacher) echo $teacher;
</pre>


		<h2>Determine whether two objects are related</h2>
		<p>Sometimes it is useful to know if two objects are related to each other, for this the <i>ODBObject</i> class provides the method <i>isRelationedWith</i>, to which an object of the <i>ODBObject</i> type is passed as a parameter and returns <i>true</i> if any relationship exists and <i>false</i> otherwise. As <i>addRelation</i>, this method raises an exception <i>ODBEObjectNotInDatabase</i> whether any items are not found in the database. Below is an example of how to work with the method <i>isRelationedWith</i>.</p>

<pre class="sh_php">
require_once &quot;../objectDB/objectDB-mysql-v3.0.php&quot;; // this will include first
require_once &quot;student.php&quot;;
require_once &quot;teacher.php&quot;;

// create, set values and save a new student
$student = new student();
$student-&gt;setDataFromParamsList(3025,'Salvi Pascual',24,'color=orange,grossery=pie,serie=futurama');
$student-&gt;save();

// create, set values and save a new teacher
$teacher = new teacher();
$teacher-&gt;setDataFromParamsList('math201','Stephen W. Hawking',37,MARRIED,date(&quot;Y-m-d&quot;));
$teacher-&gt;save();

$student-&gt;isRelationedWith($teacher); // this will return false

$student-&gt;addRelation($teacher);
$student-&gt;isRelationedWith($teacher); // now will return true
</pre>
		<p>It  is possible to use the <i>relations</i> method to get the list of teachers related to a specific student and then to go round it asking if anyone is the sought teacher, but this approach unnecessarily increases the number of lines of code and does not exploit the cache engine of <i>ObjectDB</i>, to the maximum, so it is advisable not to develop this solution. Like using the method <i>addRelation</i>, has the same result to write <i>$student-&gt;addRelation($teacher);</i> or <i>$teacher-&gt;addRelation($student);</i>.</p>


		<h2>Delete a relationship between two objects</h2>
		<p>The method <i>removeRelation</i> of the <i>ODBObject</i> class is used to delete a relationship between two objects. An object of the <i>ODBObject</i> type is passed as parameter to this method, with which is wanted to disconnect the object in use. This method will throw an exception <i>ODBEObjectNotInDatabase</i> if any of the objects are not in the database. It will also throw an exception of the <i>ODBENoRelationBetween</i> type if there is not relationship between two objects. The following example shows how to delete a relationship using <i>removeRelation</i>. To download the source code, visit <a href="https://github.com/salvipascual/objectdb/archive/example-delete.relation.zip">this link</a>.<br />In the example below causes the same result to write <i>$student-&gt;removeRelation($teacher);</i> or <i>$teacher-&gt;removeRelation($student);</i>.</p>

<pre class="sh_php">
require_once &quot;../objectDB/objectDB-mysql-v3.0.php&quot;; // this will include first
require_once &quot;student.php&quot;;
require_once &quot;teacher.php&quot;;

// load the last saved student
$db = new objectDB();
$student = $db-&gt;getLastObject('student');
	
// load first relation of the load student 
$teachers = $student-&gt;relations('teacher');
$teacher = $teachers[0];

// delete relation
$student-&gt;removeRelation($teacher); // same than: $teacher-&gt;removeRelation($student);
</pre>


		<h2>Destroy all relations of an object</h2>
		<p>On several occasions it is necessary to deprive an object of all their relationships. Clear examples of this need are when a customer buys the entire stock of a product at an online store, or before dismissing a teacher it is required to delete all relationships he holds with his students. The method <i>removeAllRelations</i> accomplishes this work. The last case can be described in the code example below. You can also download the source code using <a href="https://github.com/salvipascual/objectdb/archive/example-delete.all.relations.zip">this link</a>.</p>
<pre class="sh_php">

require_once &quot;../objectDB/objectDB-mysql-v3.0.php&quot;; // this will include first
require_once &quot;teacher.php&quot;;

// load last teacher in evaluation, to dismiss
$db = new objectDB();
$teacher = $db-&gt;getLastObject('teacher');

// remove all relations with his older students
$teacher-&gt;removeAllRelations('student');
</pre>
		<p>A text string that represents the name of the table with which to remove all relationships is passed as a parameter to the <i>removeAllRelations</i> method. This method will throw an exception <i>ODBEObjectNotInDatabase</i> if the object is not in the database or has not been saved yet. It will also throw an exception of the <i>ODBETableNotDefined</i> type if the text string inserted as a parameter does not represent the name of a table in the database.</p>
			<div class="separation"></div>
		</div>

		{% bottom %}
	</body>
</html>
