<?php
/**
 * @package		com_adh
 * @subpackage	
 * @brief		com_adh helps you manage the people within an association
 * @copyright	Copyright (C) 2010 - 2014 DEGENNES Charles-Antoine <cadegenn@gmail.com>
 * @license		Affero GNU General Public License version 3 or later; see LICENSE.txt
 * 
 * @TODO		enregistrer_cotiz(): should be in admin/helpers/cotiz.php::save()
 * @TODO		adherer(): saving adherent should go in admin/helpers/user-adh.php::save()
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
 
// Include dependancy of the main model form
jimport('joomla.application.component.modelform');
// import Joomla modelitem library
jimport('joomla.application.component.modelitem');
// Include dependancy of the dispatcher
jimport('joomla.event.dispatcher');

/**
 * Adherer Model
 */
class adhModelAdherent extends JModelForm
{
	/**
	 * @var object item
	 */
	protected $item;

	/**
	 * Get the data for a new qualification
	 */
	public function getForm($data = array(), $loadData = true)
	{

		$app = JFactory::getApplication('site');

		// Get the form.
		$form = $this->loadForm('com_adh.adherent', 'adherent', array('control' => 'jform', 'load_data' => true));
		if (empty($form)) {
			return false;
		}
		return $form;

	}
 
	/**
	 * Get the message
	 * @return object The message to be displayed to the user
	 */
	function &getItem()
	{
		if (!isset($this->_item))
		{
			$cache = JFactory::getCache('com_adh', '');
			$id = $this->getState('adherent.id');
			$this->_item =  $cache->get($id);
			if ($this->_item === false) {
				$db = $this->getDbo();
				$query  = $db->getQuery(true);
				$query->select('*')->from('#__adh_adherents')->where('id = 5');
				$db->setQuery((string)$query);
				$db->query();
				$this->_item = $db->loadObject();
		   }
		}
		return $this->_item;

	}
 
	/**
	 * 
	 * @param type $data	données du formulaire d'inscription
	 * @return type			id de l'adhérent on success, false otherwise
	 */
	public function adherer($data) {
		// set the data into a query to update the record
		$db = $this->getDbo();
		$query  = $db->getQuery(true);
		//echo("<pre>"); var_dump($data); echo("</pre>");

		// build object
		$adherent = new stdClass();
		$adherent->id = 0;
		$adherent->titre = $data['titre'];
		$adherent->personne_morale = $data['personne_morale'];
		$adherent->nom = strtoupper($data['nom']);
		$adherent->prenom = ucwords($data['prenom']);
		//$adherent->date_naissance = $data['date_naissance'];
		$myDateTime = DateTime::createFromFormat('d/m/Y', $data['date_naissance']);
		$adherent->date_naissance = $myDateTime->format('Y-m-d');
		$adherent->profession_id = $data['profession_id'];
		$adherent->email = $data['email'];
		$adherent->telephone = $data['telephone'];
		$adherent->gsm = $data['gsm'];
		$adherent->adresse = $data['adresse'];
		$adherent->adresse2 = $data['adresse2'];
		$adherent->cp = $data['cp'];
		$adherent->ville = $data['ville'];
		$adherent->pays = $data['pays'];
		$adherent->creation_date = date("Y-m-d");
		$adherent->published = 0;
		$adherent->origine_id = $data['origine_id'];
		$adherent->origine_text = $data['origine_text'];
		$adherent->imposable = $data['imposable'];
		$adherent->recv_newsletter = 0;		// by default, do not receive newsletter
		$adherent->recv_infos = 1;			// by default DO receive important informations

		// check if adherent already exist into database
		$query->clear();
		$query->select('id')->from('#__adh_adherents')->where('UPPER(nom) = UPPER("'.$adherent->nom.'") AND UPPER(prenom) = UPPER("'.$adherent->prenom.'") AND UPPER(email) = UPPER("'.$adherent->email.'") AND date_naissance = "'.$adherent->date_naissance.'"');
		$db->setQuery($query->__toString(), 0, 1);		// $query, $offset, $limit
		if ($db->query()) {
			$adh_record = $db->loadObject();
			$adherent->id = $adh_record->id;
		}
		
		if ($adherent->id == 0) {	// insert new record into database
			//echo("<pre>insert : "); var_dump($adherent); echo("</pre>"); die();
			$saved = $db->insertObject('#__adh_adherents', $adherent);
			if ($saved) return $db->insertid();
		} else {						// update existing record into database
			//echo("<pre>update : "); var_dump($adherent); echo("</pre>"); die();
			$saved = $db->updateObject('#__adh_adherents', $adherent, 'id');
			if ($saved) return $adherent->id;
		}

		return $saved;
		
		/*if (!$db->query()) {
			JError::raiseError(500, $db->getErrorMsg());
			return false;
		} else {
			return true;
		}*/
	}
	
	/**
	 * @brief	record cotisation that adherent will pay / has paid
	 * 
	 * @param	string		$data	data from registration form
	 * @param	integer		$adherent_id	id user from database
	 * @return	mixed		id of cotiz on success, false otherwise
	 */
	public function enregistrer_cotiz($data, $adherent_id) {
		$db = $this->getDbo();
		$query  = $db->getQuery(true);
		//echo("<pre>"); var_dump($data); echo("</pre>");
		
		$cotiz = new stdClass();
		$cotiz->id = 0;
		$cotiz->adherent_id = $adherent_id;
		$cotiz->tarif_id = $data['tarif_id'];
		$cotiz->montant = $data['montant'.$cotiz->tarif_id];
		$cotiz->mode_paiement = $data['mode_paiement'];
		$cotiz->date = date('Y-m-d H:M:S');
		$cotiz->date_debut_cotiz = date('Y-m-d');
		$cotiz->date_fin_cotiz = date("Y-m-d",strtotime($cotiz->date_debut_cotiz." +1 year"));
		$cotiz->payee = false;
		//$cotiz->commentaire = $data['commentaire'];
		
		// check if adherent has already register this year
		$query->clear();
		$query->select('c.id')->from('#__adh_cotisations AS c')->where('c.adherent_id = '.$adherent_id.' AND YEAR(date_debut_cotiz) = '.date("Y",strtotime($cotiz->date_debut_cotiz)));
		$db->setQuery($query, 0, 1);		// $query, $offset, $limit
		if ($db->query()) {
			$cotiz_record = $db->loadObject();
			$cotiz->id = $cotiz_record->id;
		}
		if ($cotiz->id == 0) {	// insert new record into database
			//echo("<pre>insert : "); var_dump($cotiz); echo("</pre>"); die();
			$cotiz->creation_date = date('Y-m-d H:M:S');
			$saved = $db->insertObject('#__adh_cotisations', $cotiz);
			if ($saved) { return $db->insertid(); }
		} else {						// update existing record into database
			//echo("<pre>update : "); var_dump($cotiz); echo("</pre>"); die();
			$cotiz->modification_date = date('Y-m-d H:M:S');
			$cotiz->modified_by = $adherent_id;
			// users can not update their own cotiz, only staff can do that
			//$saved = $db->updateObject('#__adh_cotisations', $cotiz, 'id');
			//if ($saved) { return $cotiz->id; }
			return $cotiz->id;
		}

		return $saved;
	}

	/**
	 * @brief	get all the tarifs availables
	 *			use helper @since 0.0.20
	 */
	public function getTarifs() {
		return ADHHelper::getTarifs();
		/*
		// Create a new query object.		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		// Select some fields
		$query->select('*');
		// From the hello table
		$query->from('#__adh_tarifs');
		$query->where('published = 1');
		$query->order('tarif ASC');
		
		$db->setQuery($query);
		$db->execute();
		return $db->loadObjectList();
		 * 
		 */
	}
}
