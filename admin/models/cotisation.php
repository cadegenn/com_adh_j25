<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla modelform library
jimport('joomla.application.component.modeladmin');
JLoader::register('AdhCotiz', JPATH_COMPONENT_ADMINISTRATOR . '/helpers/cotiz.php');

/**
 * cotisation Model
 */
class adhModelCotisation extends JModelAdmin
{
	/**
	 * Returns a reference to the a Table object, always creating it.
	 *
	 * @param	type	The table type to instantiate
	 * @param	string	A prefix for the table class name. Optional.
	 * @param	array	Configuration array for model. Optional.
	 * @return	JTable	A database object
	 * @since	2.5
	 */
	public function getTable($type = 'cotisation', $prefix = 'adhTable', $config = array()) 
	{
		return JTable::getInstance($type, $prefix, $config);
	}
	/**
	 * Method to get the record form.
	 *
	 * @param	array	$data		Data for the form.
	 * @param	boolean	$loadData	True if the form is to load its own data (default case), false if not.
	 * @return	mixed	A JForm object on success, false on failure
	 * @since	2.5
	 */
	public function getForm($data = array(), $loadData = true) 
	{
		// Get the form.
		$form = $this->loadForm('com_adh.cotisation', 'cotisation' /* --> models/forms/cotisation.xml */,
		                        array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) 
		{
			return false;
		}
		return $form;
	}
	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return	mixed	The data for the form.
	 * @since	2.5
	 */
	protected function loadFormData() 
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_adh.edit.cotisation.data', array());
		if (empty($data)) 
		{
			$data = $this->getItem();
		}
		return $data;
	}

	/**
	 * Method to get a single record.
	 *
	 * @param   integer  $pk  The id of the primary key.
	 *
	 * @return  mixed	Object on success, false on failure.
	 *
	 * @since   0.0.30
	 */
	public function getItem($pk = null)	{
		// first fetch result from JModelAdmin to get the id
		$result = parent::getItem($pk);
		// then fetch the entire object from the helper class AdhCotiz
		return new AdhCotiz($result->id);
	}

	/**
	 * Method to save the form data.
	 *
	 * @param   array  $data  The form data.
	 *
	 * @return  boolean  True on success.
	 *
	 * @from	UsersModelUser
	 * @since   0.0.30
	 */
	public function save($data)
	{
		// Initialise variables;
		$pk			= (!empty($data['id'])) ? $data['id'] : (int) $this->getState('cotiz.id');
		$cotiz		= AdhCotiz::getInstance($pk);

		// Bind the data.
		if (!$cotiz->bind($data))
		{
			$this->setError($cotiz->getError());

			return false;
		}

		// Store the data.
		if (!$cotiz->save())
		{
			$this->setError($cotiz->getError());

			return false;
		}

		$this->setState('cotiz.id', $cotiz->id);

		return true;
	}

	/**
	 * 
	 * @param type $cid		array	array of ID to update
	 * @param type $value	int		value
	 */
	public function publish(&$pks, $value = 1){
		$db = &JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->update('#__adh_cotisations')->set('payee = '.$value);
		foreach ($pks as $id) {
			$query->where('id='.$id,'OR');
		}
		$db->setQuery($query);
		return $db->query();
	} 
	/**
	 * 
	 * @param type $cid		array	array of ID to update
	 * @param type $value	int		value
	 */
	/*public function unpublish($cid, $value){
		$db = &JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->update('#__adh_cotisations')->set('payee = '.$value);
		foreach ($cid as $id) {
			$query->where('id='.$id,'OR');
		}
		$db->setQuery($query);
		return $db->query();
	}
	 */
}
