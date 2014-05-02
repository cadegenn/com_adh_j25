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
 *  This file is part of com_apl.
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
 
// import Joomla controllerform library
jimport('joomla.application.component.controllerform');
 
/**
 * adherent Controller
 */
class adhControllerAdherent extends JControllerForm
{
	/*
	 * @brief	__construct()	constructor of object
	 * @why?	because this controller can be called not only by the default view_list, so we need to known to wich view we have to return to
	 */
	function __construct($config = array()) {
		$calling_view = JRequest::getVar('calling_view', null, 'get', 'string');
		if (!is_null($calling_view)) $this->view_list = $calling_view;
		parent::__construct($config);
    }
	
    /*
     * Save datas
     */
    /*public function save(){
        echo("<pre>_GET : ".var_dump($_GET)."</pre>");
        echo("<pre>_POST : ".var_dump($_POST)."</pre>");
        echo("<pre>this : ".var_dump($this)."</pre>");
        echo("<pre>getFieldset(): ".var_dump($this->form->getFieldset())."</pre>");
        //die();
        foreach ($this->form->getFieldset() as $field) {
            
        }
    }*/

	/**
	 * Get the return URL.
	 *
	 * If a "return" variable has been passed in the request
	 * in a custom function, call $this->setRedirect($this->getReturnPage());
	 *
	 * @return	string	The return URL.
	 * @since	0.0.20
	 */
	protected function getReturnPage()
	{
		$return = JRequest::getVar('return', null, 'get', 'base64');
		if (is_null($return)) return false;
		if (empty($return) || !JUri::isInternal(base64_decode($return))) {
			return JURI::base()."/index.php?option=".$this->option."&view=".$this->view_list;
		}
		else {
			return base64_decode($return);
		}
	}

	/*
	 * @brief	edit()		override only to return into another view after process if needed
	 * @since	0.0.20
	 */
	public function edit($key = null, $urlVar = null) {
		parent::edit($key, $urlVar);
		$return = $this->getReturnPage();
		if ($return != false) $this->setRedirect($return);
	}
	
}
