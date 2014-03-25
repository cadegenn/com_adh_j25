<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
 
// Import library dependencies
JLoader::register('myJToolBarHelper', JPATH_COMPONENT_ADMINISTRATOR . '/helpers/toolbar.php');

/**
 * adh View
 */
class adhViewadh extends JView
{
	/**
	 * adhs view display method
	 * @return void
	 */
	function display($tpl = null) 
	{
		// Get data from the model
		//$items = $this->get('Items');
		//$model = $this->get('Model');
		$stats_cotiz_by_year = $this->get('StatsCotizByYear');
		$online_registrations = $this->get('OnlineRegistrations');
		$pending_payments = $this->get('PendingPayments');
		$pagination = $this->get('Pagination');
 
		// Check for errors.
		if (count($errors = $this->get('Errors'))) 
		{
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}
		// Assign data to the view
		$this->stats_cotiz_by_year = $stats_cotiz_by_year;
		$this->online_registrations = $online_registrations;
		$this->pending_payments = $pending_payments;
		$this->pagination = $pagination;
 
		// Set the toolbar
		$this->addToolBar();
		
		$doc = JFactory::getDocument();
        $doc->addScript('https://www.google.com/jsapi');
		
		// Display the template
		parent::display($tpl);
		
		// Set the document
		$this->setDocument();
	}
	
	/**
	 * Setting the toolbar
	 */
	protected function addToolBar() 
	{
		// voir d'autres boutons dans /administrator/includes/toolbar.php
		JToolBarHelper::title(JText::_('COM_ADH'), 'adh');
		myJToolBarHelper::link('adherent-add', JText::_('COM_ADH_MANAGER_ADHERENT_NEW'), 'index.php?option=com_adh&view=adherent&layout=edit');
		myJToolBarHelper::link('group-add', JText::_('COM_ADH_MANAGER_GROUPE_NEW'), 'index.php?option=com_adh&view=groupe&layout=edit');
		myJToolBarHelper::link('cotisation-add', JText::_('COM_ADH_MANAGER_COTISATION_NEW'), 'index.php?option=com_adh&view=cotisation&layout=edit');
		JToolBarHelper::divider();
		JToolBarHelper::preferences('com_adh');
	}
	
	/**
	 * Method to set up the document properties
	 *
	 * @return void
	 */
	protected function setDocument() 
	{
		$document = JFactory::getDocument();
		$document->setTitle(JText::_('COM_ADH_ADMINISTRATION'));
	}
}

?>
