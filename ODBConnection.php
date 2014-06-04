<?php

class ODBConnection {

    static public $dbtype = null;
    static public $host = null;
    static public $port = null;
    static public $dbname = null;
    static public $user = null;
    static public $pass = null;
    static public $tables = null; /* cache list of this db tables */
    public $driver = null;

    /*
      name: ODBConnection
      overview: start a connection when object create
      params:
      none
      returns:
      Integer: a conection to the database
      exceptions:
      ODBENotConnected: Raised if are problem with connection
     */

    public function __construct() {
        switch (ODBConnection::$dbtype) {
            case "mysql":
                $this->driver = new ODBConnectionMySQL();
                break;
            case "pgsql":
                $this->driver = new ODBConnectionPgSQL();
                break;
            case "mssql":
                $this->driver = new ODBConnectionMSSQL();
                break;
        }
        return $this->driver->connect;
    }
    

    public function query($sql, $native = false, $asObjects = false, $tbName = null) {
        return $this->driver->query($sql, $native, $asObjects, $tbName);
    }

    /*
      Return TRUE if exists a field in table
     */

    public function existsField($table, $field, $fl = null) {
        return $this->driver->existsField($table, $field, $fl);
    }

    /*
      Obtain list of fields of specific table
     */

    public function getFieldsOf($table) {
        return $this->driver->getFieldsOf($table);
    }

    /*
      name: getTableKey
      overview: returns the table key for an ODBObject
      params:
      String: name of the table to obtain id
      returns:
      String: name of the table key
      exceptions:
      none
     */

    public function getTableKey($tbName) {
        return "id_" . $tbName;
    }

    /*
      name: getRelationTableName
      overview: returns the name of a relation table
      params:
      String: name of the table
      String: name of the other table
      returns:
      String: name of the relation table or exception if no relations between
      exceptions:
      ODBETableNotDefined: Raised if relation table was not defined
     */

    public function getRelationTableName($tableA, $tableB) {
        foreach (ODBConnection::$tables as $table) {
            $one = "relation_" . $tableA . "_" . $tableB;
            $two = "relation_" . $tableB . "_" . $tableA;
            if ($one == $table)
                return $one;
            if ($two == $table)
                return $two;
        }
        throw new ODBETableNotDefined("Relation between $tableA and $tableB not exist or relation table was not defined");
    }

    /*
      name: getLastObject
      overview: obtain the latest object saved in a table
      params:
      String: table name for obtain latest saved object
      returns:
      Object: Latest saved object
      exceptions:
      ODBETableNotDefined: Raised if try to use not defined table
     */

    public function getLastObject($tbName) {
        if (!ODBConnection::testTable($tbName))
            throw new ODBETableNotDefined();

        $tbId = $this->getTableKey($tbName);
        
        if (ODBConnection::$dbtype == 'mssql')
        	$results = $this->query("SELECT TOP 1 * FROM $tbName ORDER BY $tbId DESC ", false, false, $tbName);
        else
        	$results = $this->query("SELECT * FROM $tbName ORDER BY $tbId DESC LIMIT 1", false, false, $tbName);
        
        return $results[0];
    }

    /*
      name: testTable
      overview: test if a table exist
      params:
      String: table name for test
      returns:
      Boolean: true if table defined, false not defined
      exceptions:
      none
     */

    static function testTable($tbName) {
        if (is_array(ODBConnection::$tables))
            foreach (ODBConnection::$tables as $table)
                if ($tbName == $table){
                    return true;
                }
        return false;
    }

    /*
      name: close
      overview: finish this connection with database
      params:
      none
      returns:
      none
      exceptions:
      ODBEConnectionNotActive: Raised if connection closed previously
     */

    public function close() {
        if ($this->driver->connect) {
            if (is_resource($this->driver->connect))
                $this->driver->close();
        }
        else
            throw new ODBEConnectionNotActive();
    }

    /*
      name: __destruct
      overview: finish this connection when object was destroy
      params:
      none
      returns:
      none
      exceptions:
      ODBEConnectionNotActive: Raised if connection closed previously
     */

    public function __destruct() {
        $this->close();
    }

}
?>