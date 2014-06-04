<?php
/**
  * @IODBConnection
  * ObjectDB Connection Interface
  * @updated 24/03/2011
  */
interface IODBConnection {
  public function __construct();
  public function query($sql, $native = false, $asObjects = false);
  public function createCacheTables();
  public function getFieldsOf($table);
  public function existsField($table, $field, $fl = null);
}
?>