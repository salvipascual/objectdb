<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1" />
		<title>ObjectDB official website - API</title>

		{% head_inclusions %}
		
	</head>
	<body>
		{% top %}

		{% middleapimenu %}

		<div id="content" class="left-indent right-indent">
			<h1>objectDB</h1>
			<p>The class shown below defines the <i>objectDB</i> object; it executes the main functions for the library. This class cannot be instantiated more than once, or an exception of the <i>ODBEMoreThanOneInstance</i> type will be generated..</p>
			
<pre class="sh_php">
class objectDB{
	public function objectDB();
	public function query($sql);
	public function getObjs($tbName,$where=&quot;&quot;);
	public function saveObj($object);
	public function removeObj($object);
	public function remove($tbName,$where=&quot;&quot;);
	public function getLastObject($tbName);
}
</pre>

		  <h2>Constructor</h2>
			<p>This class follows the design pattern &quot;Singletton&quot;, so it should not be instantiated more than once. It will generate an exception of the <i>ODBEMoreThanOneInstance</i> type otherwise.</p>
<dl>
				<dt>Parameters</dt>
	<dd>none</dd>

				<dt>Exceptions</dt>
					<dd>ODBEMoreThanOneInstance: it is thrown if the intention of building more than one instance occurs</dd>
			</dl>


		  <h2>query()</h2>
			<p>It executes a query, whether it is a SELECT, returns a list of objects, otherwise it returns NULL.</p>
			<dl>

				<dt>Return type</dt>
					<dd>Array | NULL</dd>
				<dt>Parameters</dt>
			  <dd>String: a valid query</dd>
				<dt>Exceptions</dt>
					<dd>ODBEWrongSQLSentence: it is thrown if he SQL statement is incorrect.</dd>
			</dl>


		  <h2>getObjs()</h2>
			<p>It gets a list of objects to filter, given the name of the table and a SQL expression. Used for obtaining the entire table, the second parameter must be blank.</p>
			<dl>
				<dt>Return type</dt>
					<dd>Array: list of objects</dd>

				<dt>Parameters</dt>
			  <dd>String: name of the table from which the objects will be taken.</dd>
					<dd>String: expression in SQL language to filter the results (eg: id_table = '24 ').</dd>
				<dt>Exceptions</dt>
					<dd>ODBETableNotDefined: it is thrown in the case of using an undefined table</dd>
					<dd>ODBEWrongSQLSentence: it is thrown if the SQL statement is incorrect</dd>
			</dl>


		  <h2>saveObj()</h2>
			<p>It saves or replaces an object in the database.</p>
			<dl>
				<dt>Return type</dt>
					<dd>none</dd>

				<dt>Parameters</dt>
			  <dd>ODBObject: not saved object for saving or replacing.</dd>
				<dt>Exceptions</dt>
					<dd>ODBEObjectNotExist: it is thrown if a deleted or nonexistent object is used.</dd>
			</dl>


		  <h2>removeObj()</h2>

			<p>It deletes an object from the database.</p>
			<dl>
				<dt>Return type</dt>
					<dd>Boolean: TRUE if it is deleted, otherwise it throws exceptions.</dd>
				<dt>Parameters</dt>
			  <dd>ODBObject: object to remove</dd>

				<dt>Exceptions</dt>
					<dd>ODBEObjectNotInDatabase: it is thrown whether the object to remove does not exist in the database</dd>
					<dd>ODBEObjectNotExist: it is thrown if a deleted or nonexistent object is used</dd>
			</dl>

			
		  <h2>remove()</h2>
			<p>It deletes a list of objects given the name of the table and a SQL expression to filter.</p>

			<dl>
				<dt>Return type</dt>
					<dd>none</dd>
				<dt>Parameters</dt>
					<dd>String: name of the table from which objects will be deleted</dd>
					<dd>String: expression in SQL language to filter the results (eg: id_table &lt;= '24 ')</dd>

				<dt>Exceptions</dt>
					<dd>ODBETableNotDefined: it is thrown in the case of using an undefined table</dd>
					<dd>ODBEWrongSQLSentence: it is thrown when the SQL statement is incorrect</dd>
			</dl>

			
		  <h2>getLastObject()</h2>
			<p>It gets the last saved object in a table.</p>

			<dl>
				<dt>Return type</dt>
					<dd>ODBObject: last saved object</dd>
				<dt>Parameters</dt>
					<dd>String: name of the table from which to obtain the latest saved object</dd>
				<dt>Exceptions</dt>

					<dd>ODBETableNotDefined: it is thrown in the case of using an undefined table</dd>
			</dl>
		</div>

		{% bottom %}
	</body>
</html>
