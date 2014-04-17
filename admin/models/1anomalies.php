
<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
// import the Joomla modellist library
jimport('joomla.application.component.modellist');
/**
 * adhModel1anomalies List Model
 */
class adhModel1anomalies extends JModelList
{
	/**
	 * Constructor.
	 *
	 * @param	array	An optional associative array of configuration settings.
	 * @see		JController
	 * @since	1.6
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'id', 'a.id',
				'personne_morale', 'a.personne_morale', 'LOWER(a.personne_morale)',
				'nom', 'a.nom', 'LOWER(a.nom)',
				'prenom', 'a.prenom', 'LOWER(a.prenom)',
				'LOWER(a.nom), LOWER(a.prenom)',
				'email', 'a.email',
				'ville', 'a.ville', 'LOWER(ville)',
				'pays', 'a.pays', 'LOWER(pays)',
				'published', 'a.published'
			);
		}

		parent::__construct($config);
	}

	/**
	 * Method to build an SQL query to load the list data.
	 *
	 * @return	string	An SQL query
	 */
	protected function getListQuery()
	{
		// Create a new query object.		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		// Select some fields
		//$query->select('id, nom, lieu, pays, catid, status')->leftJoin('adherents_categories ON adherents_categories.id = adherents.catid');;
		$query->select('a.*');
		$query->from('#__adh_adherents AS a')->where("LOWER(a.nom) = LOWER(a.prenom)")->where("a.personne_morale = ''");

		// filter by first letter
		$letter = $this->getState('letter.search');
		if (!empty($letter)) {
			// case sensitive
			//$query->where('(adherents.nom LIKE "%'.$search.'%")', 'OR')->where('(adherents.prenom LIKE "%'.$search.'%")');
			// case insensitive
			$query->where('(a.nom COLLATE utf8_unicode_ci LIKE "'.$letter.'%" OR a.personne_morale COLLATE utf8_unicode_ci LIKE "'.$letter.'%")');
		}
				
		//filter by search
		// Filter (http://docs.joomla.org/How_to_add_custom_filters_to_component_admin)
		$search = $this->getState('filter.search');
		if (!empty($search)) {
			// case sensitive
			//$query->where('(adherents.nom LIKE "%'.$search.'%")', 'OR')->where('(adherents.prenom LIKE "%'.$search.'%")');
			// case insensitive
			//$query->where('(a.nom COLLATE utf8_unicode_ci LIKE "%'.$search.'%")', 'OR')->where('(a.prenom COLLATE utf8_unicode_ci LIKE "%'.$search.'%")');
			$query->where('(a.nom COLLATE utf8_unicode_ci LIKE "%'.$search.'%" OR a.prenom COLLATE utf8_unicode_ci LIKE "%'.$search.'%" OR a.personne_morale COLLATE utf8_unicode_ci LIKE "%'.$search.'%")');
		}
		
		// filter by département
		$cp = $this->getState('cp.search');
		if (!empty($cp)) {
			// MySQL start the string at '1', not '0'
			$query->where('SUBSTRING(a.cp, 1, 2) = "'.$cp.'"');
		}
				
		// filter by ville
		$ville = $this->getState('ville.search');
		if (!empty($ville)) {
			$query->where('a.ville COLLATE utf8_unicode_ci = "'.$ville.'"');
		}
				
		// filter by pays
		$pays = $this->getState('pays.search');
		if (!empty($pays)) {
			$query->where('a.pays COLLATE utf8_unicode_ci = "'.$pays.'"');
		}
				
		// Add the list ordering clause.
		$orderCol	= $this->state->get('list.ordering', 'LOWER(nom)');
		$orderDirn	= $this->state->get('list.direction', 'asc');
		$query->order($db->escape($orderCol.' '.$orderDirn));
		//$query->order('catid');
		//$query->order('pays');
		/*$query->order('LOWER(nom)');*/
		
		// quelque soit l'ordre demandé, on ajoute le classement par NOM et prénom
		$query->order('LOWER(nom) '.$orderDirn);
		$query->order('LOWER(prenom) '.$orderDirn);

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
		   $search = $this->getUserStateFromRequest($this->context.'.cp.search', 'cp_search');
		   $this->setState('cp.search', $search);
		   $state = $this->getUserStateFromRequest($this->context.'.cp.state', 'cp_state', '', 'string');
		   $this->setState('cp.state', $state);

		   // Load the filter state.
		   $search = $this->getUserStateFromRequest($this->context.'.ville.search', 'ville_search');
		   $this->setState('ville.search', $search);
		   $state = $this->getUserStateFromRequest($this->context.'.ville.state', 'ville_state', '', 'string');
		   $this->setState('ville.state', $state);

		   // Load the filter state.
		   $search = $this->getUserStateFromRequest($this->context.'.pays.search', 'pays_search');
		   $this->setState('pays.search', $search);
		   $state = $this->getUserStateFromRequest($this->context.'.pays.state', 'pays_state', '', 'string');
		   $this->setState('pays.state', $state);

		   // List state information.
		   parent::populateState('a.nom, a.prenom', 'asc');
	}


}
?>
