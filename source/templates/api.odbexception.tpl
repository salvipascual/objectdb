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
			<h1>ODBException</h1>
			<p>The class shown below defines the <i>ODBException</i> object; it creates a basis for working with typical exceptions of <i>ObjectDB</i>.</p>
<pre class="sh_php">
class ODBException extends Exception {
	public $message;

	function ODBException($message=null);
	public function __toString();
}
</pre>
			<p>The exception classes listed below inherit from <i>ODBException</i> object. These define <i>ObjectDB</i> communication with the programmer, and rise in specific cases, explained in later sections. If <i>ObjectDB</i>is changed, it is a mistake to throw objects of the <i>ODBException</i> type, instead of this, developers must create their own exceptions that inherit from <i>ODBException</i>.</p>

<pre class="sh_php">
class ODBENotConnected 		extends ODBException{}	// Raised if are problem with database connection
class ODBEConnectionNotActive 	extends ODBException{}	// Raised if connection closed previously
class ODBEObjectNotInDatabase	extends ODBException{} 	// Raised if object to treat not exist in database
class ODBEWrongSQLSentence 	extends ODBException{} 	// Raised if the SQL sentence are incorrect
class ODBETableNotDefined 	extends ODBException{} 	// Raised if try to use not defined table 
class ODBEClassNotDefined 	extends ODBException{}	// Raised if try to use not defined class
class ODBECannotChangeKey 	extends ODBException{}	// Raised if try to change the key for one row
class ODBEObjectNotExist 	extends ODBException{}	// Raised if try to use a deleted or inexistent object 
class ODBEMoreThanOneInstance 	extends ODBException{}	// Raised if instanciate a singletton class more than once
class ODBERelationAlreadyExist 	extends ODBException{}	// Raised if try to set a relation previously defined
class ODBENoRelationBetween 	extends ODBException{}	// Raised if try to delete an unexisting relation
</pre>


			<h2>Constructor</h2>
			<p>This class should only be constructed by others who inherit it. A text string with a personalized message, which will be shown by throwing the exception, must be passed as parameter to the constructor.  If any message is not passed, throwing the exception will show the default error returned by the database.</p>
			<dl>
				<dt>Parameters</dt>
					<dd>String | none: message to display when the exception is thrown.</dd>

				<dt>Exceptions</dt>
					<dd>none</dd>
			</dl>

			<h2>message</h2>
			<p>The <i>message</i> attribute stores a text string that contains a custom text to be shown by throwing the exception. This attribute is public, which can be modified or queried at any time after being created the object, but for convenience it can also be initialized in the constructor of the class.</p>

			<dl>
				<dt>Data type</dt>
					<dd>String</dd>
				<dt>Flags</dt>
					<dd>public</dd>
			</dl>

			
		  <h2>__toString()</h2>
			<p>This method generates a string with information about the content of the class. It can literally be called by the expression <i>$obj-&gt;__toString();</i>, but it is ready to run treating the object as a string, making it easier to call it by means of expressions such as: <i>echo $obj; o </i> <i>throw $obj;</i>.</p>
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
