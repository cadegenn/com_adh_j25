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
		$params = JComponentHelper::getParams('com_adh');
		$model  = $this->getModel('adherent');
		$id = 0;
		$body = "";

		// Get the data from the form POST
		$data = JRequest::getVar('jform', array(), 'post', 'array');
		//$app->enqueueMessage('<pre>'.var_dump($data).'</pre>', 'Notice');
		// Now update the loaded data to the database via a function in the model
		// record new adherent
		$adhId = $model->adherer($data);	// -> models/adherent.php -> adherer()
		// check if ok and display appropriate message.  This can also have a redirect if desired.
		if (!$adhId) {
			$app->enqueueMessage(JText::_('COM_ADH_ADHERENT_NOT_SAVED'), 'Error');
			//JError::raiseError( 4711, JText::_('COM_ADH_ADHERENT_NOT_SAVED') );
			return false;
		}
		
		$app->enqueueMessage(JText::_('COM_ADH_ADHERENT_SAVED'));
		// add the new user to recipient of confirmation email
		//$mailer->addRecipient($data['email']);
		//$body = ADHHelper::buildBulletinAdhesionUser($adhId);

		// record soon-to-be-paid cotisation :-)
		$cotizId = $model->enregistrer_cotiz($data, $adhId);	// -> models/adherent.php -> enregistrer_cotiz()
		// check if ok and display appropriate message.  This can also have a redirect if desired.
		if ($cotizId < 0) {
			$app->enqueueMessage(JText::_('COM_ADH_COTISATION_NOT_SAVED'), 'Error');
			//JError::raiseError( 4711, JText::_('COM_ADH_COTISATION_NOT_SAVED') );
			return false;
		}

		$app->enqueueMessage(JText::_('COM_ADH_COTISATION_SAVED'));

		//$body .= ADHHelper::buildBulletinAdhesionCotiz($cotizId);
		//$body .= ADHHelper::buildBulletinAdhesionConfirm($cotizId);

		// @TODO	route to display summary to let user print it
		// workaround: display it here: it is the mail's body
		/*echo $body;

		if ((int)$params->get('alert_sendmail_on_inscription_cb') == 1) {
			$mailer->setSubject(JText::sprintf('COM_ADH_ADHERER_MAIL_SUBJECT', JURI::base(), strtoupper($data['nom']), $data['prenom']));
			$mailer->setBody($body);
			$send = $mailer->Send();
			if ($send !== true) {
				$app->enqueueMessage(JText::_('COM_USERS_REGISTRATION_SEND_MAIL_FAILED'), 'Error');
				//$app->enqueueMessage('<pre>'.var_dump($mailer).'</pre>', 'Notice');
				//JError::raiseError( 4711, JText::_('COM_ADH_ADHERENT_MAIL_NOT_SENT') );
			} else {
				$app->enqueueMessage(JText::_('COM_ADH_ADHERER_MAIL_SENT'));
			}
		}*/

		$app->redirect(JRoute::_('index.php?option=com_adh&view=confirm&adhId='.$adhId.'&cotizId='.$cotizId), false);

		return true;
	}

}
