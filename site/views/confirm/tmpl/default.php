<?php

/* 
 *  Copyright (C) 2012-2015 charly
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
 
JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.tooltip');
 
// Import library dependencies
JLoader::register('ADHFunctions', JPATH_COMPONENT_ADMINISTRATOR . '/helpers/functions.php');
JLoader::register('ADHControls', JPATH_COMPONENT_ADMINISTRATOR . '/helpers/controls.php');
JLoader::register('ADHdb', JPATH_COMPONENT_ADMINISTRATOR . '/helpers/db.php');

$app	= JFactory::getApplication();
$params	= $app->getParams();

// build confirmation
$body = "";
$body .= ADHHelper::buildBulletinAdhesionUser($this->adherent->id);
$body .= ADHHelper::buildBulletinAdhesionCotiz($this->cotiz->id);
$body .= ADHHelper::buildBulletinAdhesionConfirm($this->cotiz->id);

// display confirmation
echo $body;

// send email
// will we need to send an email ? if so prepare things up
if ((int)$params->get('alert_sendmail_on_inscription_cb') == 1) {
	// @url http://docs.joomla.org/Sending_email_from_extensions
	$mailer = JFactory::getMailer();
	$config = JFactory::getConfig();
	$sender = array( 
		$config->getValue( 'config.mailfrom' ),
		$config->getValue( 'config.fromname' ) );
	$mailer->setSender($sender);
	// get members of the recipient's group
	//$groupId = JGroupHelper::getGroupId($params->alert_sendmail_on_inscription_dest);
	$group_vpn = new JGroup($params->get('alert_sendmail_on_inscription_dest'));
	$users = $group_vpn->getUsers();
	foreach ($users as $uid) {
		$user = JFactory::getUser($uid);
		$recipient = $user->email;
		// hide VPN users from recipient's list
		//$mailer->addRecipient($recipient);
		$mailer->addBCC($recipient);
		$mailer->addReplyTo($recipient);
	}
	$mailer->isHTML(true);
	$mailer->Encoding = 'base64';
	$mailer->setSubject(JText::sprintf('COM_ADH_ADHERER_MAIL_SUBJECT', JURI::base(), strtoupper($this->adherent->nom), $this->adherent->prenom));
	$mailer->setBody($body);
	$send = $mailer->Send();
	if ($send !== true) {
		$app->enqueueMessage(JText::_('COM_USERS_REGISTRATION_SEND_MAIL_FAILED'), 'Error');
		//$app->enqueueMessage('<pre>'.var_dump($mailer).'</pre>', 'Notice');
		//JError::raiseError( 4711, JText::_('COM_ADH_ADHERENT_MAIL_NOT_SENT') );
	} else {
		$app->enqueueMessage(JText::_('COM_ADH_ADHERER_MAIL_SENT'));
	}
}
?>
<!--<pre class="debug">
	<?php /* var_dump(JRequest::getVar('adhId', 0, 'get'));
	var_dump($this->adherent);
	var_dump(JRequest::getVar('cotizId', 0, 'get'));
	var_dump($this->cotiz);
	//JInput::Get();
	 * */
	?>
</pre>-->

