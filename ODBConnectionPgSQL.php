<?php

class ODBConnectionPgSQL implements IODBConnection {
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

    public $connect = null;

    public function __construct() {
        // connect to database
        $this->connect = @pg_connect("host = " . ODBConnection::$host . " port = " . ODBConnection::$port . " user = " . ODBConnection::$user . " password = " . ODBConnection::$pass . " dbname = " . ODBConnection::$dbname);
        if (!$this->connect)
            throw new ODBENotConnected();

        // create the cache of this db tables

        $this->createCacheTables();

        return $this->connect;
    }

    /*
      name: query
      overview: executs a query, if is a SELECT, returns a list of objects
      params:
      String: a valid SQL query
      Boolean: result form
      returns:
      Array: Persistents objects obtains from database or null
      exceptions:
      ODBEWrongSQLSentence: Raised if the SQL sentence are incorrect
     */

    public function query($sql, $native = false, $asObjects = false, $tbName = null) {

        $result = pg_query($this->connect, $sql);

        if (!$result)
            throw new ODBEWrongSQLSentence();

        if ($native === true) {
            $arr = array();
            if ($asObjects === true)
                while ($obj = pg_fetch_object($result))
                    $arr[] = $obj;
            else
                $arr = pg_fetch_all($result);
            return $arr;
        }

        $listOfObjs = null;
        if ($result != 1) {
            if (pg_num_fields($result) > 0) {
                $tbName = pg_field_table($result, 0);
                while ($tbElement = pg_fetch_assoc($result)) {
                    if (class_exists($tbName)) {
                        $object = new $tbName();

                        $tableKey = $object->getTableKeyName(); // obtain key name
                        if (isset($tbElement[$tableKey])) {
                            $object->setId($tbElement[$tableKey]); // add key for new object

                            if (is_numeric($tbElement[$tableKey]))
                                $object->setIdType(ODB_IDTYPE_AUTOINCREMENT);
                            elseif (is_string($tbElement[$tableKey]))
                                $object->setIdType(ODB_IDTYPE_UNIQUEID);

                            unset($tbElement[$tableKey]); // delete key if exist
                        }
                        $object->setDataFromArray($tbElement);
                        $object->setSaveStatus();

                        $listOfObjs[] = $object;
                    } else {
                        $listOfObjs[] = $tbElement;
                    }
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
        $tables = @pg_fetch_all(pg_query($this->connect, "select * from information_schema.tables where table_type='BASE TABLE' and table_schema<>'pg_catalog' AND table_schema<>'information_schema' order by table_schema"));
        if (is_array($tables))
            foreach ($tables as $table) {
                if ($table['table_schema'] != 'public')
                    ODBConnection::$tables[] = $table['table_schema'] . "_" . $table['table_name'];
                else
                    ODBConnection::$tables[] = $table['table_name'];
            }
    }

    /*
      Obtain list of fields of specific table
     */

    public function getFieldsOf($table) {
        $arr = explode(".", $table);
        if (count($arr) == 2) {
            $table_schema = $arr[0];
            $table_name = $arr[1];
        } else {
            $table_schema = "public";
            $table_name = $table;
        }
        return @pg_fetch_all(pg_query($this->connect, "select * from information_schema.columns where table_schema='$table_schema' and table_name='$table_name';"));
    }

    /*
      Return TRUE if exists a field in table
     */

    public function existsField($table, $field, $fl = null) {

        if ($fl == null)
            $fl = $this->getFieldsOf($table);

        $fl = $this->getFieldsOf($table);

        if (is_array($fl))
            foreach ($fl as $f)
                if ($f['column_name'] == $field)
                    return true;
        return false;
    }


    public function close(){
    	pg_close($this->connect);
    }
}
?>