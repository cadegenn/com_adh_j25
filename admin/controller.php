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
	
	/*
	 * @brief	exdport()		export all data
	 * @since	0.0.47
	 */
	public function export() {
		$app    = JFactory::getApplication();
		$model	= $this->getModel();
		$export_file = $model->export();
		$app->enqueueMessage('database exported at <a href='.$export_file.'>'.$export_file.'</a>');
		$app->redirect(JRoute::_('index.php?option=com_adh'), false);
	}
}

?>
