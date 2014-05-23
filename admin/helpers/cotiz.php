<?php

/**
 * @package		com_adh
 * @subpackage	helper
 * @brief		User class.  Handles all application interaction with a cotisation
 * @copyright	Copyright (C) 2010 - 2014 DEGENNES Charles-Antoine <cadegenn@gmail.com>
 * @license		Affero GNU General Public License version 3 or later; see LICENSE.txt
 * 
 * @TODO		finish overriding for com_adh component instead of JUser class
 */

/** 
 *  Copyright (C) 2012-2014 DEGENNES Charles-Antoine <cadegenn@gmail.com>
 *  com_adh is a joomla! 2.5 component [http://www.volontairesnature.org]
 *  
 *  This file is part of com_adh.
 * 
 *     com_adh is free software: you can redistribute it and/or modify
 *     it under the terms of the Affero GNU General Public License as published by
 *     the Free Software Foundation, either version 3 of the License, or
 *     (at your option) any later version.
 * 
 *     com_adh is distributed in the hope that it will be useful,
 *     but WITHOUT ANY WARRANTY; without even the implied warranty of
 *     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *     Affero GNU General Public License for more details.
 * 
 *     You should have received a copy of the Affero GNU General Public License
 *     along with com_adh.  If not, see <http://www.gnu.org/licenses/>.
 * 
 */

/**
 * @package     Joomla.Platform
 * @subpackage  User
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_PLATFORM') or die;

/**
 * User class.  Handles all application interaction with a user
 *
 * @package     com_adh
 * @subpackage  User
 * @since       0.0.22
 */
class AdhCotiz extends JObject
{
	/**
	 * Unique id
	 *
	 * @var    integer
	 * @since  11.1
	 */
	public $id = null;

	/**
	 * Constructor activating the default information of the language
	 *
	 * @param   integer  $identifier  The primary key of the user to load (optional).
	 *
	 * @since   11.1
	 */
	public function __construct($identifier = 0)
	{
		// Load the user if it exists
		if (!empty($identifier))
		{
			$this->load($identifier);
		}
		else
		{
			//initialise
			$this->id = 0;
		}
	}

	/**
	 * Method to get the user table object
	 *
	 * This function uses a static variable to store the table name of the user table to
	 * instantiate. You can call this function statically to set the table name if
	 * needed.
	 *
	 * @param   string  $type    The user table name to be used
	 * @param   string  $prefix  The user table prefix to be used
	 *
	 * @return  object  The user table object
	 *
	 * @since   11.1
	 */
	public static function getTable($type = null, $prefix = 'adhTable')
	{
		static $tabletype;

		// Set the default tabletype;
		if (!isset($tabletype))
		{
			$tabletype['name'] = 'cotisation';	// refer to a table *.php file => admin/tables/cotisation.php
			$tabletype['prefix'] = 'adhTable';
		}

		// Set a custom table type is defined
		if (isset($type))
		{
			$tabletype['name'] = $type;
			$tabletype['prefix'] = $prefix;
		}

		// Create the user table object
		return JTable::getInstance($tabletype['name'], $tabletype['prefix']);
	}

	/**
	 * Method to load a JUser object by user id number
	 *
	 * @param   mixed  $id  The user id of the user to load
	 *
	 * @return  boolean  True on success
	 *
	 * @since   11.1
	 */
	public function load($id)
	{
		// Create the user table object
		$table = $this->getTable();

		// Load the JUserModel object based on the user id or throw a warning.
		if (!$table->load($id))
		{
			JError::raiseWarning('SOME_ERROR_CODE', JText::sprintf('JLIB_USER_ERROR_UNABLE_TO_LOAD_USER', $id));
			return false;
		}

		// Assuming all is well at this point lets bind the data
		$this->setProperties($table->getProperties());

		return true;
	}
}