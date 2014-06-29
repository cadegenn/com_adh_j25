<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>
<tr>
	<th width="20">
		<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->items); ?>);" />
	</th>			
	<th>
		<?php echo JText::_('COM_ADH_IMPORTED_LABEL'); ?>
	</th>
	<th>
		<?php echo JText::_('COM_ADH_NOM_LABEL'); ?> <?php echo JText::_('COM_ADH_PRENOM_LABEL'); ?>
	</th>
	<th>
		<?php echo JText::_('COM_ADH_EMAIL_LABEL'); ?>
	</th>
	<th>
		<?php echo JText::_('COM_ADH_VILLE_LABEL'); ?>
	</th>
	<th>
		<?php echo JText::_('COM_ADH_PAYS_LABEL'); ?>
	</th>
	<th>
		<?php echo JText::_('COM_ADH_STATUS_LABEL'); ?>
	</th>
	<th>
		<?php echo JText::_('COM_ADH_HEADING_ID'); ?>
	</th>
</tr>

