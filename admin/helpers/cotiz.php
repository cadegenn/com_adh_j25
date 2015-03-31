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
jimport('joomla.application.component.helper');

/**
 * define some constants
 */
// endless validity cotisation
define("COTISATION_VALIDITY_ENDLESS", 0);
// cotisation validity from 01 january to 21 decembre of the current year
define("COTISATION_VALIDITY_FROM0101TO3112", 1);
// cotisation validity from the day of registration to 365 days later
define("COTISATION_VALIDITY_1YEARFROMREGISTRATIONDATE", 2);

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
	 * @since  0.0.30
	 */
	public $id = null;

	/**
	 * tarif_id
	 * 
	 * @int
	 * @since	0.0.30
	 */
	public $tarif_id = 0;
	
	/**
	 * tarif
	 * 
	 * @object
	 * @since	0.0.30
	 */
	public $tarif;
	
	/**
	 * @var    array  AdhCotiz instances container.
	 * @since  11.3
	 */
	protected static $instances = array();

	/**
	 * Constructor activating the default information of the language
	 *
	 * @param   integer  $identifier  The primary key of the user to load (optional).
	 *
	 * @since   0.0.30
	 */
	public function __construct($identifier = 0)
	{
		// Load the user if it exists
		if (!empty($identifier))
		{
			$this->load($identifier);
			$this->tarif = $this->getTarif();
		}
		else
		{
			//initialise
			$this->id = 0;
			$this->tarif = new stdClass();
		}
	}

	/**
	 * Returns the global AdhCotiz object, only creating it if it
	 * doesn't already exist.
	 *
	 * @param   integer  $identifier  The cotiz to load
	 *
	 * @return  AdhCotiz  The cotiz object.
	 *
	 * @since   0.0.30
	 */
	public static function getInstance($identifier = 0)
	{
		// Find the user id
		if (!is_numeric($identifier))
		{
			JError::raiseWarning('SOME_ERROR_CODE', JText::sprintf('JLIB_USER_ERROR_ID_NOT_EXISTS', $identifier));
			return false;
		} else {
			$id = $identifier;
		}

		if (empty(self::$instances[$id]))
		{
			$cotiz = new AdhCotiz($id);
			self::$instances[$id] = $cotiz;
		}

		return self::$instances[$id];
	}

	/**
	 * Method to bind an associative array of data to a user object
	 *
	 * @param   array  &$array  The associative array to bind to the object
	 *
	 * @return  boolean  True on success
	 *
	 * @since   0.0.30
	 */
	public function bind(&$array)
	{
		// me
		$my = JFactory::getUser();

		// Bind the array
		if (!$this->setProperties($array))
		{
			$this->setError(JText::_('JLIB_USER_ERROR_BIND_ARRAY'));
			return false;
		}

		// Make sure its an integer
		$this->id = (int) $this->id;

		//var_dump($this) & die();
		
		return true;
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
	 * @since   0.0.30
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
	 * Method to load a AdhCotiz object by cotiz id number
	 *
	 * @param   mixed  $id  The user id of the user to load
	 *
	 * @return  boolean  True on success
	 *
	 * @since   0.0.30
	 */
	public function load($id) {
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
	
	/**
	 * Method to save the AdhCotiz object to the database
	 *
	 * @param   boolean  $updateOnly  Save the object only if not a new user
	 *                                Currently only used in the user reset password method.
	 *
	 * @return  boolean  True on success
	 *
	 * @since   0.0.30
	 * @throws  exception
	 */
	public function save($updateOnly = false)
	{
		// Create the user table object
		$table = $this->getTable();
		$table->bind($this->getProperties());

		// Allow an exception to be thrown.
		try	{
			// Check and store the object.
			if (!$table->check())
			{
				$this->setError($table->getError());
				return false;
			}

			// Store the user data in the database
			if (!($result = $table->store()))
			{
				throw new Exception($table->getError());
			}

			// Set the id for the AdhCotiz object in case we created a new user.
			if (empty($this->id))
			{
				$this->id = $table->get('id');
			}
		} catch (Exception $e) {
			$this->setError($e->getMessage());
			return false;
		}

		return $result;
	}

	/**
	 * Method to delete the AdhCotiz object from the database
	 *
	 * @return  boolean  True on success
	 *
	 * @since   0.0.30
	 */
	public function delete() {
		// Create the user table object
		$table = $this->getTable();

		$result = false;
		if (!$result = $table->delete($this->id))
		{
			$this->setError($table->getError());
		}

		return $result;
	}

	/**
	 * Method to cash the money
	 * 
	 * @return bool true on success
	 * 
	 * @since	0.0.30
	 */
	public function encaisser() {
		$this->payee = 1;
	}

	/**
	 * @brief	getTarif()		set value of $origine
	 * @return	mixed			tarif object on success, false otherwise	
	 * 
	 */
	public function getTarif() {
		if ($this->tarif_id == 0) { return false; }
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*')->from('#__adh_tarifs as t')->where('t.id = '.$this->tarif_id);
		$db->setQuery($query, 0, 1);
		if ($db->execute()) {
			return $db->loadObject();
		} else {
			return false;
		}
	}
	
	/**
	 * Method to move a 'cotisation' from one 'adherent' to another
	 * 
	 * @param	integer		id of target adherent
	 * @return	boolean		true on success | false otherwise
	 */
	public function move($id = 0) {
		if ($id == 0) { return false; }
		$this->adherent_id = $id;
		return $this->save();
	}
	
	/**
	 * Method to search for an exiting cotiz given year and adhId
	 * 
	 * @param integer $adhId	id of adherent
	 * @param integer $year		year of cotisation
	 * @return bool				id of a cotiz found | 0 otherwise
	 * @since	0.0.43
	 */
	public function search($adhId, $year) {
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*')->from('#__adh_cotisations as c')->where('YEAR(c.date_debut_cotiz) = YEAR("'.$year.'-01-01")')->where('c.adherent_id = '.(int)$adhId);
		$db->setQuery($query, 0, 1);
		if ($db->execute()) {
			//echo("<pre>"); var_dump($query); echo("</pre>");
			//echo("<pre>"); var_dump($db->loadObject()); echo("</pre>"); die();
			return $db->loadObject()->id;
		} else {
			return 0;
		}
	}
}
