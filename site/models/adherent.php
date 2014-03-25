<?php
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
		$adherent->date_naissance = $data['date_naissance'];
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
		$db->query();
		$adh_record = $db->loadObject();
		$adherent->id = $adh_record->id;
		
		if (is_null($adherent->id)) {	// insert new record into database
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
	 * @param	string		$data	données du formulaire d'inscription
	 * @param	integer		$adherent_id	id de l'adhérent dans la base
	 * @return	mixed		id de l'adhérent on success, false otherwise
	 */
	public function enregistrer_cotiz($data, $adherent_id) {
		$db = $this->getDbo();
		$query  = $db->getQuery(true);
		echo("<pre>"); var_dump($data); echo("</pre>");
		
		$cotiz = new stdClass();
		$cotiz->adherent_id = $adherent_id;
		$cotiz->tarif_id = $data['tarif_id'];
		$cotiz->montant = $data['montant'.$cotiz->tarif_id];
		$cotiz->mode_paiement = $data['mode_paiement'];
		$cotiz->date = date('Y-m-d H:M:S');
		$cotiz->date_debut_cotiz = date('Y-m-d');
		$cotiz->date_fin_cotiz = date("Y-m-d",strtotime($cotiz->date_debut_cotiz." +1 year"));
		$cotiz->payee = false;
		$cotiz->commentaire = $data['commentaire'];
		
		// check if adherent has already register this year
		$query->clear();
		$query->select('c.id')->from('#__adh_cotisations AS c')->where('c.adherent_id = '.$adherent_id.' AND YEAR(date_debut_cotiz) = '.date("Y",strtotime($cotiz->date_debut_cotiz)));
		$db->setQuery($query->__toString(), 0, 1);		// $query, $offset, $limit
		$db->query();
		$cotiz_record = $db->loadObject();
		$cotiz->id = $cotiz_record->id;
		
		if (is_null($cotiz->id)) {	// insert new record into database
			//echo("<pre>insert : "); var_dump($adherent); echo("</pre>"); die();
			$cotiz->creation_date = date('Y-m-d H:M:S');
			$saved = $db->insertObject('#__adh_cotisations', $cotiz);
			if ($saved) return $db->insertid();
		} else {						// update existing record into database
			//echo("<pre>update : "); var_dump($adherent); echo("</pre>"); die();
			$cotiz->modification_date = date('Y-m-d H:M:S');
			$cotiz->modified_by = $adherent_id;
			$saved = $db->updateObject('#__adh_cotisations', $cotiz, 'id');
			if ($saved) return $cotiz->id;
		}

		return $saved;
	}

	/**
	 * @brief	get all the tarifs availables
	 * 
	 */
	public function getTarifs() {
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
	}
}
