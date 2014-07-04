<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
 
/**
 * adh View
 */
class adhViewExtractEmails extends JView
{
	/**
	 * adhs view display method
	 * @return void
	 */
	function display($tpl = null) 
	{
		// Get data from the model
		$items = $this->get('Items');			// => admin/models/ExtractEmails.php
 
		// Check for errors.
		if (count($errors = $this->get('Errors'))) 
		{
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}
		$pagination = $this->get('Pagination');
		// Assign data to the view
		$this->items = $items;
		$this->pagination = $pagination;
		//Add the state... (http://docs.joomla.org/How_to_add_custom_filters_to_component_admin)
		$this->state = $this->get('State');
		
		// Set the toolbar
		$this->addToolBar();
		// Load styleSheet
        $this->addDocStyle();
		
		// Ajouter le sous menu
		ADHHelper::addSubmenu('extractions');	// => admin/helpers/adh.php
		
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
		JToolBarHelper::title(JText::_('COM_ADH').' : '.JText::_('COM_ADH_SUBMENU_EXTRACTIONS_EMAILS'), 'adh');
		/*JToolBarHelper::addNewX('extractEmail.add');
		JToolBarHelper::editListX('extractEmail.edit');
		JToolBarHelper::divider();
		JToolBarHelper::publishList('extractEmails.publish');
		JToolBarHelper::unpublishList('extractEmails.unpublish');
		JToolBarHelper::divider();
		JToolBarHelper::archiveList('extractEmails.archive');
		JToolBarHelper::deleteListX(JText::_('COM_ADH_AREYOUSURE'), 'extractEmails.delete');
		JToolBarHelper::divider();*/
		JToolBarHelper::preferences('com_adh');
	}
	
	/**
     * Add the stylesheet to the document.
     */
    protected function addDocStyle()
    {
        $doc = JFactory::getDocument();
        $doc->addStyleSheet('components/com_adh/css/admin.css');
		//$doc->addStyleSheet('components/com_adh/css/googlemap-v3.css');
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
