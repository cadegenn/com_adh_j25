<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
 
/**
 * HTML View class for the Adherer Component
 */
class adhViewAdherent extends JView
{
	// Overwriting JView display method
	function display($tpl = null)  {
		$app            = JFactory::getApplication();
		$params         = $app->getParams();
		$dispatcher = JDispatcher::getInstance();

		// Get some data from the models
		$state          = $this->get('State');
		$item           = $this->get('Item');
		$this->form     = $this->get('Form');
		$this->tarifs	= $this->get('Tarifs');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) 
		{
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}

		// add some styles
		$doc = JFactory::getDocument();
		$doc->addStyleSheet('components/com_adh/css/admin.css');

		// Display the view
		parent::display($tpl);
	}
}