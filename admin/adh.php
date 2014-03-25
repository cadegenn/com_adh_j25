<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// Set some global property
$document = JFactory::getDocument();
//$document->addStyleDeclaration('.icon-48-categories, .icon-48-adh {background-image: url(components/com_adh/images/ico-48x48/adh.png);}');
//$document->addStyleDeclaration('.ico-32-import {background-image: url(components/com_adh/images/ico-128x128/import-icon.png); width:32px;}');
$document->addStyleSheet( 'components/com_adh/css/com_adh.css' );

// require helper file
JLoader::register('adhHelper', dirname(__FILE__).'/helpers/adh.php');

// import joomla controller library
jimport('joomla.application.component.controller');
 
// Get an instance of the controller prefixed by chantiers
$controller = JController::getInstance('adh');
 
// Perform the Request task
$controller->execute(JRequest::getCmd('task'));
 
// Redirect if set by the controller
$controller->redirect();

?>
