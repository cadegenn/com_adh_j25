
<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
// import the Joomla modellist library
jimport('joomla.application.component.modellist');
/**
 * adhModelImportV2Professions List Model
 */
class adhModelImportV2Professions extends JModelList
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
		$query->select('*');
		// From the hello table
		$query->from($this->database.'.professions');
		//$query->leftJoin($this->database.'.categories_chantier ON categories_chantier.id = chantiers.categorie');
		$query->order('id');
		//$query->order('pays');
		//$query->order('nom');
		return $query;
    }

	/*
	 * Import chantiers from V2 ADH database
	 */
	public function import($cid) {
		// pour afficher le debug, supprimer la redirection dans --> controllers/importv2chantierscategories.php
		//echo("<pre>".var_dump($cid)."</pre>");
		$db = JFactory::getDbo();
		$user = JFactory::getUser(); 
		
		foreach ($cid as $key => $id) {
			// on initialise les objets
			$query_adh = $this->_db->getQuery(true);
			$query = $db->getQuery(true);
			
			// on interroge la base V2
			$query_adh->select('professions.*')->from($this->database.'.professions')->where('professions.id = '.$id);
			$this->_db->setQuery($query_adh);
			//echo("<pre>".var_dump($this->_db->getQuery())."</pre>");
			$this->_db->execute();
			$rows = $this->_db->loadObjectList();
			//echo("<pre>".var_dump($rows)."</pre>");
			foreach( $rows as $row ) {
				//echo("<pre>".var_dump($row)."</pre>");
				// on duplique l'objet source
				$profession = clone $row; //new stdClass();//$row;
				// on ajoute les nouveaux champs
				$profession->published = 0;//($row->obsolete ? 2 : $categorie->published);
				$profession->creation_date = null;
				$profession->modified_by = $user->id;
				// on formatte les données correctement
				$profession->published = ($row->enable ? 1 : $profession->published);
				// on supprime les attributs de trop
				unset($profession->enable);
				//echo("<pre>".var_dump($categorie)."</pre>");
				// on supprime les id en conflit
				$query->delete('#__adh_professions')->where('id = '.$profession->id);
				$db->setQuery($query);
				$db->execute();
				// on insert le nouvel objet (qui en fait contient les anciennes data de la V2 du site)
				$db->insertObject('#__adh_professions', $profession );
			}
		}
		// si une erreur survient, de toute façon, PHP aura intérompu le traitement et joomla et/ou les log apache l'auront affiché
		return true;
	}
	
	/*public function getPagination() {
		$pagination = parent::getPagination();
		$pagination->set("_viewall", true);
	}*/
}
?>
