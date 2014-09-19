<?php
/**
 * @package		com_adh
 * @subpackage	
 * @brief		com_adh helps you manage the people within an association
 * @copyright	Copyright (C) 2010 - 2014 DEGENNES Charles-Antoine <cadegenn@gmail.com>
 * @license		Affero GNU General Public License version 3 or later; see LICENSE.txt
 * 
 * @TODO		
 */

/** 
 *  Copyright (C) 2012-2014 DEGENNES Charles-Antoine <cadegenn@gmail.com>
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
		//$query->select('a.*')->from('#__adh_adherents AS a')->where('a.published = 0')->order('a.creation_date DESC');
		$query->select('a.*')->from('#__adh_adherents AS a')->order('a.creation_date DESC LIMIT 10');
		$db->setQuery($query);
		
		return $db->loadObjectList();
	}

	public function getPendingPayments() {
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('c.*')->from('#__adh_cotisations AS c')->where('c.payee = 0')->order('c.date_debut_cotiz DESC LIMIT 10');
		$query->select('a.personne_morale, a.nom, a.prenom')->leftjoin('#__adh_adherents AS a ON (c.adherent_id = a.id)');
		$db->setQuery($query);
		
		return $db->loadObjectList();
	}
	
	/**
	 * @brief	getAdherents()	get some informations on all adherents
	 * @return	(array)			array of object
	 */
	public function getAdherents() {
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		//$query->select('a.*')->from('#__adh_adherents AS a')->where('a.published = 0')->order('a.creation_date DESC');
		$query->select('a.id, CONCAT_WS(" ", a.personne_morale, a.nom, a.prenom) as nom, CONCAT_WS(" ", a.adresse, a.cp, a.ville, a.pays) as address')->from('#__adh_adherents AS a')->where('a.published = 1');
		$db->setQuery($query, 0, 10);
		
		return $db->loadObjectList();
	}

	/**
	 * @brief	getComponent()	get version of component from Joomla!'s database
	 * @return	(string)		version of component
	 * @since	0.0.20
	 */
	public function getComponent() {
		// Create a new query object.
		$db = $this->getDbo();
		$query = $db->getQuery(true);

		$component = JRequest::getVar('option', '','get','string');
		// Construct the query
		$query->select('*')->from('#__extensions AS e')->where('name = "'.$component.'"');

		// Setup the query
		$db->setQuery($query->__toString(), 0, 1);

		// Return the result
		return $db->loadObject();
	}

	
}
