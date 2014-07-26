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
 
// import Joomla controllerform library
jimport('joomla.application.component.controllerform');
 
/**
 * adherent Controller
 */
class adhControllerAdherent extends JControllerForm{
	/**
	 * Overrides parent save method to set id state
	 *
	 * @param   string  $key     The name of the primary key of the URL variable.
	 * @param   string  $urlVar  The name of the URL variable if different from the primary key (sometimes required to avoid router collisions).
	 *
	 * @return  boolean  True if successful, false otherwise.
	 *
	 * @since   0.0.30
	 */
	public function save($key = null, $urlVar = null)
	{
		/*var_dump($_POST);
		echo("<br />");
		var_dump(JRequest::getVar('jform', array(), 'post', null));
		die();*/
		/*
		$id = JRequest::getVar(id, int, 'get', 0);
		$model = $this->getModel();
		$model->setState($this->context . '.id', $id);
		// @TODO: find why the context is not correct
		// we need this for the page to reload when clicking 'Apply'
		// because the context in the ControllerForm is 'user.id' instead of adherent.id
		// don't know why...
		$model->setState('user.id', $id);
		 * 
		 */

		return parent::save();
	}
}
