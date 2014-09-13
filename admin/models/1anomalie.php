<?php
/**
 * @package		com_adh
 * @subpackage	
 * @brief		com_adh helps you manage the people within an association
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

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla modelform library
jimport('joomla.application.component.modeladmin');
JLoader::register('AdhUser', JPATH_COMPONENT_ADMINISTRATOR . '/helpers/user-adh.php');
JLoader::register('AdhCotiz', JPATH_COMPONENT_ADMINISTRATOR . '/helpers/cotiz.php');

/**
 * 1anomalie Model
 */
class adhModel1anomalie extends JModelAdmin
{
	/**
	 * Returns a reference to the a Table object, always creating it.
	 *
	 * @param	type	The table type to instantiate
	 * @param	string	A prefix for the table class name. Optional.
	 * @param	array	Configuration array for model. Optional.
	 * @return	JTable	A database object
	 * @since	2.5
	 */
	public function getTable($type = 'adherent', $prefix = 'adhTable', $config = array()) 
	{
		return JTable::getInstance($type, $prefix, $config);
	}
	
	
	/**
	 * Method to get the record form.
	 *
	 * @param	array	$data		Data for the form.
	 * @param	boolean	$loadData	True if the form is to load its own data (default case), false if not.
	 * @return	mixed	A JForm object on success, false on failure
	 * @since	2.5
	 */
	public function getForm($data = array(), $loadData = true) 
	{
		// Get the form.
		$form = $this->loadForm('com_adh.adherent', 'adherent' /* --> models/forms/adherent.xml */,
		                        array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) 
		{
			return false;
		}
		return $form;
	}
	
	/**
	 * Method to get the record form.
	 *
	 * @param	array	$data		Data for the form.
	 * @param	boolean	$loadData	True if the form is to load its own data (default case), false if not.
	 * @return	mixed	A JForm object on success, false on failure
	 * @since	2.5
	 */
	public function getForm1($data = array(), $loadData = true) 
	{
		$userId = JRequest::getInt('user1id');
		// Get the form.
		$form = $this->loadForm('com_adh.1anomalie.form1', 'adherent' /* --> models/forms/adherent.xml */,
		                        array('control' => 'jform1', 'load_data' => $loadData), false, false, $userId);
		if (empty($form)) 
		{
			return false;
		}
		return $form;
	}

	/**
	 * Method to get the record form.
	 *
	 * @param	array	$data		Data for the form.
	 * @param	boolean	$loadData	True if the form is to load its own data (default case), false if not.
	 * @return	mixed	A JForm object on success, false on failure
	 * @since	2.5
	 */
	public function getForm2($data = array(), $loadData = true) 
	{
		$userId = JRequest::getInt('user2id');
		// Get the form.
		$form = $this->loadForm('com_adh.1anomalie.form2', 'adherent' /* --> models/forms/adherent.xml */,
		                        array('control' => 'jform2', 'load_data' => $loadData), true, false, $userId);
		if (empty($form)) 
		{
			return false;
		}
		return $form;
	}

	/**
	 * Method to get a form object. Inspired from JModelForm::loadForm
	 *
	 * @param   string   $name     The name of the form.
	 * @param   string   $source   The form source. Can be XML string if file flag is set to false.
	 * @param   array    $options  Optional array of options for the form creation.
	 * @param   boolean  $clear    Optional argument to force load a new form.
	 * @param   string   $xpath    An optional xpath to search for the fields.
	 * @param	integer	 $id	   the id of the 'adherent' to load
	 *
	 * @return  mixed  JForm object on success, False on error.
	 *
	 * @see     JForm
	 * @since   0.0.??
	 */
	protected function loadForm($name, $source = null, $options = array(), $clear = false, $xpath = false, $id = 0)
	{
		//echo("<pre>"); var_dump($source); echo("</pre>");
		//echo("<pre>"); var_dump($options); echo("</pre>");
		// Handle the optional arguments.
		$options['control'] = JArrayHelper::getValue($options, 'control', false);

		// Create a signature hash.
		$hash = md5($source . serialize($options));

		// Check if we can use a previously loaded form.
		if (isset($this->_forms[$hash]) && !$clear)
		{
			return $this->_forms[$hash];
		}

		// Get the form.
		JForm::addFormPath(JPATH_COMPONENT . '/models/forms');
		JForm::addFieldPath(JPATH_COMPONENT . '/models/fields');

		try
		{
			$form = JForm::getInstance($name, $source, $options, false, $xpath);

			if (isset($options['load_data']) && $options['load_data'])
			{
				// Get the data for the form.
				$data = $this->loadFormData($id);
			}
			else
			{
				$data = array();
			}

			// Allow for additional modification of the form, and events to be triggered.
			// We pass the data because plugins may require it.
			$this->preprocessForm($form, $data);

			// Load the data into the form after the plugins have operated.
			$form->bind($data);

		}
		catch (Exception $e)
		{
			$this->setError($e->getMessage());
			return false;
		}

		// Store the form for later.
		$this->_forms[$hash] = $form;

		return $form;
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return	mixed	The data for the form.
	 * @since	2.5
	 */
	protected function loadFormData($id) 
	{
		//echo("<pre>"); var_dump($id); echo("</pre>");
		// Check the session for previously entered form data.
		//$user1id = JRequest::getInt('user1id');
		//$user2id = JRequest::getInt('user2id');
		//$data = JFactory::getApplication()->getUserState('com_adh.edit.1anomalie.data.'.$id, array());
		$data = null;
		if (empty($data)) 
		{
			//$data = $this->getItem(array('id' => $id));
			$data = $this->getItem($id);
		}
		
		//echo("<pre>"); var_dump($data); echo("</pre>");
		//echo("<pre>".var_dump($data)."</pre>"); die();
		return $data;
	}

	/**
	 * Method to get a single record.
	 *
	 * @param   integer  $pk  The id of the primary key.
	 *
	 * @return  mixed	Object on success, false on failure.
	 *
	 * @since   0.0.30
	 */
	public function getItem($pk = null)	{
		// fetch the entire object from the helper class AdhUser
		return new AdhUser($pk);
	}
	
	/**
	 * Method to get the complete 'user1' object
	 * @since	0.0.34
	 */
	public function getUser1() {
		$userId = JRequest::getInt('user1id');
		return $this->getItem($userId);
	}

	/**
	 * Method to get the complete 'user1' object
	 * @since	0.0.34
	 */
	public function getUser2() {
		$userId = JRequest::getInt('user2id');
		return $this->getItem($userId);
	}

	/**
	 * Surcharge de la méthode save
	 * essentiellement pour formater correctement les données
	 * avant injection dans MySQL
	 */
	/*public function save($data) {
		echo("<pre>".var_dump($data)."</pre>");
		$data->nom = htmlentities($data->nom, ENT_QUOTES, 'UTF-8');
		echo("<pre>".var_dump($data)."</pre>");
		die();
	}*/
	
	/**
	 * Method to save the form data.
	 *
	 * @param   array  $data  The form data.
	 *
	 * @return  boolean  True on success.
	 *
	 * @from	UsersModelUser
	 * @since   0.0.33
	 */
	public function save($data)
	{
		// Initialise variables;
		$pk			= (!empty($data['id'])) ? $data['id'] : (int) $this->getState('user.id');
		$user		= AdhUser::getInstance($pk);

		// Bind the data.
		if (!$user->bind($data))
		{
			$this->setError($user->getError());

			return false;
		}

		// Store the data.
		if (!$user->save())
		{
			$this->setError($user->getError());

			return false;
		}

		$this->setState('user.id', $user->id);

		return true;
	}

	/**
	 * Method to delete one or more records.
	 *
	 * @param   array  &$pks  An array of record primary keys.
	 *
	 * @return  boolean  True if successful, false if an error occurs.
	 *
	 * @since   0.0.33
	 */
	public function delete(&$pks)
	{
		// Initialise variables.
		$dispatcher = JDispatcher::getInstance();
		$pks = (array) $pks;
		$table = $this->getTable();

		// Include the content plugins for the on delete events.
		JPluginHelper::importPlugin('content');

		// Iterate the items to delete each one.
		foreach ($pks as $i => $pk)
		{

			if ($table->load($pk))
			{

				if ($this->canDelete($table))
				{
					$user = AdhUser::getInstance($pk); 
					if (!$user->delete()) {
						$this->setError($table->getError());
						return false;
					}
				}
				else
				{

					// Prune items that you can't change.
					unset($pks[$i]);
					$error = $this->getError();
					if ($error)
					{
						JError::raiseWarning(500, $error);
						return false;
					}
					else
					{
						JError::raiseWarning(403, JText::_('JLIB_APPLICATION_ERROR_DELETE_NOT_PERMITTED'));
						return false;
					}
				}

			}
			else
			{
				$this->setError($table->getError());
				return false;
			}
		}

		// Clear the component's cache
		$this->cleanCache();

		return true;
	}


	/**
	 * Method to move all cotisations from one adherent to another
	 * 
	 * @param	array		array of cotisation id
	 * @param	int			target adherent's id
	 * 
	 */
	public function moveCotiz($cid, $adherent_id) {
		//$pk			= (!empty($data['id'])) ? $data['id'] : (int) $this->getState('user.id');
		//$user		= AdhUser::getInstance($pk);
		$success = 0;
		foreach ($cid as $id) {
			$cotiz = new AdhCotiz($id);
			if ($cotiz->move($adherent_id)) {
				$success++;
			}
		}
		if ($success > 0) { return $success; }

		return false;
	}

	/**
	 * Method to delete a cotisations
	 * 
	 * @param	int		cotisation id
	 * 
	 */
	public function deleteCotiz($id) {
		$cotiz = new AdhCotiz($id);
		return $cotiz->delete($id);
	}

	/**
	 * Method to delete a adherent
	 * 
	 * @param	int		adherent id
	 * 
	 */
	public function deleteUser($id) {
		$user = new AdhUser($id);
		return $user->delete($id);
	}
	
}
