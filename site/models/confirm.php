<?php

/* 
 *  Copyright (C) 2012-2015 charly
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
 
// Include dependancy of the main model form
jimport('joomla.application.component.modelform');
// import Joomla modelitem library
jimport('joomla.application.component.modelitem');
// Include dependancy of the dispatcher
jimport('joomla.event.dispatcher');

/**
 * Adherer Model
 */
class adhModelConfirm extends JModelForm
{
	/**
	 * @var object item
	 */
	protected $item;

	/**
	 * Get the data for a new qualification
	 */
	public function getForm($data = array(), $loadData = true)
	{

		$app = JFactory::getApplication('site');

		// Get the form.
		$form = $this->loadForm('com_adh.adherent', 'adherent', array('control' => 'jform', 'load_data' => true));
		if (empty($form)) {
			return false;
		}
		return $form;

	}
 
	/**
	 * Get the message
	 * @return object The message to be displayed to the user
	 */
	function &getItem()
	{
		if (!isset($this->_item))
		{
			$cache = JFactory::getCache('com_adh', '');
			$id = $this->getState('adherent.id');
			$this->_item =  $cache->get($id);
			if ($this->_item === false) {
				$db = $this->getDbo();
				$query  = $db->getQuery(true);
				$query->select('*')->from('#__adh_adherents')->where('id = 5');
				$db->setQuery((string)$query);
				$db->query();
				$this->_item = $db->loadObject();
		   }
		}
		return $this->_item;

	}
 
	/**
	 * @brief	get the cotiz passed as parameter
	 * @param	cotizId
	 *			use helper @since 0.0.42
	 */
	public function getCotiz($cotizId = 0) {
		if ($cotizId == 0) { $cotizId = JRequest::getInt('cotizId', 0, 'get'); }
		return new ADHCotiz($cotizId);
	}

	/**
	 * @brief	get the adherent passed as parameter
	 * @param	adhId
	 *			use helper @since 0.0.42
	 */
	public function getAdherent($adhId = 0) {
		if ($adhId == 0) { $adhId = JRequest::getInt('adhId', 0, 'get'); }
		return new ADHUser($adhId);
	}

}
