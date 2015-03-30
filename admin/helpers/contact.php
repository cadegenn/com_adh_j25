<?php

/**
 * @package		com_adh
 * @subpackage	contact
 * @brief		Contact helper class, provides static methods to perform various tasks relevant to the Joomla contact classes
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
defined('_JEXEC') or die;
 
abstract class JContact {
	
	/**
	 * @brief	getFeatured()	return contact marked as 'featured'
	 * @return	(array)			array of featured contacts
	 */
	static function getFeatured() {
		$db = JFactory::getDbo();
		$query = $db->getQuery(TRUE);
		$query->select('*')->from('#__contact_details as cd')->where('featured = 1');
		$db->setQuery($query);
		$db->execute();
		
		return $db->loadObjectList();
		//return $db->loadAssocList();
	}
}