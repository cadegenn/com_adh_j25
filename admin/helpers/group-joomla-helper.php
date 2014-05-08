<?php

/**
 * @package		com_adh
 * @subpackage	helper
 * @brief		Authorisation helper class, provides static methods to perform various tasks relevant to the Joomla group and authorisation classes
 * @copyright	Copyright (C) 2010 - 2014 DEGENNES Charles-Antoine <cadegenn@gmail.com>
 * @license		Affero GNU General Public License version 3 or later; see LICENSE.txt
 * 
 * @TODO		
 */

defined('JPATH_PLATFORM') or die;

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

abstract class JGroupHelper {
	/**
	 * Returns groupid if a group exists
	 *
	 * @param   string  $title  The group title to search on.
	 *
	 * @return  integer  The group id or 0 if not found.
	 *
	 * @since   11.1
	 */
	public static function getGroupId($title)
	{
		// Initialise some variables
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select($db->quoteName('id'));
		$query->from($db->quoteName('#__usergroups'));
		$query->where($db->quoteName('title') . ' = ' . $db->quote($title));
		$db->setQuery($query, 0, 1);
		return $db->loadResult();
	}

	
}