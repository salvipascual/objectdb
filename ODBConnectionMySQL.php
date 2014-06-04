<?php

class ODBConnectionMySQL implements IODBConnection {
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
        // connect to database
        $this->connect = @mysql_connect(ODBConnection::$host . ":" . ODBConnection::$port, ODBConnection::$user, ODBConnection::$pass, true);
        if (!$this->connect)
            throw new ODBENotConnected();
        mysql_select_db(ODBConnection::$dbname);

        // create the cache of this db tables
        $this->createCacheTables();

        return $this->connect;
    }

    /*
      name: query
      overview: executs a query, if is a SELECT, returns a list of objects
      params:
      String: a valid SQL query
      returns:
      Array: Persistents objects obtains from database or null
      exceptions:
      ODBEWrongSQLSentence: Raised if the SQL sentence are incorrect
     */

    public function query($sql, $native = false, $asObjects = false, $tbName = null) {
        $result = mysql_query($sql, $this->connect);
        if (!$result)
            throw new ODBEWrongSQLSentence();

        if ($native === true) {
            $arr = array();
            if ($asObjects === true)
                while ($obj = mysql_fetch_object($result))
                    $arr[] = $obj;
            else
                while ($row = mysql_fetch_array($result))
                    $arr[] = $row;
            return $arr;
        }


        $listOfObjs = null;
        if ($result != 1) {
            $tbName = mysql_field_table($result, 0);
            while ($tbElements = mysql_fetch_array($result, MYSQL_ASSOC)) {
                if (class_exists($tbName)) {
                    $object = new $tbName();
                    $tableKey = $object->getTableKeyName(); // obtain key name
                    $object->setId($tbElements[$tableKey]); // add key for new object
                    unset($tbElements[$tableKey]); // delete key if exist
                    $object->setDataFromArray($tbElements);
                    $object->setSaveStatus();
                    $listOfObjs[] = $object;
                } else {
                    $listOfObjs[] = $tbElements;
                }
            }
        }
        return $listOfObjs;
    }

    /*
      Create a chache of tables
     */

    public function createCacheTables() {
        ODBConnection::$tables = array();
        $result = mysql_list_tables(ODBConnection::$dbname, $this->connect);
        for ($i = 0; $i < mysql_num_rows($result); $i++)
            ODBConnection::$tables[] = mysql_tablename($result, $i);
    }

    /*
      Obtain list of fields of specific table
     */

    public function getFieldsOf($table) {
        $result = mysql_list_fields(ODBConnection::$dbname, $table, $this->connect);
        return $result;
    }

    /*
      Return TRUE if exists a field in table
     */

    public function existsField($table, $field, $fl = null) {
        if ($fl == null)
            $fl = $this->getFieldsOf($table);
        foreach ($fl as $f)
            if ($f["Field"] == $field)
                return true;
        return false;
    }


    public function close(){
    	mysql_close($this->connect);
    }
}
?>