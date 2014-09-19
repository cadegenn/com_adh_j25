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
 
// import Joomla view library
jimport('joomla.application.component.view');
 
// Import library dependencies
JLoader::register('AdhToolBarHelper', JPATH_COMPONENT_ADMINISTRATOR . '/helpers/toolbar.php');

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
		//$adherents = $this->get('Adherents');
 
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
 		$this->component = $this->get('Component');
		$this->manifest = json_decode($this->component->manifest_cache);
		//$this->adherents = $adherents;

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
		AdhToolBarHelper::link('adherent-add', JText::_('COM_ADH_MANAGER_ADHERENT_NEW'), 'index.php?option=com_adh&view=adherent&layout=edit');
		AdhToolBarHelper::link('group-add', JText::_('COM_ADH_MANAGER_GROUPE_NEW'), 'index.php?option=com_adh&view=groupe&layout=edit');
		AdhToolBarHelper::link('cotisation-add', JText::_('COM_ADH_MANAGER_COTISATION_NEW'), 'index.php?option=com_adh&view=cotisation&layout=edit');
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
