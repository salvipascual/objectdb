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
<h1>ODBObject</h1>
			<p>The kind shown below defines the object <i>ODBObject</i>, it stores information of a row of the database, and provides the programmer of features that allow it to interact therewith. This class should be inherited to create and increase functionality, and new instances should not be created manually, because the library methods handle them.</p>
<pre class="sh_php">
class ODBObject {
	public function setId($id=null);
	public function setSaveStatus();
	public function setDataFromArray($data);
	public function setDataFromParamsList();
	public function getId();
	public function getState();
	public function getTableName();
	public function getTableKeyName();
	public function isSaved();
	public function save();
	public function remove();
	public function isRelationedWith($object);
	public function relations($tbName);
	public function addRelation($object);
	public function removeRelation($object);
	public function removeAllRelations($tbName);
	public function __toString();
}
</pre>
			<p>The following constants represent states that an object can have.</p>
<pre class="sh_php">
define(&quot;OBJECT_SAVED&quot;, 0x01, true);	// define an ODBObject saved into database
define(&quot;OBJECT_NOT_SAVED&quot;, 0x02, true);	// define an ODBObject not in database
define(&quot;OBJECT_DELETED&quot;, 0x03, true);	// define an ODBObject deleted with remove() function
</pre>

			<h2>setId()</h2>
			<p>I sets the key for a table row, but only if the object has not been saved.</p>
			<dl>
				<dt>Return type</dt>
					<dd>none</dd>

				<dt>Parameters</dt>
					<dd>String: key of the table</dd>
				<dt>Exceptions</dt>
					<dd>ODBECannotChangeKey: it is thrown if intent to change the key of a saved object occurs.</dd>
			</dl>

			
			<h2>setSaveStatus()</h2>

			<p>Sets an object as saved; a saved object cannot be set again. This method is used for internal work of objectDB, and it has only been set as public to have the visibility of the <i>objectDB</i> class. When saving an object using the appropriate functions, the library automatically takes care of marking it, so the programmer is not advised to use it.</p>
			<dl>
				<dt>Return type</dt>
					<dd>none</dd>
				<dt>Parameters</dt>

					<dd>none</dd>
				<dt>Exceptions</dt>
					<dd>none</dd>
			</dl>
			
			
		  <h2>setDataFromArray()</h2>
			<p>Fill an object using an array of information.</p>

			<dl>
				<dt>Return type</dt>
					<dd>none</dd>
				<dt>Parameters</dt>
					<dd>Array; object's values ordered according to the fields of the database.</dd>
				<dt>Exceptions</dt>

					<dd>ODBEObjectNotExist: it is thrown if intention of filling a deleted or nonexistent object occurs.</dd>
			</dl>


		  <h2>setDataFromParamsList()</h2>
			<p>Fill an object with a parameters list.</p>
			<dl>
				<dt>Return type</dt>

					<dd>none</dd>
				<dt>Parameters</dt>
					<dd>As many parameters as columns have the database, without including the key of the table.</dd>
				<dt>Exceptions</dt>
					<dd>ODBEObjectNotExist: it is thrown if a deleted or nonexistent object is filled.</dd>
			</dl>

			
			
		  <h2>getId()</h2>
			<p>It gets the key of the table for this row.</p>
			<dl>
				<dt>Return type</dt>
					<dd>String: the table for this row</dd>
				<dt>Parameters</dt>

					<dd>none</dd>
				<dt>Exceptions</dt>
					<dd>none</dd>
			</dl>	
			
			
		  <h2>getState()</h2>
			<p>It gets the state of an object.</p>
			<dl>

				<dt>Return type</dt>
					<dd>Integer: It will be the following constants(OBJECT_SAVED, OBJECT_NOT_SAVED, OBJECT_DELETED).</dd>
				<dt>Parameters</dt>
					<dd>none</dd>
				<dt>Exceptions</dt>
					<dd>none</dd>
			</dl>	

			
		  <h2>getTableName()</h2>
			<p>It returns the name of the table which stored this object.</p>
			<dl>
				<dt>Return type</dt>
					<dd>String: name of the table</dd>
				<dt>Parameters</dt>

					<dd>none</dd>
				<dt>Exceptions</dt>
					<dd>none</dd>
			</dl>
			
			
		  <h2>getTableKeyName()</h2>
			<p>It returns the name of the field that represents the key of the table.</p>

			<dl>
				<dt>Return type</dt>
					<dd>String: name of key field in the table</dd>
				<dt>Parameters</dt>
					<dd>none</dd>
				<dt>Exceptions</dt>

					<dd>none</dd>
			</dl>
			
			
		  <h2>isSaved()</h2>
			<p>It checks if the object is in the database.</p>
			<dl>
				<dt>Return type</dt>
					<dd>Boolean: TRUE if the object is in the database, FALSE otherwise</dd>

				<dt>Parameters</dt>
					<dd>none</dd>
				<dt>Exceptions</dt>
					<dd>none</dd>
			</dl>	
			
			
		  <h2>save()</h2>
			<p>If the object has just been created, it stores this object in the database, if the object was already in the database and was modified, it is updated.</p>

			<dl>
				<dt>Return type</dt>
					<dd>none</dd>
				<dt>Parameters</dt>
					<dd>none</dd>
				<dt>Exceptions</dt>

					<dd>ODBEObjectNotExist: it is thrown if the object has been deleted or does not exist.</dd>
			</dl>	

			
		  <h2>remove()</h2>
			<p>It deletes an object from the database permanently.</p>
			<dl>
				<dt>Return type</dt>
					<dd>Boolean: TRUE if the object was removed, an exception is thrown otherwise.</dd>

				<dt>Parameters</dt>
					<dd>none</dd>
				<dt>Exceptions</dt>
					<dd>
						ODBEObjectNotInDatabase: it is thrown when the object is not in the database.<br />
						ODBEObjectNotExist: it is thrown when the object was removed or does not exist.</dd>
			</dl>		

			
		  <h2>isRelationedWith()</h2>

			<p>It checks if there is relationship between two objects.</p>
			<dl>
				<dt>Return type</dt>
					<dd>Boolean: TRUE if a relationship exists, FALSE otherwise</dd>
				<dt>Parameters</dt>
					<dd>ODBObject: An object with which to test whether a relationship exists.</dd>

				<dt>Exceptions</dt>
					<dd>none</dd>
			</dl>		


		  <h2>relations()</h2>
			<p>It gets the relationships that an object has with another.</p>
			<dl>
				<dt>Return type</dt>

					<dd>Array: list of objects associated with this</dd>
				<dt>Parameters</dt>
					<dd>String: name of the table with which relations will be obtained</dd>
				<dt>Exceptions</dt>
					<dd>
						ODBEObjectNotInDatabase: it is thrown whether this object does not exist in the database<br />
						ODBETableNotDefined: it is thrown if the relations table does not exist <br />
						ODBEObjectNotExist: it is thrown when the object was removed or does not exist					</dd>
			</dl>	


		  <h2>addRelation()</h2>
			<p>It defines a relationship between two objects</p>
			<dl>
				<dt>Return type</dt>
					<dd>none</dd>
				<dt>Parameters</dt>

					<dd>ODBObject: the object with which to define the relationship</dd>
				<dt>Exceptions</dt>
					<dd>
						ODBEObjectNotInDatabase: it is thrown whether this object does not exist in the database <br />
						ODBETableNotDefined: it is thrown if the relations table does not exist <br />
						ODBEObjectNotExist: it is thrown if the object was removed or does not exist <br />
						ODBERelationAlreadyExist: it is thrown if the relationship was previously defined					</dd>
			</dl>		


		  <h2>removeRelation()</h2>
			<p>It deletes a relationship between two objects.</p>

			<dl>
				<dt>Return type</dt>
					<dd>none</dd>
				<dt>Parameters</dt>
					<dd>ODBObject: Object with which to delete the relationship</dd>
				<dt>Exceptions</dt>

					<dd>
						ODBEObjectNotInDatabase: it is thrown whether this object does not exist in the database <br />
						ODBETableNotDefined: it is thrown if the relations table does not exist <br />
						ODBEObjectNotExist: it is thrown if the object was removed or does not exist <br />
						ODBENoRelationBetween: it is thrown in the absence of a relationship					</dd>
			</dl>	


		  <h2>removeAllRelations()</h2>
			<p>It removes all relationships that may have an object with a given table.</p>
			<dl>
				<dt>Return type</dt>

					<dd>none</dd>
				<dt>Parameters</dt>
					<dd>String: name of the table with which to remove all relationships</dd>
				<dt>Exceptions</dt>
					<dd>
						ODBEObjectNotInDatabase: it is thrown whether this object does not exist in the database <br />
						ODBETableNotDefined: it is thrown if the relations table does not exist <br />
						ODBEObjectNotExist: it is thrown if the object was removed or does not exist					</dd>
			</dl>
			
			
		  <h2>__toString()</h2>
			<p>This method generates a string with information about the content of the class. It can literally be called by the expression <i>$obj-&gt;__toString();</i>, but it is ready to run treating the object as a string, making it easier to call it with expressions such as: <i>echo $obj;</i> or <i>throw $obj;</i>.</p>
			<dl>

				<dt>Return type</dt>
					<dd>String</dd>
				<dt>Parameters</dt>
					<dd>none</dd>
				<dt>Exceptions</dt>
					<dd>none</dd>
			</dl>
		</div>

		{% bottom %}
	</body>
</html>
