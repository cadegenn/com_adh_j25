<?php
/**
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
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
			echo "<h2>adh: ok</h2>";
		} else {
			echo "<h2>adh: failed</h2>";
		}

		// record soon-to-be-paid cotisation :-)
		$saved = $model->enregistrer_cotiz($data, $id);	// -> models/adherent.php -> enregistrer_cotiz()
		// check if ok and display appropriate message.  This can also have a redirect if desired.
		if ($saved) {
			echo "<h2>cotiz: ok</h2>";
		} else {
			echo "<h2>cotiz: failed</h2>";
		}

				return true;
	}

}
