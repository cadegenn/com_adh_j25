<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla controlleradmin library
jimport('joomla.application.component.controlleradmin');
 
/**
 * adherents Controller
 */
class adhControllerCotisations extends JControllerAdmin
{
	/**
	 * Proxy for getModel.
	 * @since	2.5
	 */
	public function getModel($name = 'cotisation', $prefix = 'adhModel') 
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}
	
	/**
	 * 
	 * @param type $config
	 * 
	 * @see	http://joomla-answers.blogspot.fr/2012/06/joomla-25-extend-jgridpublished-column.html
	 */
	/*public function __construct($config = array()) {
		parent::__construct($config);
		$this->registerTask('unpaid', 'paid');
	}*/
	/**
	 * Followed by the function which will call the model to update the status:
	 * @see	http://joomla-answers.blogspot.fr/2012/06/joomla-25-extend-jgridpublished-column.html
	 */
	/*function paid() {
		$ids	= JRequest::getVar('cid', array(), '', 'array');
		$values = array('paid' => 1, 'unpaid' => 0);
		$task   = $this->getTask();
		$value  = JArrayHelper::getValue($values, $task, 0, 'int');
		$model	= $this->getModel();
		if (!$model->paid($ids, $value)) {	// -> helpers/cotisations.php : JHtmlCotisations::paid()
			JError::raiseWarning(500, $model->getError());
		}
		$this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_list . $extensionURL, false));
	}*/

}
?>
