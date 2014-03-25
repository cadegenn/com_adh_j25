<?php
 
defined('JPATH_BASE') or die;
 
jimport('joomla.html.html');
jimport('joomla.form.formfield');
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');
 
/**
 * Custom Field class for the Joomla Framework.
 *
 * @package             Joomla.Administrator
 * @subpackage          com_adh
 * @since               0.0.11
 */
class JFormFieldAdherent extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var         string
	 * @since       0.0.11
	 */
	protected $type = 'adherent';

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
		$query->select('id as value, CONCAT_WS(" ", nom, prenom, ville) as text');
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