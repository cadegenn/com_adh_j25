<?php
defined('_JEXEC') or die;
/**
 * @see http://joomla-answers.blogspot.fr/2012/06/joomla-25-extend-jgridpublished-column.html
 */
/*abstract class JHtmlCotisations {
	static function paid($value = 0, $i) {
		$states = array(
			0=> array('disabled.png','cotisations.paid','',	  'COM_ADH_CLICK_TO_DECLARE_PAID'),
			1=> array('tick.png',    'cotisations.unpaid', '','COM_ADH_CLICK_TO_DECLARE_UNPAID'), );
		$state   = JArrayHelper::getValue($states, (int) $value, $states[1]);
		$html    = JHtml::_('image', 'admin/'.$state[0], JText::_($state[2]), NULL, true);
		$html    = '<a href="#" onclick="return listItemTask(\'cb'.$i.'\',\''.$state[1].'\')" title="'.JText::_($state[3]).'">'. $html.'</a>';

		return $html;
	}
}*/
?>