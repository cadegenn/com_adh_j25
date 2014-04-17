<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
 
/**
 * adh View
 */
class adhView1anomalies extends JView
{
	/**
	 * adherents view display method
	 * @return void
	 */
	function display($tpl = null) 
	{
		// Get data from the model
		$items = $this->get('Items');			// => admin/models/1anomalies.php
		$pagination = $this->get('Pagination');
 
		// Check for errors.
		if (count($errors = $this->get('Errors'))) 
		{
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}
		// Assign data to the view
		$this->items = $items;
		$this->pagination = $pagination;
 
		// Set the toolbar
		$this->addToolBar();
		//Add the state... (http://docs.joomla.org/How_to_add_custom_filters_to_component_admin)
		$this->state = $this->get('State');
		
		// Ajouter le sous menu
		ADHHelper::addSubmenu('anomalies');	// => admin/helpers/adh.php
		
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
		JToolBarHelper::title(JText::_('COM_ADH').' : '.JText::_('COM_ADH_SUBMENU_ANOMALIES'), 'adh');
		JToolBarHelper::editListX('adherent.edit');
		JToolBarHelper::divider();
		JToolBarHelper::publishList('adherents.publish');
		JToolBarHelper::unpublishList('adherents.unpublish');
		JToolBarHelper::divider();
		JToolBarHelper::archiveList('adherents.archive');
		JToolBarHelper::deleteListX(JText::_('COM_ADH_AREYOUSURE'),'adherents.delete');
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
