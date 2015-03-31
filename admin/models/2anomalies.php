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
// import the Joomla modellist library
jimport('joomla.application.component.modellist');
/**
 * adhModel2anomalies List Model
 */
class adhModel2anomalies extends JModelList
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
				'id', 'c.id',
				'adherent_id', 'c.adherent_id',
				'tarif_id', 'c.tarif_id',
				'montant', 'c.montant',
				'LOWER(mode_paiement), LOWER(c.mode_paiement)',
				'date', 'c.date', 'date_debut_cotiz', 'c.date_debut_cotiz', 'date_fin_cotiz', 'c.date_fin_cotiz',
				'payee', 'c.payee',
				'creation_date', 'c.creation_date',
				'modification_date', 'c.modification_date',
				'modified_by', 'c.modified_by'
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
		$query->select('a.*');
		$query->select('c.*')->from('#__adh_cotisations AS c');//, a.personne_morale AS personne_morale, a.nom AS nom, a.prenom AS prenom');
		//$query->from('#__adh_cotisations AS c, #__adh_adherents AS a');
		//$query->where('c.adherent_id = a.id');
		
		$anomalies = $this->getState('anomalies.search');
		if (!empty($anomalies)) {
			switch ($anomalies) {
				// orphans cotisations
				case 1 :	$query->where("c.adherent_id NOT IN (SELECT id from #__adh_adherents AS a)");
							break;
				// doubles cotisations
				case 2 :	$query->innerJoin('#__adh_cotisations AS d ON (c.adherent_id = d.adherent_id)')->where('YEAR(c.date_debut_cotiz) = YEAR(d.date_debut_cotiz)')->where('c.id <> d.id');
							break;
				//case 3 :	$query->select('a.')
			}
		} else {
			// do not display anything until we choose a type of abnormalities
			$query->where("0 = 1");
		}
		// joining with adherents
		$query->leftJoin('#__adh_adherents AS a ON(c.adherent_id = a.id)');

		$year = $this->getState('year.search');
		if (!empty($year)) {
			$query->where('YEAR(c.date_debut_cotiz) = '.$year);
		}
				
		// Add the list ordering clause.
		$orderCol	= $this->state->get('list.ordering', 'c.id');
		$orderDirn	= $this->state->get('list.direction', 'asc');
		$query->order($db->escape($orderCol.' '.$orderDirn));
		
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
	protected function populateState($ordering = null, $direction = null) {
		// Initialise variables.
		$app = JFactory::getApplication('administrator');

		// Load the year state.
		$search = $this->getUserStateFromRequest($this->context.'.year.search', 'year_search');
		$this->setState('year.search', $search);
		$state = $this->getUserStateFromRequest($this->context.'.year.state', 'year_state', '', 'string');
		$this->setState('year.state', $state);

		// Load the filter state.
		$search = $this->getUserStateFromRequest($this->context.'.anomalies.search', 'anomalies_search');
		$this->setState('anomalies.search', $search);
		$state = $this->getUserStateFromRequest($this->context.'.anomalies.state', 'anomalies_state', '', 'string');
		$this->setState('anomalies.state', $state);

		// List state information.
		parent::populateState('c.id', 'asc');
	}


}
?>
