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
 
/**
 * adherent View
 * 
 */
class adhViewAdherent extends JView
{
    /**
     * display method of Hello view
     * @return void
     */
    public function display($tpl = null) 
    {
        // get the Data
        $this->form = $this->get('Form');
        $this->item = $this->get('Item');

        // Check for errors.
        if (count($errors = $this->get('Errors'))) 
        {
                JError::raiseError(500, implode('<br />', $errors));
                return false;
        }

        // Set the toolbar
        $this->addToolBar();
        // Load styleSheet
        $this->addDocStyle();
		// Load scripts
		$this->addScripts();

		// Ajouter le sous menu
		ADHHelper::addSubmenu('adherents');	// => admin/helpers/adh.php
		
        // Display the template
        parent::display($tpl);		// -> ./tmpl/edit.php
    }

    /**
     * Setting the toolbar
     */
    protected function addToolBar() 
    {
        $input = JFactory::getApplication()->input;
        $input->set('hidemainmenu', true);
        $isNew = ($this->item->id == 0);
		
		// si JCE est présent, on ajoute un bouton JCE Browser
		/*if (is_dir(JPATH_ADMINISTRATOR .DS. 'components' .DS. 'com_jce')) {
			require_once(JPATH_ADMINISTRATOR .DS. 'components' .DS. 'com_jce' .DS. 'helpers' .DS. 'browser.php');
			$bar=& JToolBar::getInstance( 'toolbar' );
			$bar->appendButton( 'Popup', 'stats', 'Explorateur',  WFBrowserHelper::getBrowserLink(), 800, 500 );
			JToolBarHelper::divider();
		}
		 * 
		 */
        JToolBarHelper::title($isNew ? JText::_('COM_ADH').' : '.JText::_('COM_ADH_MANAGER_ADHERENT_NEW')
                                     : JText::_('COM_ADH').' : '.JText::_('COM_ADH_MANAGER_ADHERENT_EDIT'));
        JToolBarHelper::apply('adherent.apply');                                      // --> administrator/components/com_adh/controllers/adherent.php::save();
        JToolBarHelper::save('adherent.save');                                      // --> administrator/components/com_adh/controllers/adherent.php::save();
        JToolBarHelper::save2new('adherent.save2new');                                      // --> administrator/components/com_adh/controllers/adherent.php::save();
        JToolBarHelper::save2copy('adherent.save2copy');                                      // --> administrator/components/com_adh/controllers/adherent.php::save();
        JToolBarHelper::cancel('adherent.cancel', $isNew    ? 'JTOOLBAR_CANCEL'     // --> administrator/components/com_adh/controllers/adherent.php::cancel();
                                                            : 'JTOOLBAR_CLOSE');
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
     * Add the scripts to the document.
     */
    protected function addScripts()
    {
        $doc = JFactory::getDocument();
        $doc->addScript('components/com_adh/js/fonctions.js');
    }
    
}
