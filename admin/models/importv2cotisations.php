
<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
// import the Joomla modellist library
jimport('joomla.application.component.modellist');

// Import library dependencies
JLoader::register('ADHFunctions', JPATH_COMPONENT_ADMINISTRATOR . '/helpers/functions.php');

/**
 * adhModelImportV2Cotisations List Model
 */
class adhModelImportV2Cotisations extends JModelList
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
		// Select some fields
		$query->select('cotisations.*, adherents.nom as nom, adherents.prenom as prenom, tarifs.tarif as tarif, tarifs.symbol as symbol, configure_paiement.label as mode_paiement');
		// From the hello table
		$query->from($this->database.'.cotisations');
		$query->leftJoin($this->database.'.adherents ON cotisations.id_adherent = adherents.id');
		$query->leftJoin($this->database.'.tarifs ON cotisations.id_tarif = tarifs.id');
		$query->leftJoin($this->database.'.configure_paiement ON cotisations.mode_paiement = configure_paiement.id');
		$query->order('nom, prenom');
		$query->order('date');
		//$query->order('pays');
		//$query->order('nom');

		// Filter (http://docs.joomla.org/How_to_add_custom_filters_to_component_admin)
		$search = $this->getState('year.search');
		if (!empty($search)) {
			// case sensitive
			//$query->where('(adherents.nom LIKE "%'.$search.'%")', 'OR')->where('(adherents.prenom LIKE "%'.$search.'%")');
			// case insensitive
			//$query->where('(adherents.nom COLLATE utf8_unicode_ci LIKE "%'.$search.'%")', 'OR')->where('(adherents.prenom COLLATE utf8_unicode_ci LIKE "%'.$search.'%")');
			$query->where('YEAR(DATE) = "'.$search.'"');
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
		   $search = $this->getUserStateFromRequest($this->context.'.year.search', 'year_search');
		   $this->setState('year.search', $search);

		   $state = $this->getUserStateFromRequest($this->context.'.year.state', 'year_state', '', 'string');
		   $this->setState('year.state', $state);

		   // List state information.
		   parent::populateState('cotisations.date', 'asc');
	}

	/*
	 * Import chantiers from V2 ADH database
	 */
	public function import($cid) {
		// pour afficher le debug, supprimer la redirection dans --> controllers/importv2chantierscategories.php
		//echo("<pre>".var_dump($cid)."</pre>");
		$db = JFactory::getDbo();
		$user = JFactory::getUser(); 
		
		//foreach ($cid as $key => $id) {
			// on initialise les objets
			$query_adh = $this->_db->getQuery(true);
			$query = $db->getQuery(true);
			
			// on interroge la base V2
			$query_adh->select('c.*, configure_paiement.label as mode_paiement')->from($this->database.'.cotisations as c');
			if (is_array($cid)) {
				$query_adh->where('c.id IN ('.implode(",",$cid).')');
			} else {
				$query_adh->where('c.id_adherent > 0');
			}
			$query_adh->leftJoin($this->database.'.configure_paiement ON c.mode_paiement = configure_paiement.id');
			$query_adh->where('c.date IS NOT NULL');
			//$query_adh->where('cotisations.id = '.$id);
			$this->_db->setQuery($query_adh);
			//echo("<pre>".var_dump($this->_db->getQuery()->toString())."</pre>"); die();
			$this->_db->execute();
			$rows = $this->_db->loadObjectList();
			//echo("<pre>".var_dump($rows)."</pre>");
			foreach( $rows as $row ) {
				//echo("<pre>".var_dump($row)."</pre>");
				// on duplique l'objet source
				$cotisation = clone $row; //new stdClass();//$row;
				// on ajoute les nouveaux champs
				/*$cotisation->published = 0;//($row->obsolete ? 2 : $categorie->published);
				$cotisation->creation_date = null;
				$cotisation->modification_date = null;
				$cotisation->modified_by = null;*/
				// on formatte les données correctement
				$cotisation->adherent_id = $cotisation->id_adherent;
				$cotisation->tarif_id = $cotisation->id_tarif;
				$cotisation->mode_paiement = strtolower(ADHFunctions::unEscapeString($cotisation->mode_paiement));
				$cotisation->date_debut_cotiz = (is_null($cotisation->date) ? date("Y")."-01-01" : $cotisation->date);
				//$cotisation->date_fin_cotiz = date_add($cotisation->date, date_interval_create_from_date_string('1 year'));
				$cotisation->date_fin_cotiz = date("Y-m-d",strtotime($cotisation->date_debut_cotiz." +1 year"));
				// on suppose que toute cotisation trouvée dans la base de données a été payée, sauf celles dont la date est NULL
				$cotisation->payee = !(is_null($cotisation->date));
				$cotisation->creation_date = (is_null($cotisation->date) ? date("Y")."-01-01" : $cotisation->date);
				$cotisation->modification_date = null;
				$cotisation->modified_by = null;
				//$cotisation->mode_paiement = "";
				// on supprime les attributs de trop
				unset($cotisation->id_adherent);
				unset($cotisation->id_tarif);
				//echo("<pre>".var_dump($categorie)."</pre>");
				// on supprime les id en conflit
				$query = $db->getQuery(true);
				$query->delete('#__adh_cotisations')->where('id = '.$cotisation->id);
				$db->setQuery($query);
				$db->execute();
				// on insert le nouvel objet (qui en fait contient les anciennes data de la V2 du site)
				$db->insertObject('#__adh_cotisations', $cotisation );
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
