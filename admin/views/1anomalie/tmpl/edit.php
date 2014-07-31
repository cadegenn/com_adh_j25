<?php
/**
 * @package		
 * @subpackage	
 * @brief
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

// No direct access
defined('_JEXEC') or die('Restricted access');

/* form for the toolbar */
?>
<form action="<?php echo JRoute::_('index.php?option=com_adh&layout=edit'); ?>"
      method="post" name="adminForm" id="adminForm">
	<div>
		<input type="hidden" name="task" value="1anomalie.edit" />
        <?php //JFactory::getApplication()->setUserState('com_adh.edit.1anomalie.id.user1', (int) $this->form1->getField('id')->value); ?>
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
	
<?php		
echo $this->loadTemplate('user1');
echo $this->loadTemplate('user2');

?>

<?php if (JDEBUG): ?>
	<!--<pre class="clrlft"><?php //echo var_dump($this); ?></pre>
	<?php //$session = JFactory::getSession();
	//$registry = $session->get('registry');?>
	<pre><?php //var_dump($registry->get('com_adh.edit.1anomalie')); ?></pre>-->
<?php endif; ?>
