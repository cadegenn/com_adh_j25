<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla modelform library
jimport('joomla.application.component.modeladmin');
 
/**
 * adherent Model
 */
class adhModelAdh extends JModelAdmin
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
	public function getTable($type = 'adherent', $prefix = 'adhTable', $config = array()) 
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
		$form = $this->loadForm('com_adh.adherent', 'adherent' /* --> models/forms/adherent.xml */,
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
		$data = JFactory::getApplication()->getUserState('com_adh.edit.adherent.data', array());
		if (empty($data)) 
		{
			$data = $this->getItem();
		}
		return $data;
	}

	/**
	 * Surcharge de la méthode save
	 * essentiellement pour formater correctement les données
	 * avant injection dans MySQL
	 */
	/*public function save($data) {
		echo("<pre>".var_dump($data)."</pre>");
		$data->nom = htmlentities($data->nom, ENT_QUOTES, 'UTF-8');
		echo("<pre>".var_dump($data)."</pre>");
		die();
	}*/
	
	public function getStatsCotizByYear() {
		$db = JFactory::getDbo();
		//$query = $db->getQuery(true);
		//$query->select('YEAR(date_debut_cotiz) AS year, Count(*) AS nb')->from('#__adh_cotisations AS c')->where('c.payee = 1')->group('YEAR(date_debut_cotiz)');
		$query = "	SELECT YEAR(c.date_debut_cotiz) AS year, Count(*) AS nb, x.primo
					FROM #__adh_cotisations AS c
					LEFT JOIN (
						SELECT YEAR(a.creation_date) AS mydate, Count(*) AS primo 
						FROM #__adh_adherents a
						GROUP BY mydate
					) x ON x.mydate = YEAR(c.date_debut_cotiz)
					WHERE YEAR( c.date_debut_cotiz ) !=0
					GROUP BY YEAR(c.date_debut_cotiz)
				";
		$db->setQuery($query);
		
		return $db->loadObjectList();
	}
	
	public function getOnlineRegistrations() {
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('a.*')->from('#__adh_adherents AS a')->where('a.published = 0')->order('a.creation_date DESC');
		$db->setQuery($query);
		
		return $db->loadObjectList();
	}

	public function getPendingPayments() {
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('c.*')->from('#__adh_cotisations AS c')->where('c.payee = 0')->order('c.date_debut_cotiz DESC');
		$query->select('a.personne_morale, a.nom, a.prenom')->leftjoin('#__adh_adherents AS a ON (c.adherent_id = a.id)');
		$db->setQuery($query);
		
		return $db->loadObjectList();
	}
}
