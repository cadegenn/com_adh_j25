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
 
/**
 * 2anomalie Model
 */
class adhModel2anomalie extends JModelAdmin
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
	public function getTable($type = 'cotisation', $prefix = 'adhTable', $config = array()) 
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
		$form = $this->loadForm('com_adh.cotisation', 'cotisation' /* --> models/forms/cotisation.xml */,
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
		$form = $this->loadForm('com_adh.2anomalie.form1', 'cotisation' /* --> models/forms/cotisation.xml */,
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
		$form = $this->loadForm('com_adh.2anomalie.form2', 'cotisation' /* --> models/forms/cotisation.xml */,
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
	 *
	 * @return  mixed  JForm object on success, False on error.
	 *
	 * @see     JForm
	 * @since   11.1
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
		//$data = JFactory::getApplication()->getUserState('com_adh.edit.2anomalie.data.'.$id, array());
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
	
	
}
