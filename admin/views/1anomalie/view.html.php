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
 * 1anomalie View
 * 
 */
class adhView1anomalie extends JView
{
	/**
	 * $form1	object	form for user 1
	 */
	public $form1 = null;
	
	/**
	 * $form2	object	form for user 2
	 */
	public $form2 = null;
	
	/**
	 * $toolbar1	toolbar for user 1
	 */
	public $toolbar1 = null;
	
	/**
	 * $toolbar2	toolbar for user 2
	 */
	public $toolbar2 = null;
	
    /**
     * display method of Hello view
     * @return void
     */
    public function display($tpl = null) 
    {
		//$model = $this->getModel();
		//$user1id = JRequest::getInt('user1id');
		//$user2id = JRequest::getInt('user2id');
        // get the Data
        $form1 = $this->get('Form1');
        $form2 = $this->get('Form2');
		//$form1 = $model->getForm($data, true, $user1Id);
        $user1 = $this->get('User1');
        $user2 = $this->get('User2');

        // Check for errors.
        if (count($errors = $this->get('Errors'))) 
        {
                JError::raiseError(500, implode('<br />', $errors));
                return false;
        }
        // Assign the Data
        $this->form1 = $form1;
        $this->form2 = $form2;
        $this->user1 = $user1;
        $this->user2 = $user2;

        // Set the toolbar
        $this->addToolBar();
		$this->toolbar1 = $this->addUserToolbar('user1');
		$this->toolbar2 = $this->addUserToolbar('user2');
        // Load styleSheet
        $this->addDocStyle();
		// Load scripts
		$this->addScripts();

		// Ajouter le sous menu
		ADHHelper::addSubmenu('anomalies');	// => admin/helpers/adh.php
		
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
        //$isNew = ($this->item->id == 0);
		
		// si JCE est présent, on ajoute un bouton JCE Browser
		/*if (is_dir(JPATH_ADMINISTRATOR .DS. 'components' .DS. 'com_jce')) {
			require_once(JPATH_ADMINISTRATOR .DS. 'components' .DS. 'com_jce' .DS. 'helpers' .DS. 'browser.php');
			$bar=& JToolBar::getInstance( 'toolbar' );
			$bar->appendButton( 'Popup', 'stats', 'Explorateur',  WFBrowserHelper::getBrowserLink(), 800, 500 );
			JToolBarHelper::divider();
		}
		 * 
		 */
        JToolBarHelper::title(JText::_('COM_ADH').' : '.JText::_('COM_ADH_MANAGER_ANOMALIES_ADHERENTS'));
        //                             : JText::_('COM_ADH').' : '.JText::_('COM_ADH_MANAGER_ADHERENT_EDIT'));
        //JToolBarHelper::apply('1anomalie.apply');                                   // --> administrator/components/com_adh/controllers/1anomalie.php::save();
        //JToolBarHelper::save('1anomalie.save');                                     // --> administrator/components/com_adh/controllers/1anomalie.php::save();
        //JToolBarHelper::save2new('1anomalie.save2new');								// --> administrator/components/com_adh/controllers/1anomalie.php::save();
        //JToolBarHelper::save2copy('1anomalie.save2copy');                           // --> administrator/components/com_adh/controllers/1anomalie.php::save();
        JToolBarHelper::cancel('1anomalie.cancel', 'JTOOLBAR_CLOSE');				// --> administrator/components/com_adh/controllers/1anomalie.php::cancel();
    }
	
	/**
	 * @brief	addUserToolbar()	create another toolbar for one side
	 */
	public function addUserToolbar($name = "") {
		//$toolbar = new JToolBar($name);
		AdhToolBarHelper::delete('1anomalie.deleteUser', 'JDELETE', 'toolbar-'.$name, 'adminForm'.  ucwords($name));
		AdhToolBarHelper::apply('1anomalie.apply', 'JAPPLY', 'toolbar-'.$name, 'adminForm'.  ucwords($name));
		//return $toolbar;
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
