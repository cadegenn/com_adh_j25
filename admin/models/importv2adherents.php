
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
	protected $_db = null;
	
	/*
	 * existing 'adherents' from database (merely already imported ones)
	 */
	public $imported = array();

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
		
		$this->imported = $this->setImported();
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
		
		// Filters already imported objects
		/* @note	code is simpler in newer version of php, so let's use it.
		 * unfortunately, OVH runs php 5.3 (5.4 is available, but since it is the production site, I will not test it yet)
		 */
		$notimported = $this->getState('notimported.search');
		if (!empty($notimported)) {
			if (PHP_VERSION_ID > 505000) {
				// php version > 5.5.0 -> we can use array_column() function
				$already_imported_id = implode("', '", array_column($this->imported, 'id'));
			} else {
				$already_imported_id = implode("', '", array_map(function ($entry) {
					return $entry->id;
				}, $this->imported));
			}
			$query->where("adherents.id NOT IN ('" . $already_imported_id . "')");
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

			// Load the filter state.
			$search = $this->getUserStateFromRequest($this->context.'.notimported.search', 'notimported_search', '', 'string');
			switch ($search) {	// browsers sets checkbox values differently... try to guess the real value
				case "on" :	$search = "checked";
							break;
				default :	$search = "";
							break;
			}
			$this->setState('notimported.search', $search);
			$state = $this->getUserStateFromRequest($this->context.'.notimported.state', 'notimported_state', '', 'string');
			switch ($state) {	// browsers sets checkbox values differently... try to guess the real value
				case "on" :	$state = "checked";
							break;
				default :	$state = "";
							break;
			}
			$this->setState('notimported.state', $state);

			// List state information.
			parent::populateState('adherents.nom', 'asc');
	}

	/**
	 * Gets the value of a user state variable and sets it in the session
	 *
	 * This is the same as the method in JApplication except that this also can optionally
	 * force you back to the first page when a filter has changed
	 *
	 * @param   string   $key        The key of the user state variable.
	 * @param   string   $request    The name of the variable passed in a request.
	 * @param   string   $default    The default value for the variable if not found. Optional.
	 * @param   string   $type       Filter for the variable, for valid values see {@link JFilterInput::clean()}. Optional.
	 * @param   boolean  $resetPage  If true, the limitstart in request is set to zero
	 *
	 * @return  The request user state.
	 *
	 * @since   0.0.32
	 * @override	because the original function return old_state if new_state is null. We need here to handle null value
	 */
	public function getUserStateFromRequest($key, $request, $default = null, $type = 'none', $resetPage = true)
	{
		$app = JFactory::getApplication();
		$old_state = $app->getUserState($key);
		$cur_state = (!is_null($old_state)) ? $old_state : $default;
		$new_state = JRequest::getVar($request, null, 'default', $type);

		if (($cur_state != $new_state) && ($resetPage))
		{
			JRequest::setVar('limitstart', 0);
		}

		// Save the new value only if it is set in this request.
		$app->setUserState($key, $new_state);

		return $new_state;
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
				$adherent->profession_id = 0;
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
	
    /**
     * setImported()		set the already imported objects
     *
     * @return	(array)		array of objects
     */
    private function setImported()
    {
		$db = JFactory::getDbo();
		
		$query = $db->getQuery(true);
		$query->select('a.id');
		$query->from('#__adh_adherents AS a');
		$query->order('nom');
		$query->order('prenom');
		$db->setQuery($query);
		$db->execute();
		
		return $db->loadObjectList();
    }
	
	/**
	 * getImported()		get the already imported objects
	 * 
	 * @return	(array)		array of objects
	 */
	public function getImported() {
		return $this->imported;
	}

}
?>
