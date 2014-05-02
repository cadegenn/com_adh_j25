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
 *  This file is part of com_apl.
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

// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

/**
 * @package		Joomla.Site
 * @subpackage	com_adh
 */
class adhControllerAdherent extends JControllerForm
{
	/**
	 * Method to get a model object, loading it if required.
	 *
	 * @param	string	$name	The model name. Optional.
	 * @param	string	$prefix	The class prefix. Optional.
	 * @param	array	$config	Configuration array for model. Optional.
	 *
	 * @return	object	The model.
	 *
	 * @since	1.5
	 */
	public function getModel($name = 'form', $prefix = '', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);

		return $model;
	}

	public function adherer() {
		// Check for request forgeries.
		JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Initialise variables.
		$app    = JFactory::getApplication();
		$model  = $this->getModel('adherent');
		$id = 0;

		// Get the data from the form POST
		$data = JRequest::getVar('jform', array(), 'post', 'array');

		// Now update the loaded data to the database via a function in the model
		// record new adherent
		$saved = $model->adherer($data);	// -> models/adherent.php -> adherer()
		// check if ok and display appropriate message.  This can also have a redirect if desired.
		if ($saved) {
			$id = $saved;	// if saved > false, then it equals adherent's id
			$app->enqueueMessage(JText::_('ADH_ADHERENT_SAVED'));
		} else {
			JError::raiseError( 4711, JText::_('ADH_ADHERENT_NOT_SAVED') );
		}

		// record soon-to-be-paid cotisation :-)
		$saved = $model->enregistrer_cotiz($data, $id);	// -> models/adherent.php -> enregistrer_cotiz()
		// check if ok and display appropriate message.  This can also have a redirect if desired.
		if ($saved) {
			$app->enqueueMessage(JText::_('ADH_COTISATION_SAVED'));
		} else {
			JError::raiseError( 4711, JText::_('ADH_COTISATION_NOT_SAVED') );
		}

				return true;
	}

}
