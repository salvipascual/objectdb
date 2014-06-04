<?php

/**
 * All required files to use ObjectDB
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

// Constants 
if (!defined("ODB_ROOT_DIR")) define("ODB_ROOT_DIR", "./"); 

// Include
require_once ODB_ROOT_DIR."lib/IODBConnection.php";
require_once ODB_ROOT_DIR."drivers/ODBConnectionPgSQL.php";
require_once ODB_ROOT_DIR."drivers/ODBConnectionMySQL.php";
require_once ODB_ROOT_DIR."drivers/ODBConnectionMSSQL.php";
require_once ODB_ROOT_DIR."lib/ODBException.php";
require_once ODB_ROOT_DIR."lib/ODBConnection.php";
require_once ODB_ROOT_DIR."lib/ODBObject.php";
require_once ODB_ROOT_DIR."lib/objectDB.php";

// End of file