<?php

/**
 * @package		com_adh
 * @subpackage	helper
 * @brief		Authorisation helper class, provides static methods to perform various tasks relevant to the Adh user and authorisation classes
 * @copyright	Copyright (C) 2010 - 2014 DEGENNES Charles-Antoine <cadegenn@gmail.com>
 * @license		Affero GNU General Public License version 3 or later; see LICENSE.txt
 * @since		0.0.20
 * 
 * @TODO		finish overriding for com_adh component instead of JUser class
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

/**
 * @package     Joomla.Platform
 * @subpackage  User
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_PLATFORM') or die;

class AdhUserHelper extends JUserHelper {
	/**
	 * Method to add a user to a group.
	 *
	 * @param   integer  $userId   The id of the user.
	 * @param   integer  $groupId  The id of the group.
	 *
	 * @return  mixed  Boolean true on success, Exception on error.
	 *
	 * @since   11.1
	 */
	public static function addUserToGroup($userId, $groupId)
	{
		// Get the user object.
		$user = new JUser((int) $userId);

		// Add the user to the group if necessary.
		if (!in_array($groupId, $user->groups))
		{
			// Get the title of the group.
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select($db->quoteName('title'));
			$query->from($db->quoteName('#__adh_usergroups'));
			$query->where($db->quoteName('id') . ' = ' . (int) $groupId);
			$db->setQuery($query);
			$title = $db->loadResult();

			// Check for a database error.
			if ($db->getErrorNum())
			{
				return new Exception($db->getErrorMsg());
			}

			// If the group does not exist, return an exception.
			if (!$title)
			{
				return new Exception(JText::_('JLIB_USER_EXCEPTION_ACCESS_USERGROUP_INVALID'));
			}

			// Add the group data to the user object.
			$user->groups[$title] = $groupId;

			// Store the user object.
			if (!$user->save())
			{
				return new Exception($user->getError());
			}
		}

		// Set the group data for any preloaded user objects.
		$temp = JFactory::getUser((int) $userId);
		$temp->groups = $user->groups;

		// Set the group data for the user object in the session.
		$temp = JFactory::getUser();
		if ($temp->id == $userId)
		{
			$temp->groups = $user->groups;
		}

		return true;
	}

	/**
	 * Method to get a list of groups a user is in.
	 *
	 * @param   integer  $userId  The id of the user.
	 *
	 * @return  mixed  Array on success, JException on error.
	 *
	 * @since   11.1
	 */
	public static function getUserGroups($userId)
	{
		// Get the user object.
		$user = JUser::getInstance((int) $userId);

		return isset($user->groups) ? $user->groups : array();
	}

	/**
	 * Method to remove a user from a group.
	 *
	 * @param   integer  $userId   The id of the user.
	 * @param   integer  $groupId  The id of the group.
	 *
	 * @return  mixed  Boolean true on success, JException on error.
	 *
	 * @since   11.1
	 */
	public static function removeUserFromGroup($userId, $groupId)
	{
		// Get the user object.
		$user = JUser::getInstance((int) $userId);

		// Remove the user from the group if necessary.
		$key = array_search($groupId, $user->groups);
		if ($key !== false)
		{
			// Remove the user from the group.
			unset($user->groups[$key]);

			// Store the user object.
			if (!$user->save())
			{
				return new JException($user->getError());
			}
		}

		// Set the group data for any preloaded user objects.
		$temp = JFactory::getUser((int) $userId);
		$temp->groups = $user->groups;

		// Set the group data for the user object in the session.
		$temp = JFactory::getUser();
		if ($temp->id == $userId)
		{
			$temp->groups = $user->groups;
		}

		return true;
	}

	/**
	 * Method to set the groups for a user.
	 *
	 * @param   integer  $userId  The id of the user.
	 * @param   array    $groups  An array of group ids to put the user in.
	 *
	 * @return  mixed  Boolean true on success, Exception on error.
	 *
	 * @since   11.1
	 */
	public static function setUserGroups($userId, $groups)
	{
		// Get the user object.
		$user = JUser::getInstance((int) $userId);

		// Set the group ids.
		JArrayHelper::toInteger($groups);
		$user->groups = $groups;

		// Get the titles for the user groups.
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select($db->quoteName('id') . ', ' . $db->quoteName('title'));
		$query->from($db->quoteName('#__adh_usergroups'));
		$query->where($db->quoteName('id') . ' = ' . implode(' OR ' . $db->quoteName('id') . ' = ', $user->groups));
		$db->setQuery($query);
		$results = $db->loadObjectList();

		// Check for a database error.
		if ($db->getErrorNum())
		{
			return new Exception($db->getErrorMsg());
		}

		// Set the titles for the user groups.
		for ($i = 0, $n = count($results); $i < $n; $i++)
		{
			$user->groups[$results[$i]->id] = $results[$i]->title;
		}

		// Store the user object.
		if (!$user->save())
		{
			return new Exception($user->getError());
		}

		// Set the group data for any preloaded user objects.
		$temp = JFactory::getUser((int) $userId);
		$temp->groups = $user->groups;

		// Set the group data for the user object in the session.
		$temp = JFactory::getUser();
		if ($temp->id == $userId)
		{
			$temp->groups = $user->groups;
		}

		return true;
	}

	/**
	 * Gets the user profile information
	 *
	 * @param   integer  $userId  The id of the user.
	 *
	 * @return  object
	 *
	 * @since   11.1
	 */
	public function getProfile($userId = 0)
	{
		if ($userId == 0)
		{
			$user	= JFactory::getUser();
			$userId	= $user->id;
		}

		// Get the dispatcher and load the user's plugins.
		$dispatcher	= JDispatcher::getInstance();
		JPluginHelper::importPlugin('user');

		$data = new JObject;
		$data->id = $userId;

		// Trigger the data preparation event.
		$dispatcher->trigger('onContentPrepareData', array('com_users.profile', &$data));

		return $data;
	}

	/**
	 * Method to activate a user
	 *
	 * @param   string  $activation  Activation string
	 *
	 * @return  boolean  True on success
	 *
	 * @since   11.1
	 */
	public static function activateUser($activation)
	{
		// Initialize some variables.
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		// Let's get the id of the user we want to activate
		$query->select($db->quoteName('id'));
		$query->from($db->quoteName('#__adh_adherents'));
		$query->where($db->quoteName('activation') . ' = ' . $db->quote($activation));
		$query->where($db->quoteName('block') . ' = 1');
		$query->where($db->quoteName('lastvisitDate') . ' = ' . $db->quote('0000-00-00 00:00:00'));
		$db->setQuery($query);
		$id = intval($db->loadResult());

		// Is it a valid user to activate?
		if ($id)
		{
			$user = JUser::getInstance((int) $id);

			$user->set('block', '0');
			$user->set('activation', '');

			// Time to take care of business.... store the user.
			if (!$user->save())
			{
				JError::raiseWarning("SOME_ERROR_CODE", $user->getError());
				return false;
			}
		}
		else
		{
			JError::raiseWarning("SOME_ERROR_CODE", JText::_('JLIB_USER_ERROR_UNABLE_TO_FIND_USER'));
			return false;
		}

		return true;
	}

	/**
	 * Returns userid if a user exists
	 *
	 * @param   string  $email  The email to search on.
	 *
	 * @return  integer  The user id or 0 if not found.
	 *
	 * @since   11.1
	 */
	public static function getUserId($email)
	{
		// Initialise some variables
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select($db->quoteName('id'));
		$query->from($db->quoteName('#__adh_adherents'));
		$query->where($db->quoteName('email') . ' = ' . $db->quote($email));
		$db->setQuery($query, 0, 1);
		return $db->loadResult();
	}

	/**
	 * Formats a password using the current encryption. If the user ID is given
	 * and the hash does not fit the current hashing algorithm, it automatically
	 * updates the hash.
	 *
	 * @param   string   $password  The plaintext password to check.
	 * @param   string   $hash      The hash to verify against.
	 * @param   integer  $user_id   ID of the user if the password hash should be updated
	 *
	 * @return  boolean  True if the password and hash match, false otherwise
	 *
	 * @since   3.2.1
	 */
	public static function verifyPassword($password, $hash, $user_id = 0)
	{
		$rehash = false;
		$match = false;

		// If we are using phpass
		if (strpos($hash, '$P$') === 0)
		{
			// Use PHPass's portable hashes with a cost of 10.
			$phpass = new PasswordHash(10, true);

			$match = $phpass->CheckPassword($password, $hash);

			$rehash = false;
		}
		else
		{
			// Check the password
			$parts = explode(':', $hash);
			$crypt = $parts[0];
			$salt  = @$parts[1];

			$rehash = true;

			$testcrypt = md5($password . $salt) . ($salt ? ':' . $salt : '');

			$match = JCrypt::timingSafeCompare($hash, $testcrypt);
		}

		// If we have a match and rehash = true, rehash the password with the current algorithm.
		if ((int) $user_id > 0 && $match && $rehash)
		{
			$user = new JUser($user_id);
			$user->password = self::hashPassword($password);
			$user->save();
		}

		return $match;
	}

}
