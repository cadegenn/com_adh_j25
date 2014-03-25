<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>
<tr>
	<th width="20">
		<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->items); ?>);" />
	</th>			
	<th>
		<?php echo JText::_('COM_ADH_LABEL_LABEL'); ?>
	</th>
	<th>
		<?php echo JText::_('COM_ADH_TARIF_LABEL'); ?>
	</th>
	<th>
		<?php echo JText::_('COM_ADH_STATUS_LABEL'); ?>
	</th>
	<th>
		<?php echo JText::_('COM_ADH_HEADING_ID'); ?>
	</th>
</tr>
