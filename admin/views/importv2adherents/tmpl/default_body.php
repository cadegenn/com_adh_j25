<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

/*$user           = JFactory::getUser();
$userId         = $user->get('id');
//$listOrder      = $this->escape($this->published->get('list.ordering'));
//$listDirn       = $this->escape($this->published->get('list.direction'));
//$saveOrder      = $listOrder == 'a.ordering';
 * 
 */
?>

<?php foreach($this->items as $i => $item):
	$found = false;
	//echo ("<pre>"); var_dump($this->imported); echo ("</pre>");
	foreach ($this->imported as $j => $imported) {
		if ($item->id == $imported->id) { $found = true; break; }
	}
	$item->max_ordering = 0; //??
	/*//$ordering       = ($listOrder == 'a.ordering');
	$canCreate      = $user->authorise('core.create',		'com_adh.category.'.$item->categorie);
	$canEdit        = $user->authorise('core.edit',			'com_adh.adherent.'.$item->id);
	$canCheckin     = $user->authorise('core.manage',		'com_checkin') || $item->checked_out == $userId || $item->checked_out == 0;
	//$canEditOwn     = $user->authorise('core.edit.own',		'com_adh.adherent.'.$item->id) && $item->created_by == $userId;
	$canChange      = $user->authorise('core.edit.state',	'com_adh.adherent.'.$item->id) && $canCheckin;
	 * 
	 */
	?>
	<tr class="row<?php echo $i % 2; ?>">
        <td>
			<?php echo JHtml::_('grid.id', $i, $item->id); ?>
		</td>
		<td class="center">
			<?php echo JHtml::_('jgrid.published', $found, $i, 'chantiers.', 0, 'cb', '', ''); ?>
		</td>
		<td>
			<span class='blue'><?php echo stripslashes($item->nom." ".$item->prenom); ?></span>
		</td>
		<td>
			<?php echo $item->email; ?>
		</td>
		<td>
			<?php echo $item->ville; ?>
		</td>
		<td>
			<?php echo $item->pays; ?>
		</td>
		<td class="center">
			<?php //echo $item->visible; ?>
			<?php echo JHtml::_('jgrid.published', $item->enable, $i, 'adherents.', 0, 'cb', '', ''); ?>
		</td>
		<td class="right">
			<?php echo $item->id; ?>
		</td>
	</tr>
<?php endforeach; ?>
