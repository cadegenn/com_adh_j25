<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
 
/**
 * adh View
 */
class adhViewImportV2Adherents extends JView
{
	/**
	 * adhs view display method
	 * @return void
	 */
	function display($tpl = null) 
	{
		// Get data from the model
		/*$model = $this->get('Model');
		echo var_dump($this);
		echo ("<pre></pre>");
		echo var_dump($this->_models['importv2adherents']->_db->getErrorNum());
		die();*/
		/*if ($this->_models['importv2adherents']->_db->getErrorNum() > 0) { 
			$items = "";
		} else {
		*/
		$items = $this->get('Items');			// => admin/models/importV2Adherents.php
		$imported = $this->get('Imported');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) 
		{
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}
		//}

		$pagination = $this->get('Pagination');
		
		// Assign data to the view
		$this->items = $items;
		$this->imported = $imported;
		$this->pagination = $pagination;
		//Add the state... (http://docs.joomla.org/How_to_add_custom_filters_to_component_admin)
		$this->state = $this->get('State');

			// Set the toolbar
		$this->addToolBar();
		
		// Ajouter le sous menu
		ADHHelper::addSubmenu('import');	// => admin/helpers/adh.php
		
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
		JToolBarHelper::title(JText::_('COM_ADH').' : '.JText::_('COM_ADH_SUBMENU_IMPORT_ADHERENTS'), 'adh');
		//JToolBarHelper::deleteListX('', 'adh.delete');
		//JToolBarHelper::editListX('importV2Adherent.edit');
		//JToolBarHelper::addNewX('importV2Adherent.add');
		JToolBarHelper::custom('importV2Adherents.import','import','import',JText::_('COM_ADH_IMPORT'), true);
		JToolBarHelper::custom('importV2Adherents.importAll','import','importAll',JText::_('COM_ADH_IMPORT_ALL'), false);
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
