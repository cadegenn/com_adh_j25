<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla controller library
jimport('joomla.application.component.controller');
 
/**
 * General Controller of chantiers component
 */
class adhController extends JController
{
	/**
	 * display task
	 *
	 * @return void
	 */
	function display($cachable = false, $urlparams = false) {
		// set default view if not set
		//JRequest::setVar('view', JRequest::getCmd('view', 'apl'));
		$input = JFactory::getApplication()->input;
		$input->set('i', $input->getCmd('view', 'adh'));
 
		// call parent behavior
		parent::display($cachable, $urlparams);
		
		//aplHelper::addSubmenu('chantiers');	// => admin/helpers/chantiers.php
	}
	
	/**
	 * @brief	getUsersFromGroup()		Method to retrieve members of a given group
	 * @param	(integer)				$groupId id of a group in the database
	 * @return	(array)					json-encoded array of users's ids members of the groupId
	 * @deprecated since version 0.0.22
	 */
	/*public function getUsersFromGroup($groupId = 0) {
		// pour debug, on prend d'abord la valeur via la méthode 'get', puis on surcharge par une éventuelle donnée dans le 'post'
		//$groupId = intval(JRequest::getVar('groupid', 0, 'get', 'int'));		
		$groupId = JRequest::getVar('groupid', $groupId, 'post', 'int');		
		$group = new JGroup($groupId);
		$users = $group->getUsers();
		
		echo json_encode($users);
	}*/
}

?>
