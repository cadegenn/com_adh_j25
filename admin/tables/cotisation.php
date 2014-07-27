<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
 
// import Joomla table library
jimport('joomla.database.table');
 
/**
 * Hello Table class
 */
class adhTableCotisation extends JTable
{
	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function __construct(&$db) 
	{
		parent::__construct('#__adh_cotisations', 'id', $db);
	}

	/**
	 * Stores a cotisation
	 *
	 * @param       boolean True to update fields even if they are null.
	 * @return      boolean True on success, false on failure.
	 * @since       1.6
	 */
	public function store($updateNulls = false) {
		$date   = JFactory::getDate();
		$user   = JFactory::getUser();
		if ($this->id) {
			// Existing item
			$this->modification_date= $date->toSql();
			$this->modified_by      = $user->get('id');
		} else {
			// New newsfeed. A feed created and created_by field can be set by the user,
			// so we don't touch either of these if they are set.
			if (!intval($this->creation_date)) {
				$this->creation_date = $date->toSql();
			}
			if (empty($this->created_by)) {
				$this->created_by = $user->get('id');
			}
		}
		
		// Attempt to store the data.
		return parent::store($updateNulls);
	}

	
}

?>
