<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
 
/**
 * adh View
 */
class adhViewImportV2Origines extends JView
{
	/**
	 * adhs view display method
	 * @return void
	 */
	function display($tpl = null) 
	{
		if ($this->_models['importv2origines']->_db->getErrorNum() > 0) { 
			$items = "";
		} else {
			$items = $this->get('Items');			// => admin/models/importV2origines.php

			// Check for errors.
			if (count($errors = $this->get('Errors'))) 
			{
				JError::raiseError(500, implode('<br />', $errors));
				return false;
			}
		}

		$pagination = $this->get('Pagination');
		
		// Assign data to the view
		$this->items = $items;
		$this->pagination = $pagination;
 
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
		JToolBarHelper::title(JText::_('COM_ADH').' : '.JText::_('COM_ADH_SUBMENU_IMPORT_ORIGINES'), 'adh');
		//JToolBarHelper::deleteListX('', 'adh.delete');
		//JToolBarHelper::editListX('importV2Chantier.edit');
		//JToolBarHelper::addNewX('importV2Chantier.add');
		//JToolBarHelper::preferences('com_adh');
		JToolBarHelper::custom('importV2Origines.import','import','import',JText::_('COM_ADH_IMPORT'), true);
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
