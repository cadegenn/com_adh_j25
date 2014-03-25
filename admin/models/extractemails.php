
<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
// import the Joomla modellist library
jimport('joomla.application.component.modellist');
/**
 * adhModelChantiers List Model
 */
class adhModelExtractEmails extends JModelList
{
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
		$query->select('DISTINCT a.nom, a.email');
		$query->from('#__adh_adherents AS a');
		$query->where('a.email <> ""');
		$query->where('a.published = 1');
		$query->order('LOWER(a.nom)');

		$year = $this->getState('year.search');
		if (!empty($year)) {
			$query->select('c.date_debut_cotiz');
			$query->from('#__adh_cotisations as c');
			$query->where('c.adherent_id = a.id AND YEAR(c.date_debut_cotiz)='.$year);
		}
				
		// filter by département
		$cp = $this->getState('cp.search');
		if (!empty($cp)) {
			// MySQL start the string at '1', not '0'
			$query->where('SUBSTRING(a.cp, 1, 2) = "'.$cp.'"');
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

		   // Load the year state.
		   $search = $this->getUserStateFromRequest($this->context.'.year.search', 'year_search');
		   $this->setState('year.search', $search);
		   $state = $this->getUserStateFromRequest($this->context.'.year.state', 'year_state', '', 'string');
		   $this->setState('year.state', $state);

		   // Load the filter state.
		   $search = $this->getUserStateFromRequest($this->context.'.cp.search', 'cp_search');
		   $this->setState('cp.search', $search);
		   $state = $this->getUserStateFromRequest($this->context.'.cp.state', 'cp_state', '', 'string');
		   $this->setState('cp.state', $state);

		   // List state information.
		   parent::populateState('#__adh_cotisations.date_debut_cotiz', 'asc');
	}

}
?>
