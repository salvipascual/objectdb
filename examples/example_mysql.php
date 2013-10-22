<?php

/*
 * ObjectDB example for MySQL Server
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY
 * or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License
 * for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program as the file LICENSE.txt; if not, please see
 * http://www.gnu.org/licenses/old-licenses/gpl-2.0.txt.
 *
 * @author Salvi Pascual <salvi.pascual@pragres.com>
 * @version 4.1
 * @website http://objectdb.salvipascual.com
 */


 echo "<h1>ObjectDB example for MySQL Server</h1>";

// Steps:

// 0: Define the ObjectDB root dir

define("ODB_ROOT_DIR", "../");

// 1: Include ObjectDB

include ODB_ROOT_DIR.'objectDB.inc';

// 2: Connect to database (MODIFY PARAMS IN THE STRING FIRST!)

$status = objectDB::connect("mysql://username:password@localhost/dbname");

// 3: Drop old city table

objectDB::dropIfExists("city"); // Optional

// 4: Build yor model

include_once ODB_ROOT_DIR."examples/city.php";

// 5: Install the table

$city = new city();
$city->install(); // Optional: This a one-time operation

// 6: Clean table

objectDB::remove("city"); // Optional

// 7: Create objects

new city(array("name" => "New York", "country" => "USA", "population" => 1000));
new city(array("name" => "Madrid", "country" => "Spain", "population" => 2000));
new city(array("name" => "Tokio", "country" => "Japan", "population" => 3000));
new city(array("name" => "Paris", "country" => "France", "population" => 4000));
new city(array("name" => "Quevec", "country" => "Canada", "population" => 2500));

// 8: Load specific objects

$cities = objectDB::getObjs(array(
            'tbName' => 'city',
            'order' => 'population DESC',
            'where' => 'population > 2000'));

// 9: Show objects

foreach ($cities as $city) $city->showCard();

// 10: Show stats

echo objectDB::getLengthOf("city", "population > 2000"). " of ". objectDB::getLengthOf("city") . " cities!";

// Thaks you!

// End of file