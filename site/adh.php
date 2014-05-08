<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// require helper file
JLoader::register('adhHelper', JPATH_COMPONENT_ADMINISTRATOR.'/helpers/adh.php');
// joomla lacks a group object with suitable methods like it have for users
// we will not use it here however
JLoader::register('JGroup', JPATH_COMPONENT_ADMINISTRATOR.'/helpers/group-joomla.php');
JLoader::register('JGroupHelper', JPATH_COMPONENT_ADMINISTRATOR.'/helpers/group-joomla-helper.php');
JLoader::register('AdhUser', JPATH_COMPONENT_ADMINISTRATOR.'/helpers/user-adh.php');
JLoader::register('AdhUserHelper', JPATH_COMPONENT_ADMINISTRATOR.'/helpers/user-adh-helper.php');
JLoader::register('AdhCotiz', JPATH_COMPONENT_ADMINISTRATOR.'/helpers/cotiz.php');
JLoader::register('JContact', JPATH_COMPONENT_ADMINISTRATOR.'/helpers/contact.php');

// import joomla controller library
jimport('joomla.application.component.controller');
 
// Get an instance of the controller prefixed by chantiers
$controller = JController::getInstance('adh');
 
// Perform the Request task
$controller->execute(JRequest::getCmd('task'));
 
// Redirect if set by the controller
$controller->redirect();

?>
