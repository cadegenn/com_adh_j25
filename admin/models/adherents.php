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
JLoader::register('AdhCotiz', JPATH_COMPONENT_ADMINISTRATOR . '/helpers/cotiz.php');

/**
 * adhModelAdherents List Model
 */
class adhModelAdherents extends JModelList
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
				'cp', 'ville', 'a.ville', 'LOWER(ville)',
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
		$query->from('#__adh_adherents AS a');
		/*
		 * too slow
		// add some cotisation informations
		$query->select('c.date_debut_cotiz, c.date_fin_cotiz, c.payee');
		$query->leftjoin('#__adh_cotisations AS c ON (c.adherent_id = a.id)');
		$query->where('c.date_debut_cotiz = (SELECT MAX(date_debut_cotiz) FROM #__adh_cotisations AS c2 WHERE c2.adherent_id = a.id)');
		*/

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
		// le COLLATE 'utf8_general_ci' permet de spécifier que ÉÊÈ = E c'est à dire que l'ordre sera correct quelque soit les accents
		$query->order("LOWER(nom) COLLATE 'utf8_general_ci' ".$orderDirn);
		$query->order("LOWER(prenom) COLLATE 'utf8_general_ci' ".$orderDirn);

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
