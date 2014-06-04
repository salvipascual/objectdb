<?php

/**
 * ObjectDB Connection Interface
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
 * @link http://objectdb.pragres.com
 */

interface IODBConnection {
	
	/**
	 * Constructor
	 *
	 */
	public function __construct();
	
	/**
	 * Execute a SQL query
	 *
	 * @param string $sql - A valid SQL sentence
	 * @param boolean $native - Turn on/off to return a list of ODBObject or native PHP function's results
	 * @param boolean $asObjects - Return a list of objects or a list of arrays
	 */
	public function query($sql, $native = false, $asObjects = false);
	
	/**
	 * Create cache tables
	 *
	 */
	public function createCacheTables();
	
	/**
	 * Return the list of fields
	 *
	 * @param string $table - The name of the table
	 */
	public function getFieldsOf($table);
	
	/**
	 * Return TRUE if field exists
	 *
	 * @param string $table - The name of the table
	 * @param string $field - The name of the field
	 * @param array $fl - The list of fields loaded previusly
	 */
	public function existsField($table, $field, $fl = null);
}

// End of file
// End of file