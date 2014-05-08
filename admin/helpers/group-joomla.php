<?php

/**
 * @package		com_adh
 * @subpackage	helper
 * @brief		Group class.  Handles all application interaction with a Joomla! group
 * @copyright	Copyright (C) 2010 - 2014 DEGENNES Charles-Antoine <cadegenn@gmail.com>
 * @license		Affero GNU General Public License version 3 or later; see LICENSE.txt
 * 
 * @TODO		
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

defined('JPATH_PLATFORM') or die;

class JGroup extends JObject {
	/**
	 * Unique id
	 *
	 * @var    integer
	 * @since  0.0.22
 	 */
	public $id = null;

	/**
     * The group's title
     * @var    string
     * @since  0.0.22
     */
    public $title = null;
	
	/**
     * Array of ids of users that belongs to this group
     *
     * @var    array
     * @since  0.0.22
     */
    public $users = array();
	
	/**
	 * Constructor activating the default information of the language
	 *
	 * @param   integer  $identifier  The primary key of the user to load (optional).
	 *
	 * @since   11.1
	 */
	public function __construct($identifier = 0) {
		parent::__construct();
		// Load the group if it exists
		if (!empty($identifier)) {
				$this->load($identifier);
		} else {
			//initialise
			$this->id = 0;
			$this->title = "";
			$this->users = array();
		}
	}

	/**
	 * Returns the global Group object, only creating it if it
	 * doesn't already exist.
	 *
	 * @param   integer  $identifier  The group to load - Can be an integer or string - If string, it is converted to ID automatically.
	 *
	 * @return  JGroup  The Group object.
	 *
	 * @since   0.0.22
	 */
	public static function getInstance($identifier = 0)
	{
		// Find the user id
		if (!is_numeric($identifier)) {
			if (!$id = JGroupHelper::getGroupId($identifier)) {
				JError::raiseWarning('SOME_ERROR_CODE', JText::sprintf('JLIB_GROUP_ERROR_ID_NOT_EXISTS', $identifier));
				$retval = false;
				return $retval;
			}
		} else {
			$id = $identifier;
		}

		if (empty(self::$instances[$id])){
			$group = new JGroup($id);
			self::$instances[$id] = $group;
		}

		return self::$instances[$id];
	}

	/**
	 * Method to load a JGroup object by group id number
	 *
	 * @param   mixed  $id  The group id of the group to load
	 *
	 * @return  boolean  True on success
	 *
	 * @since   0.0.22
	 */
	public function load($id)
	{
		// Create the user table object
		$table = $this->getTable();

		// Load the JUserModel object based on the user id or throw a warning.
		if (!$table->load($id)) {
			JError::raiseWarning('SOME_ERROR_CODE', JText::sprintf('JLIB_USER_ERROR_UNABLE_TO_LOAD_USER', $id));
			return false;
		}

		// Assuming all is well at this point lets bind the data
		$this->setProperties($table->getProperties());
		$this->getUsers();
		
		return true;
	}

	/**
	 * Method to get the group table object
	 *
	 * This function uses a static variable to store the table name of the group table to
	 * instantiate. You can call this function statically to set the table name if
	 * needed.
	 *
	 * @param   string  $type    The user table name to be used
	 * @param   string  $prefix  The user table prefix to be used
	 *
	 * @return  object  The user table object
	 *
	 * @since   0.0.22
	 */
	public static function getTable($type = null, $prefix = 'JTable') {
		static $tabletype;

		// Set the default tabletype;
		if (!isset($tabletype)) {
			$tabletype['name'] = 'usergroup';	// refer to a table *.php file => libraries/joomla/database/table/usergroup.php
			$tabletype['prefix'] = 'JTable';
		}

		// Set a custom table type is defined
		if (isset($type)) {
			$tabletype['name'] = $type;
			$tabletype['prefix'] = $prefix;
		}

		// Create the user table object
		return JTable::getInstance($tabletype['name'], $tabletype['prefix']);
	}

	/**
	 * Method to get list of users from group
	 */
	public function getUsers() {
		if ($this->users === null)
		{
			$this->users = array();
		}

		if (empty($this->users))
		{
			$this->users = JAccess::getUsersByGroup($this->id, false);
		}

		return $this->users;
	}
}