
<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
// import the Joomla modellist library
jimport('joomla.application.component.modellist');

// Import library dependencies
JLoader::register('ADHFunctions', JPATH_COMPONENT_ADMINISTRATOR . '/helpers/functions.php');

/**
 * adhModelImportV2Adherents List Model
 */
class adhModelImportV2Adherents extends JModelList
{
    /*
     * database from wich to import data
     */
    protected $database = "";
    /*
     * hostname where lives the database
     */
    protected $db_host = "";
    /*
     * username to access database
     */
    protected $db_username = "";
    /*
     * password of the user
     */
    protected $db_passwd = "";
	/*
	 * database object
	 */
	public $_db=null;

	public function __construct($config = array()) {
		parent::__construct($config);
		
		$db = JFactory::getDbo();
		$db->setQuery('SELECT params FROM #__extensions WHERE name = "com_adh"');
		$params = json_decode( $db->loadResult(), true );
		$this->database=$params['database'];
		$this->db_host=$params['db_host'];
		$this->db_username=$params['db_username'];
		$this->db_passwd=$params['db_passwd'];

		$options = array(   "driver"    => "mysql",
							"database"  => $this->database,
							"select"    => true,
							"host"      => $this->db_host,
							"user"      => $this->db_username,
							"password"  => $this->db_passwd
				);
		$this->_db = JDatabaseMySQL::getInstance($options);
		
		if ($this->_db->getErrorNum()>0) { JFactory::getApplication()->enqueueMessage(JText::_('COM_ADH_IMPORT_DATABASE_CONNEXION_ERROR'), 'error'); }
	}

	protected function _getList($query, $limitstart = 0, $limit = 0)
	{
		if ($this->_db->getErrorNum() > 0) { return false; }
		
		$this->_db->setQuery($query, $limitstart, $limit);
		$result = $this->_db->loadObjectList();

		return $result;
	}

    /**
     * Method to build an SQL query to load the list data.
     *
     * @return	string	An SQL query
     */
    protected function getListQuery()
    {
		//echo var_dump($this->_db);
		if ($this->_db->getErrorNum() > 0) { return "SELECT 0=1"; }
		
		$query = $this->_db->getQuery(true);
		$query->select('adherents.*');
		$query->from($this->database.'.adherents');
		$query->order('nom');
		$query->order('prenom');
		$query->order('cp');
		
		$letter = $this->getState('letter.search');
		if (!empty($letter)) {
			// case sensitive
			//$query->where('(adherents.nom LIKE "%'.$search.'%")', 'OR')->where('(adherents.prenom LIKE "%'.$search.'%")');
			// case insensitive
			$query->where('(adherents.nom COLLATE utf8_unicode_ci LIKE "'.$letter.'%")', 'AND');
		}
				
		// Filter (http://docs.joomla.org/How_to_add_custom_filters_to_component_admin)
		$search = $this->getState('filter.search');
		if (!empty($search)) {
			// case sensitive
			//$query->where('(adherents.nom LIKE "%'.$search.'%")', 'OR')->where('(adherents.prenom LIKE "%'.$search.'%")');
			// case insensitive
			//$query->where('(adherents.nom COLLATE utf8_unicode_ci LIKE "%'.$search.'%")', 'OR')->where('(adherents.prenom COLLATE utf8_unicode_ci LIKE "%'.$search.'%")');
			$query->where('(adherents.nom COLLATE utf8_unicode_ci LIKE "%'.$search.'%" OR adherents.prenom COLLATE utf8_unicode_ci LIKE "%'.$search.'%")');
		}
		
		return $query;
    }
	
	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @since		1.6
	 * @see			http://docs.joomla.org/How_to_add_custom_filters_to_component_admin
	*/
	protected function populateState($ordering = null, $direction = null)
	{
		   // Initialise variables.
		   $app = JFactory::getApplication('administrator');

		   // Load the filter state.
		   $search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		   $this->setState('filter.search', $search);

		   $state = $this->getUserStateFromRequest($this->context.'.filter.state', 'filter_state', '', 'string');
		   $this->setState('filter.state', $state);

		   // Load the filter state.
		   $search = $this->getUserStateFromRequest($this->context.'.letter.search', 'letter_search');
		   $this->setState('letter.search', $search);

		   $state = $this->getUserStateFromRequest($this->context.'.letter.state', 'letter_state', '', 'string');
		   $this->setState('letter.state', $state);

		   // List state information.
		   parent::populateState('adherents.nom', 'asc');
	}

	/*
	 * Import adherents from V2 ADH database
	 */
	public function import($cid) {
		// pour afficher le debug, supprimer la redirection dans --> controllers/importv2adherents.php
		//echo("<pre>".var_dump($cid)."</pre>");
		$db = JFactory::getDbo();
		
		//foreach ($cid as $key => $id) {
			// on initialise les objets
			$query_adh = $this->_db->getQuery(true);
			$query = $db->getQuery(true);
			
			// on interroge la base V2
			//$query_adh->select('a.*, c.date AS cotiz_date')->from($this->database.'.adherents AS a')->where('a.id = '.$id);
			//$query_adh->select('c.date AS cotiz_date')->leftjoin($this->database.'.cotisations AS c ON (c.id_adherent = a.id)')->where('c.date IS NOT NULL');
			//$this->_db->setQuery($query_adh, 0, 1);
			$query_adh->select('a.*')->from($this->database.'.adherents AS a');
			if (is_array($cid)) { $query_adh->where('a.id IN ('.implode(",",$cid).')'); }
			$query_adh->select('c.date AS cotiz_date')->leftjoin($this->database.'.cotisations AS c ON (c.id_adherent = a.id)');
			$query_adh->order('cotiz_date ASC')->group('a.id');
			$this->_db->setQuery($query_adh);
			//echo("<pre>".var_dump($this->_db->getQuery())."</pre>");
			//echo("<pre>".var_dump($query_adh->__toString())."</pre>");
			$this->_db->execute();
			$rows = $this->_db->loadObjectList();
			//echo("<pre>".var_dump($rows)."</pre>");
			foreach( $rows as $row ) {
				//echo("<pre>".var_dump($row)."</pre>");
				// on duplique l'objet source
				$adherent = clone $row; //new stdClass();//$row;
				// on ajoute les nouveaux champs
				$adherent->titre = "M.";
				$adherent->personne_morale = "";
				$adherent->gsm = "";
				$adherent->url = "";
				$adherent->profession_id;
				//$adherent->published = 0;//($row->obsolete ? 2 : $adherent->published);
				$adherent->creation_date = ($row->date_creation == "0000-00-00" ? $row->cotiz_date : $row->date_creation);
				$adherent->modification_date = ($row->date_modification == "0000-00-00" ? null : $row->date_modification);
				$adherent->modified_by = 0;
				$adherent->recv_newsletter = 0;
				$adherent->recv_infos = 0;
				$adherent->origine_id = 0;
				$adherent->origine_text = "";
				// on formatte les données correctement
				$adherent->titre = ($adherent->Mr == "Mlle" ? "Mme" : $adherent->Mr);
				if (strtoupper($adherent->titre) == "ASSO" || strtoupper($adherent->titre) == "ASSOCIATION" || $adherent->titre == "Soci&eacute;t&eacute;" || $adherent->prenom == "") {
					$adherent->titre = "Société";
					$adherent->personne_morale = ADHFunctions::unEscapeString($adherent->nom);
					$adherent->nom = "";
					//echo("<pre>"); var_dump($adherent); echo("</pre>"); die();
				}
				$adherent->nom = strtoupper(ADHFunctions::unEscapeString($adherent->nom));
				$adherent->prenom = ucwords(ADHFunctions::unEscapeString($adherent->prenom));
				/*
				 *  on nettoie un peu les adresses
				 */
				//$adherent->adresse = ADHFunctions::unEscapeString($adherent->adresse);
				$adherent->adresse = $db->escape(ADHFunctions::unEscapeString($adherent->adresse));
				$adherent->adresse2 = $db->escape(ADHFunctions::unEscapeString($adherent->adresse2));
				if ($adherent->adresse2 = "1") { $adherent->adresse2 = ""; }
				if ($adherent->adresse2 = "1 1") { $adherent->adresse2 = ""; }
				if (preg_match("/".$adherent->adresse2.".*/", $adherent->adresse)) { $adherent->adresse2 = ""; }
				/*
				 * les codes postaux & ville
				 */
				// certains codes postaux sont dans le champs ville au format "code-ville"
				if (preg_match("/(?P<cp>\d+)-(?P<ville>.+)/", $adherent->ville, $matches)) {
					$adherent->cp = $matches['cp'];
					$adherent->ville = $matches['ville'];
				}
				$adherent->ville = $db->escape(ucwords($adherent->ville));
				// on ajoute un 0 devant les codes postaux français trop cours (6330 -> 06330)
				if ($adherent->pays == "France" and strlen($adherent->cp) == 4 ) { $adherent->cp = '0'.$adherent->cp; }
				/*
				 * le pays
				 */
				$adherent->pays = $db->escape(ucwords(strtolower(ADHFunctions::unEscapeString($adherent->pays))));
				$adherent->gsm = $adherent->telephone_portable;
				$adherent->profession_id = $adherent->profession;
				$adherent->recv_newsletter = $adherent->newsletter;
				$adherent->recv_infos = $adherent->recv_news_apl;
				$adherent->origine_id = $adherent->id_origine;
				$adherent->origine_text = ADHFunctions::unEscapeString($adherent->text_origine);
				$adherent->published = 1; //($adherent->enable ? 1 : $adherent->enable);
				//$adherent->fiche_custom = ($adherent->fiche_custom != '' ? "images/adh".$adherent->fiche_custom : "");
				// on supprime les attributs de trop
				unset($adherent->Mr);
				unset($adherent->telephone_portable);
				unset($adherent->profession);
				unset($adherent->date_creation);
				unset($adherent->date_modification);
				unset($adherent->enable);
				unset($adherent->newsletter);
				unset($adherent->recv_news_apl);
				unset($adherent->id_origine);
				unset($adherent->text_origine);
				unset($adherent->cotiz_date);
				//echo("<pre>".var_dump($adherent)."</pre>");
				$query = $db->getQuery(true);
				$query->delete('#__adh_adherents')->where('id = '.$adherent->id);
				$db->setQuery($query);
				$db->execute();
				// on insert le nouvel objet (qui en fait contient les anciennes data de la V2 du site)
				$db->insertObject('#__adh_adherents', $adherent, 'id');

			}
		//}
		// si une erreur survient, de toute façon, PHP aura intérompu le traitement et joomla et/ou les log apache l'auront affiché
		return true;
	}
	
	/*public function getPagination() {
		$pagination = parent::getPagination();
		$pagination->set("_viewall", true);
	}*/
}
?>