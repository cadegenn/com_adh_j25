<?php

/**
 * @package		com_adh
 * @subpackage	helper
 * @brief		User class.  Handles all application interaction with a user
 * @copyright	Copyright (C) 2010 - 2014 DEGENNES Charles-Antoine <cadegenn@gmail.com>
 * @license		Affero GNU General Public License version 3 or later; see LICENSE.txt
 * 
 * @TODO		finish overriding for com_adh component instead of AdhUser class
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

//JLoader::register('AdhUserHelper', dirname(__FILE__) . '/user-adh-helper.php');
JLoader::register('AdhCotiz', dirname(__FILE__) . DS . 'cotiz.php');

/**
 * User class.  Handles all application interaction with a user
 *
 * @package     com_adh
 * @subpackage  User
 * @since       0.0.22
 */
class AdhUser extends JObject
{
	/**
	 * Joomla user object (JUser)
	 *
	 * @object	JUser
	 * @since	0.0.20
	 */
	protected $juser = null;

	/**
	 * Unique id
	 *
	 * @var    integer
	 * @since  11.1
	 */
	public $id = null;

	/**
	 * profession
	 * 
	 * @object
	 * @since	0.0.22	
	 */
	public $profession;
	
	/**
	 * origine
	 * 
	 * @object
	 * @since	0.0.22	
	 */
	public $origine;
	
	/**
	 * cotiz
	 * 
	 * @array of @object
	 * @since	0.0.30
	 */
	public $cotiz;
	
	/**
	 * @var    array  AdhUser instances container.
	 * @since  11.3
	 */
	protected static $instances = array();

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
			$this->profession = $this->getProfession();
			$this->origine = $this->getOrigine();
			$this->cotiz = $this->getCotiz();

		}
		else
		{
			//initialise
			$this->id = 0;
			$this->profession = new stdClass();
			$this->profession_id = 0;
			$this->origine = new stdClass();
			$this->origine_id = 0;
			$this->cotiz = array();
		}
	}

	/**
	 * Returns the global AdhUser object, only creating it if it
	 * doesn't already exist.
	 *
	 * @param   integer  $identifier  The user to load - Can be an integer or string - If string, it is converted to ID automatically.
	 *
	 * @return  AdhUser  The User object.
	 *
	 * @since   11.1
	 */
	public static function getInstance($identifier = 0)
	{
		// Find the user id
		if (!is_numeric($identifier))
		{
			if (!$id = AdhUserHelper::getUserId($identifier))
			{
				JError::raiseWarning('SOME_ERROR_CODE', JText::sprintf('JLIB_USER_ERROR_ID_NOT_EXISTS', $identifier));
				$retval = false;
				return $retval;
			}
		}
		else
		{
			$id = $identifier;
		}

		if (empty(self::$instances[$id]))
		{
			$user = new AdhUser($id);
			self::$instances[$id] = $user;
		}

		return self::$instances[$id];
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
		JTable::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR . DS . "tables");
		static $tabletype;

		// Set the default tabletype;
		if (!isset($tabletype))
		{
			$tabletype['name'] = 'adherent';	// refer to a table *.php file => joomla/search/path/adherent.php
			$tabletype['prefix'] = 'adhTable';	// refer to a table *.php file => search for adhTableAdherent class
		}

		// Set a custom table type is defined
		if (isset($type))
		{
			$tabletype['name'] = $type;
			$tabletype['prefix'] = $prefix;
		}
		//if (DEBUG) {
		//	echo("<pre>"); var_dump(JTable::getInstance($tabletype['name'], $tabletype['prefix'])); echo("</pre>");
		//}
		// Create the user table object
		return JTable::getInstance($tabletype['name'], $tabletype['prefix']);
	}

	/**
	 * Method to save the AdhUser object to the database
	 *
	 * @param   boolean  $updateOnly  Save the object only if not a new user
	 *                                Currently only used in the user reset password method.
	 *
	 * @return  boolean  True on success
	 *
	 * @since   11.1
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

			// Set the id for the AdhUser object in case we created a new user.
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
	 * Method to delete the AdhUser object from the database
	 *
	 * @return  boolean  True on success
	 *
	 * @since   11.1
	 */
	public function delete()
	{
		//JPluginHelper::importPlugin('user');

		// Trigger the onUserBeforeDelete event
		//$dispatcher = JDispatcher::getInstance();
		//$dispatcher->trigger('onUserBeforeDelete', array($this->getProperties()));
		
		// @TODO: delete all the cotisations of this user
		foreach ($this->cotiz as $cotiz) {
			$cotiz->delete();
		}
		// Create the user table object
		$table = $this->getTable();

		$result = false;
		if (!$result = $table->delete($this->id))
		{
			$this->setError($table->getError());
		}

		// Trigger the onUserAfterDelete event
		//$dispatcher->trigger('onUserAfterDelete', array($this->getProperties(), $result, $this->getError()));

		return $result;
	}

	/**
	 * Method to load a AdhUser object by user id number
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

		// Load the AdhUserModel object based on the user id or throw a warning.
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
	 * @brief	getProfession()		set value of $profession
	 * 
	 */
	public function getProfession() {
		if (!$this->profession_id) { $this->profession_id = 0; }
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*')->from('#__adh_professions as p')->where('p.id = '.$this->profession_id);
		$db->setQuery($query, 0, 1);
		if ($db->execute()) {
			return $db->loadObject();
		} else {
			return false;
		}
	}
	
	/**
	 * @brief	getOrigine()		set value of $origine
	 * 
	 */
	public function getOrigine() {
		if (!$this->origine_id) { $this->origine_id = 0; }
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*')->from('#__adh_origines as o')->where('o.id = '.$this->origine_id);
		$db->setQuery($query, 0, 1);
		if ($db->execute()) {
			return $db->loadObject();
		} else {
			return false;
		}
	}
	
	/**
	 * @brief	getCotiz()		set value of $origine
	 * 
	 */
	public function getCotiz() {
		if (!isset($this->id)) return false;
		$array = array();
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('id, date_debut_cotiz')->from('#__adh_cotisations as c')->where('c.adherent_id = '.$this->id)->order('YEAR(date_debut_cotiz) DESC');
		$db->setQuery($query, 0, 0);
		$db->execute();
		if ($db->execute()) {
			$cotiz_list = $db->loadObjectList();
			foreach ($cotiz_list as $cotiz) {
				$array[] = new AdhCotiz($cotiz->id);
			}
			return $array;
		} else {
			return false;
		}
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
		/*// me
		$my = JFactory::getUser();
		// Let's check to see if the user is new or not
		if (empty($this->id))
		{
			$this->set('creation_date', JFactory::getDate()->toSql());
			$this->set('created_by', $my->id);
		}
		else
		{
			$this->set('modification_date', JFactory::getDate()->toSql());
			$this->set('modified_by', $my->id);
		}
		*/
		// Bind the array
		if (!$this->setProperties($array))
		{
			$this->setError(JText::_('JLIB_USER_ERROR_BIND_ARRAY'));
			return false;
		}

		// Make sure its an integer
		$this->id = (int) $this->id;

		$this->profession = $this->getProfession();
		$this->origine = $this->getOrigine();
		$this->cotiz = $this->getCotiz();

		return true;
	}

	/**
	 * Method to search for an exiting adherent
	 * 
	 * @param integer $adhId	id of adherent
	 * @param bool	  $replace	true: load user into current object | false: do nothing
	 * @return bool				id of an adherent found | 0 otherwise
	 * @since	0.0.43
	 */
	public function search($replace = false) {
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('id')->from('#__adh_adherents')->where('UPPER(nom) = UPPER("'.$this->nom.'") AND UPPER(prenom) = UPPER("'.$this->prenom.'") AND UPPER(email) = UPPER("'.$this->email.'")');// AND date_naissance = "'.$adherent->date_naissance.'"');
		$db->setQuery($query, 0, 1);
		if ($db->execute()) {
			// if no user found in database, return 0
			if ($db->getNumRows() == 0) { return 0; }
			// if a user is found, get it
			$found = $db->loadObject();
			if ($replace) {
				$this->load($found->id);
				$this->bind($found);
			}
			return $found->id;
		} else {
			return 0;
		}
	}
}
