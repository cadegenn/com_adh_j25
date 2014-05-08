<?php
/**
 * @package		com_adh_j25
 * @subpackage	helpers
 * @brief		extension of Joomla! class JToolBarHelper to create custom button link
 * @copyright	Copyright (C) 2005 - 2014 DEGENNES Charles-Antoine <cadegenn@gmail.com>
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

// No direct access
defined('_JEXEC') or die;

jimport('joomla.html.toolbar');

/**
 * Utility class for the button bar.
 *
 * @package		Joomla.Administrator
 * @subpackage	Application
 */
class myJToolBarHelper extends JToolBarHelper {
	/**
	 * @brief	link()	Writes a custom button link 
	 * @param	string	$alt	Alternative text.
	 * @param	string	$href	URL of the href attribute.
	 * @since	1.0
	 */
	public static function link($id, $alt, $href)
	{
		$bar = JToolBar::getInstance('toolbar');
		// Add button.
		$bar->appendButton('Link', $id, $alt, $href);
	}

}

?>
