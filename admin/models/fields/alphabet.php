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
class JFormFieldAlphabet extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var         string
	 * @since       0.0.11
	 */
	protected $type = 'alphabet';

	/**
	 * Method to get the field options.
	 *
	 * @return      array   The field option objects.
	 * @since       1.6
	 */
	public function getOptions()
	{
		// Initialize variables.
		$options = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');

		// Check for a database error.
		if ($db->getErrorNum()) {
				JError::raiseWarning(500, $db->getErrorMsg());
		}

		return $options;
	}
}