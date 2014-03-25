<?php
 
defined('JPATH_BASE') or die;
 
jimport('joomla.html.html');
jimport('joomla.form.formfield');
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('groupedlist');
 
/**
 * Custom Field class for the Joomla Framework.
 *
 * @package             Joomla.Administrator
 * @subpackage          com_adh
 * @since               0.0.11
 */
class JFormFieldAdherent extends JFormFieldGroupedList
{
	/**
	 * The form field type.
	 *
	 * @var         string
	 * @since       0.0.11
	 */
	protected $type = 'adherent';

	/**
	 * Method to get the field input markup for a generic list.
	 * Use the multiple attribute to enable multiselect.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   11.1
	 */
	public function getGroups() {
		// Initialize variables.
		$groups = array();
		$alphabet = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
		foreach ($alphabet as $letter) {
			$results = $this->getOptions($letter);
			foreach ($results as $key => $option) { // $option is an object : myColumn->value
				list($id, $personne_morale, $nom, $prenom, $ville, $pays) = explode('|', $option->myColumn, 6);
				if (!$letter) { $letter = '#'; }
				$personne_morale = html_entity_decode($personne_morale, ENT_QUOTES, 'UTF-8');
				$nom = html_entity_decode($nom, ENT_QUOTES, 'UTF-8');
				$prenom = html_entity_decode($prenom, ENT_QUOTES, 'UTF-8');
				$ville = html_entity_decode($ville, ENT_QUOTES, 'UTF-8');
				$pays = html_entity_decode($pays, ENT_QUOTES, 'UTF-8');
				if (!isset($groups[$letter])) {
						$groups[$letter] = array();
				}
				$groups[$letter][$id] = JHTML::_('select.option', $id, ($personne_morale ? $personne_morale : $nom." ".$prenom)." - ".$ville.", ".$pays, 'value', 'text', false);
			}
		}
		
		reset($groups);
		return $groups;
	}

		/**
	 * Method to get the field options.
	 *
	 * @return      array   The field option objects.
	 * @since       1.6
	 */	
	public function getOptions($letter = "") {
		// Initialize variables.
		$options = array();

		$db     = JFactory::getDbo();
		$query  = $db->getQuery(true);

		//$query->select('id AS value, label AS text'); // => important !! AS value pour l'id et AS text pour le texte de l'option
		$query->select('CONCAT_WS("|",id,personne_morale,nom,prenom,ville,pays) as myColumn');
		$query->from('#__adh_adherents');
		if ($letter != "") { $query->where('(#__adh_adherents.nom COLLATE utf8_unicode_ci LIKE "'.$letter.'%" OR #__adh_adherents.personne_morale COLLATE utf8_unicode_ci LIKE "'.$letter.'%")'); }
		//$query->where('published = 1');
		$query->order('personne_morale, nom, prenom, ville ASC');

		// Get the options.
		$db->setQuery($query);

		$options = $db->loadObjectList();

		// Check for a database error.
		if ($db->getErrorNum()) {
			JError::raiseWarning(500, $db->getErrorMsg());
		}

		return $options;
	}

	public function getGroupsOld()
	{
		// Initialize variables.
		$groups = array();
		$results = $this->getOptions();
		//var_dump($results);
		$alphabet = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
		foreach ($results as $key => $option) { // $option is an object : myColumn->value
			list($id, $personne_morale, $nom, $prenom, $ville, $pays) = explode('|', $option->myColumn, 6);
			if ($personne_morale) {
				$letter = strtoupper(substr($personne_morale,0,1));
			} else {
				$letter = strtoupper(substr($nom,0,1));
			}
			if (!$letter) { $letter = '#'; }
			$personne_morale = html_entity_decode($personne_morale, ENT_QUOTES, 'UTF-8');
			$nom = html_entity_decode($nom, ENT_QUOTES, 'UTF-8');
			$prenom = html_entity_decode($prenom, ENT_QUOTES, 'UTF-8');
			$ville = html_entity_decode($ville, ENT_QUOTES, 'UTF-8');
			$pays = html_entity_decode($pays, ENT_QUOTES, 'UTF-8');
			if (!isset($groups[$letter])) {
					$groups[$letter] = array();
			}
			$groups[$letter][$id] = JHTML::_('select.option', $id, ($personne_morale ? $personne_morale : $nom." ".$prenom)." - ".$ville.", ".$pays, 'value', 'text', false);
		}

		reset($groups);

		return $groups;
	}

	public function getOptionsOld($letter = "")
	{
		// Initialize variables.
		$options = array();

		$db     = JFactory::getDbo();
		$query  = $db->getQuery(true);

		//$query->select('id AS value, label AS text'); // => important !! AS value pour l'id et AS text pour le texte de l'option
		$query->select('CONCAT_WS("|",id,personne_morale,nom,prenom,ville,pays) as myColumn');
		$query->from('#__adh_adherents');
		//$query->where('published = 1'); //->where('categorie = "'.$categorie.'"');
		$query->order('nom, prenom, ville ASC');

		// Get the options.
		$db->setQuery($query);

		$options = $db->loadObjectList();

		// Check for a database error.
		if ($db->getErrorNum()) {
						JError::raiseWarning(500, $db->getErrorMsg());
		}

		return $options;
	}
}