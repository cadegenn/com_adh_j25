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
class JFormFieldOrigine extends JFormFieldGroupedList
{
	/**
	 * The form field type.
	 *
	 * @var         string
	 * @since       0.0.11
	 */
	protected $type = 'origine';

	/**
	 * Method to get the field input markup for a generic list.
	 * Use the multiple attribute to enable multiselect.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   11.1
	 */
	public function getGroups()
	{
		// Initialize variables.
		$groups = array();
		$results = $this->getOptions();
		//var_dump($results);
		foreach ($results as $key => $option) { // $option is an object : myColumn->value
			list($id, $categorie, $label) = explode('|', $option->myColumn, 3);
			$categorie = html_entity_decode($categorie, ENT_QUOTES, 'UTF-8');
			if (!isset($groups[$categorie])) {
					$groups[$categorie] = array();
			}
			$groups[$categorie][$label] = JHTML::_('select.option', $id, $label, 'value', 'text', false);
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
	public function getOptions($categorie = "")
	{
		// Initialize variables.
		$options = array();

		$db     = JFactory::getDbo();
		$query  = $db->getQuery(true);

		//$query->select('id AS value, label AS text'); // => important !! AS value pour l'id et AS text pour le texte de l'option
		$query->select('CONCAT_WS("|",id,categorie,label) as myColumn');
		$query->from('#__adh_origines');
		$query->where('published = 1'); //->where('categorie = "'.$categorie.'"');
		$query->order('label');

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