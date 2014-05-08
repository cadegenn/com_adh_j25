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
 
// import Joomla controlleradmin library
jimport('joomla.application.component.controlleradmin');
 
/**
 * adherents Controller
 */
class adhControllerAdherents extends JControllerAdmin
{
	/**
	 * Proxy for getModel.
	 * @since	2.5
	 */
	public function getModel($name = 'adherent', $prefix = 'adhModel') 
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}
	
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
		$return = JRequest::getVar('return', null, 'default', 'base64');
		if (is_null($return)) return false;
		if (empty($return) || !JUri::isInternal(base64_decode($return))) {
			return JURI::base()."/index.php?option=".$this->option."&view=".$this->view_list;
		}
		else {
			return base64_decode($return);
		}
		//echo("<pre>"); var_dump($this); echo("</pre>");
	}

	/*
	 * @brief	publish()		override only to return into another view after process if needed
	 * @since	0.0.20
	 */
	public function publish() {
		parent::publish();
		$return = $this->getReturnPage();
		if ($return != false) $this->setRedirect($return);
	}

	/*
	 * @brief	unpublish()		override only to return into another view after process if needed
	 * @since	0.0.20
	 */
	public function unpublish() {
		parent::unpublish();
		$return = $this->getReturnPage();
		if ($return != false) $this->setRedirect($return);
	}
	
	/*
	 * @brief	archive()		override only to return into another view after process if needed
	 * @since	0.0.20
	 */
	public function archive() {
		parent::archive();
		$return = $this->getReturnPage();
		if ($return != false) $this->setRedirect($return);
	}

	/*
	 * @brief	delete()		override only to return into another view after process if needed
	 * @since	0.0.20
	 */
	public function delete() {
		parent::delete();
		$return = $this->getReturnPage();
		if ($return != false) $this->setRedirect($return);
	}
}
?>
