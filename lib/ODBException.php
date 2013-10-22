<?php

/**
 * Object DB Base exception class, please, do not use
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

class ODBException extends Exception {

	public $message;

	function ODBException($message=null) {
		$this->message = $message ? $message : mysql_error();
	}

	public function __toString() {
		return 'Raised by ObjectDB => ' . get_class($this) . ': "' . $this->message . '"';
	}

}

/**
 * List of aviable exceptions
 *
 */

class ODBENotConnected extends ODBException {}
class ODBEConnectionNotActive extends ODBException {/* Raised if are problem with database connection */}
class ODBEObjectNotInDatabase extends ODBException {/* Raised if connection closed previously */}
class ODBEWrongSQLSentence extends ODBException {/* Raised if object to treat not exist in database */}
class ODBETableNotDefined extends ODBException {/* Raised if the SQL sentence are incorrect */}
class ODBEClassNotDefined extends ODBException {/* Raised if try to use not defined table */}
class ODBECannotChangeKey extends ODBException {/* Raised if try to use not defined class */}
class ODBEObjectNotExist extends ODBException {/* Raised if try to change the key for one row */}
class ODBEMoreThanOneInstance extends ODBException {/* Raised if try to use a deleted or inexistent object */}
class ODBENoURLSupliedForConnect extends ODBException {/* Raised if try to connect whithout url */}

// End of file