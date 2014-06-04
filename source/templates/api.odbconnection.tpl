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
			<h1>ODBConnection</h1>
			<p>The class shown below defines the object <i>ODBConnection</i>, the same object opens and destroys connections to the database and implements the basic functions of access to it. It is not advisable to use the functions of connection directly from an object <i>ODBConnection</i>, instead it would be better the use of more sophisticated methods, provided by the <i>ODBObject</i> and <i>objectDB</i>, objects in order to ensure the work with the cache.</p>

			<p>An <i>ODBConnection</i> object should never be created by the user, although the possibility is not limited to. This object is instantiated and used by the other classes of <i>objectDB</i> to perform accesses to the database. The only contact of the programmer for this purpose should be to modify the configuration parameters.</p>
<pre class="sh_php">
class ODBConnection {
	/* start - configure */
	static public $host   = &quot;localhost&quot;;
	static public $port   = &quot;3306&quot;; // mysql default port
	static public $dbname = &quot;database_name&quot;;
	static public $user   = &quot;database_username&quot;;
	static public $pass   = &quot;database_password&quot;;
	/* end - configure */

	public $connect = null;

	public function ODBConnection();
	public function query($sql);
	public function getTableKey($tbName);
	public function getRelationTableName($tbNameA,$tbNameB);
	public function getLastObject($tbName);
	public function testTable($tbName);
	public function close();	
	public function __destruct();
}
</pre>
		  <h2>Constructor</h2>
			<p>This class should only be build by other objects of the library, but its instantiation and use is not restricted for the programmer. When it is built, opens a connection to the database, given the insertion in the configuration parameters, and closes it when it is destroyed. In case the configuration parameters are incorrect, an exception of the <i>ODBENotConnected</i> type is thrown..</p>

			<dl>
				<dt>Parameters</dt>
					<dd>none</dd>
				<dt>Exceptions</dt>
					<dd>ODBENotConnected: it is thrown  problems with the connection</dd>
			</dl>

		  <h2>Destroyer</h2>
			<p>Close the connection when the object is destroyed.</p>
			<dl>
				<dt>Exceptions</dt>
					<dd>ODBEConnectionNotActive: it is thrown if the connection was previously  closed.</dd>
			</dl>

		  <h2>host</h2>
			<p>The attribute <i>host</i> stores a text string representing the address of the database server to connect. It can be specified by the name of a machine or an IP address.</p>
			<dl>
				<dt>Data type</dt>
					<dd>String</dd>

				<dt>Flags</dt>
					<dd>public</dd>
					<dd>static</dd>
			</dl>
			
			<h2>port</h2>
			<p>Specifies the port on which the database server listens.</p>

			<dl>
				<dt>Data type</dt>
					<dd>Integer</dd>
				<dt>Flags</dt>
					<dd>public</dd>
					<dd>static</dd>

			</dl>

			<h2>dbname</h2>
			<p>Create a string representing the name of the database on the server.</p>
			<dl>
				<dt>Data type</dt>
					<dd>String</dd>

				<dt>Flags</dt>
					<dd>public</dd>
					<dd>static</dd>
			</dl>

			<h2>user</h2>
			<p>The <i>user</i> attribute stores a text string that represents the user name to access the database server.</p>

			<dl>
				<dt>Data type</dt>
					<dd>String</dd>
				<dt>Flags</dt>
					<dd>public</dd>
					<dd>static</dd>

			</dl>	

			<h2>pass</h2>
			<p>Save a string that represents the user password to access the database server.</p>
			<dl>
				<dt>Data type</dt>
					<dd>String</dd>
				<dt>Flags</dt>

					<dd>public</dd>
					<dd>static</dd>
			</dl>
			
		  <h2>connect</h2>
			<p>The <i>connect</i> attribute stores the connection token with the database. This value in normal circumstances should never be used, but is maintained with public visibility to cases where the developer would want to merge <i>objectDB</i> with native functions of the database in which he works.</p>

			<dl>
				<dt>Data type</dt>
					<dd>Integer</dd>
				<dt>Flags</dt>
					<dd>public</dd>
			</dl>
		
		  <h2>query()</h2>

			<p>Make a query to the database, in case of a SELECT query type, it returns an array of objects <i>ODBObject</i>, otherwise returns null. Parameter is passed as a string that represents a valid query in SQL language. If an incorrect query is passed to it, it throws an exception of the <i>ODBEWrongSQLSentence</i> type.</p>
			<dl>
				<dt>Return type</dt>
			  <dd>Array de ODBObject</dd>
				<dt>Parameters</dt>

					<dd>String: a valid query in SQL language.</dd>
				<dt>Exceptions</dt>
					<dd>ODBEWrongSQLSentence: it is thrown if the parameter is not a valid  query.</dd>
			</dl>
			
		  <h2>getTableKey()</h2>
			<p>Returns the key of the table to the table name passed by a parameter</p>

			<dl>
				<dt>Return type</dt>
					<dd>String: name of the field that serves as key of the table.</dd>
				<dt>Parameters</dt>
					<dd>String: name of a table.</dd>
				<dt>Exceptions</dt>

					<dd>none</dd>
			</dl>

		  <h2>getRelationTableName()</h2>
			<p>It returns the name of the table that relates two tables. The names of two tables in the database are passed as parameter to it, and it returns an exception of the <i>ODBETableNotDefined</i> type in case of the relation table for them does not exist.</p>
			<dl>

				<dt>Return type</dt>
					<dd>String: name of the relation table</dd>
				<dt>Parameters</dt>
					<dd>String: name of a table in the database</dd>
					<dd>String: name of another table in the database</dd>
				<dt>Exceptions</dt>

					<dd>ODBETableNotDefined: it is thrown if there is no relation table in  the database</dd>
			</dl>
			
		  <h2>getLastObject()</h2>
			<p>Gets the last saved object in a table. The name of the table is passed as a parameter to get the last saved object. It throws an exception of the <i>ODBETableNotDefined</i> type if the table, which was passed as parameters, does not exist.</p>
			<dl>
				<dt>Return type</dt>

					<dd>ODBObject</dd>
				<dt>Parameters</dt>
					<dd>String: name of a table in the database</dd>
				<dt>Exceptions</dt>
					<dd>ODBETableNotDefined: it is thrown if the table, which was passed by  parameters, does not exist.</dd>
			</dl>

		  <h2>testTable()</h2>
			<p>Test  if a table exists in the database. The name of the table to try is passed as a  parameter.</p>
			<dl>
				<dt>Return type</dt>
					<dd>Boolean: TRUE if the table exists, FALSE otherwise</dd>
				<dt>Parameters</dt>

					<dd>String: name of a table in the database</dd>
				<dt>Exceptions</dt>
					<dd>none</dd>
			</dl>
			
		  <h2>close()</h2>
			<p>It closes a connection to the database. It throws an exception of the <i>ODBEConnectionNotActive</i> type if the connection was closed previously.</p>

			<dl>
				<dt>Return type</dt>
					<dd>none</dd>
				<dt>Parameters</dt>
					<dd>none</dd>
				<dt>Exceptions</dt>

					<dd>ODBEConnectionNotActive: it is thrown if the connection was closed previously.</dd>
			</dl>
		</div>

		{% bottom %}
	</body>
</html>
