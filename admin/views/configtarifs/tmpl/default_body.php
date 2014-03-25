<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

$user           = JFactory::getUser();
$userId         = $user->get('id');
//$listOrder      = $this->escape($this->published->get('list.ordering'));
//$listDirn       = $this->escape($this->published->get('list.direction'));
//$saveOrder      = $listOrder == 'a.ordering';
?>

<?php foreach($this->items as $i => $item):
	$item->max_ordering = 0; //??
	//$ordering       = ($listOrder == 'a.ordering');
	$canCreate      = $user->authorise('core.create',		'com_adh.category.'.$item->id);
	$canEdit        = $user->authorise('core.edit',			'com_adh.configTarif.'.$item->id);
	$canCheckin     = $user->authorise('core.manage',		'com_checkin') || $item->checked_out == $userId || $item->checked_out == 0;
	//$canEditOwn     = $user->authorise('core.edit.own',		'com_adh.chantier.'.$item->id) && $item->created_by == $userId;
	$canChange      = $user->authorise('core.edit.state',	'com_adh.configTarif.'.$item->id) && $canCheckin;
	?>
	<tr class="row<?php echo $i % 2; ?>">
        <td>
			<?php echo JHtml::_('grid.id', $i, $item->id); ?>
		</td>
		<td>
			<a href="<?php echo JRoute::_('index.php?option=com_adh&view=configTarif&layout=edit&id=' . $item->id); ?>"><?php echo $item->label; ?></a>
		</td>
		<td class="right">
			<?php echo stripslashes($item->tarif); ?> <?php echo stripslashes($item->symbol); ?>
		</td>
		<td class="center">
			<?php echo JHtml::_('jgrid.published', $item->published, $i, 'configTarifs.', $canChange, 'cb', '', ''); ?>
		</td>
		<td class="right">
			<?php echo $item->id; ?>
		</td>
	</tr>
<?php endforeach; ?>
