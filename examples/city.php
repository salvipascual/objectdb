<?php

/**
 * Example of ObjectDB Object
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

class city extends ODBObject {

	// @note: default values are important!
	#public $id_type = ODB_IDTYPE_UNIQUEID;
	public $name = ODB_TYPE_STRING;
	public $country = ODB_TYPE_STRING;
	public $population = ODB_TYPE_NUMERIC;
	public $island = ODB_TYPE_BOOLEAN;

	/**
	 * Specific method for this entity
	 * Shows a card of this city
	 */
	public function showCard() {
		echo "<table border = \"1\">
              <tr><td>Name: <b>{$this->name}</b></td></tr>
              <tr><td>Country: <b>{$this->country}</b></td></tr>
              <tr><td>Population: <b>{$this->population}</b></td></tr>
              </table><br>";
	}
}

// End of file
