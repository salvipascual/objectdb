<?php

/*
 * ObjectDB's example
 * @update 2011-03-25
 * @url http://objectdb.salvipascual.com
 */

echo "<h1> ObjectDB 'example </h1>";

// Steps:

// 1: Include ObjectDB

include "objectDB.inc";

// 2: Connect to database
objectDB::connect("mssql://sa:123@rafa-home/test");

// 3: Drop old city table
objectDB::dropIfExists("city");

// 4: Build yor model

class city extends ODBObject {

    // @note: default values are important!
   //	public $id_type = ODB_IDTYPE_UNIQUEID;
    public $name = ODB_TYPE_STRING;
    public $country = ODB_TYPE_STRING;
    public $population = ODB_TYPE_NUMERIC;
    public $island = ODB_TYPE_BOOLEAN;

    public function showCard() {
        echo "<table border = \"1\">
              <tr><td>Name: <b>{$this->name}</b></td></tr>
              <tr><td>Country: <b>{$this->country}</b></td></tr>
              <tr><td>Population: <b>{$this->population}</b></td></tr>
              </table><br>";
    }
}

// 5: Install the table

$city = new city();
$city->install();

// 6: Clean table

objectDB::remove("city");

// 7: Create objects

new city(array("name" => "New York", "country" => "USA", "population" => 1000));
new city(array("name" => "Madrid", "country" => "Spain", "population" => 2000));
new city(array("name" => "Tokio", "country" => "Japan", "population" => 3000));
new city(array("name" => "Paris", "country" => "France", "population" => 4000));

// 8: Load specific objects

$cities = objectDB::getObjs(array(
            'tbName' => 'city',
            'order' => 'population DESC',
            'where' => 'population > 2000'
        ));

// 9: Show objects

foreach ($cities as $city)
    $city->showCard();

// 10: Show stats

echo objectDB::getLengthOf("city", "population > 2000"). " of ". objectDB::getLengthOf("city") . " cities!";

// Thaks you!

?>
