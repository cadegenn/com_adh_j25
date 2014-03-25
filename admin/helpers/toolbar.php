<?php
/**
 * @copyright	Copyright (C) 2005 - 2013 charly All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.html.toolbar');

/**
 * Utility class for the button bar.
 *
 * @package		Joomla.Administrator
 * @subpackage	Application
 */
class myJToolBarHelper extends JToolBarHelper {
	/**
	 * Writes a cancel button that will go back to the previous page without doing
	 * any other operation.
	 *
	 * @param	string	$alt	Alternative text.
	 * @param	string	$href	URL of the href attribute.
	 * @since	1.0
	 */
	public static function link($id, $alt, $href)
	{
		$bar = JToolBar::getInstance('toolbar');
		// Add a back button.
		$bar->appendButton('Link', $id, $alt, $href);
	}

}

?>
