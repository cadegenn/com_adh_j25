
<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
// import the Joomla modellist library
jimport('joomla.application.component.modellist');

// Import library dependencies
JLoader::register('ADHFunctions', JPATH_COMPONENT_ADMINISTRATOR . '/helpers/functions.php');

/**
 * adhModelImports List Model
 */
class adhModelConfig extends JModelList
{
}
?>
