<?php
/**
 * @package     com_adh
 * @subpackage  User
 *
 * @copyright   Copyright (C) 2013 DCA, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 * 
 * @use		how-to use this class in your component :
 * @file	In your component's admin/config.xml, add this fieldset
	<fieldset
			name="users"
			label="COM_ADH_USERS_SETTINGS_LABEL"
			description="COM_ADH_USERS_SETTINGS_DESC"
	>
		<field  name="auth_method" type="list" 
			label="COM_ADH_USERS_AUTHMETHOD_LABEL" description="COM_ADH_USERS_AUTHMETHOD_DESC" class="inputbox"
			filter="intval" size="1" default="1"
		>
			<option value="0">COM_ADH_USERS_AUTHMETHOD_NONE</option>
			<option value="1">COM_ADH_USERS_AUTHMETHOD_JOOMLA</option>
			<option value="2">COM_ADH_USERS_AUTHMETHOD_ADH</option>
			<option value="999">COM_ADH_USERS_AUTHMETHOD_CUSTOM</option>
		</field>
		<field
			name="auth_dbhost" type="text"
			label="COM_ADH_USERS_AUTHMETHOD_SETTINGS_DBHOST_LABEL" description="COM_ADH_USERS_AUTHMETHOD_SETTINGS_DBHOST_DESC"
		/>
		<field
			name="auth_dbusername" type="text"
			label="COM_ADH_USERS_AUTHMETHOD_SETTINGS_DBUSERNAME_LABEL" description="COM_ADH_USERS_AUTHMETHOD_SETTINGS_DBUSERNAME_DESC"
		/>
		<field
			name="auth_dbpasswd" type="password"
			label="COM_ADH_USERS_AUTHMETHOD_SETTINGS_DBPASSWD_LABEL" description="COM_ADH_USERS_AUTHMETHOD_SETTINGS_DBPASSWD_DESC"
		/>
		<field name="auth_dbname" type="text"
			label="COM_ADH_USERS_AUTHMETHOD_SETTINGS_DATABASE_LABEL" description="COM_ADH_USERS_AUTHMETHOD_SETTINGS_DATABASE_DESC"
		/>
		<field name="auth_tablename" type="text"
			label="COM_ADH_USERS_AUTHMETHOD_SETTINGS_TABLE_LABEL" description="COM_ADH_USERS_AUTHMETHOD_SETTINGS_TABLE_DESC"
		/>
		<field name="auth_usernamefield" type="text"
			label="COM_ADH_USERS_AUTHMETHOD_SETTINGS_USERNAMEFIELD_LABEL" description="COM_ADH_USERS_AUTHMETHOD_SETTINGS_USERNAMEFIELD_DESC"
		/>
		<field name="auth_passwordfield" type="text"
			label="COM_ADH_USERS_AUTHMETHOD_SETTINGS_PASSWORDFIELD_LABEL" description="COM_ADH_USERS_AUTHMETHOD_SETTINGS_PASSWORDFIELD_DESC"
		/>
	</fieldset>

 * @url		go to the admin page of your site, select component and go to "preferences"
 * @url		fill in these fields
 * @install	the plg_auth_adh normally found near this component
 * @url		go to extensions/plugins, enable "Authentication - Adhérents"
 * @file	when you need to access user's (e.g. adhérent's) attributes, add this to your php user's helper
 * @file
 * 
 *
 */

defined('JPATH_PLATFORM') or die;

/**
 * Authorisation helper class, provides static methods to perform various tasks relevant
 * to the Joomla user and authorisation classes
 *
 * This class has influences and some method logic from the Horde Auth package
 *
 * @package     Joomla.Platform
 * @subpackage  User
 * @since       0.0.18
 */
abstract class JAdhHelper extends JUserHelper
{

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

}
