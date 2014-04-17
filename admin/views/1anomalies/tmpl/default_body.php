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
	//$canCreate      = $user->authorise('core.create',		'com_adh.category.'.$item->catid);
	$canEdit        = $user->authorise('core.edit',			'com_adh.adherent.'.$item->id);
	$canCheckin     = $user->authorise('core.manage',		'com_checkin') || $item->checked_out == $userId || $item->checked_out == 0;
	//$canEditOwn     = $user->authorise('core.edit.own',		'com_adh.adherent.'.$item->id) && $item->created_by == $userId;
	$canChange      = $user->authorise('core.edit.state',	'com_adh.adherent.'.$item->id) && $canCheckin;
	?>
	<tr class="row<?php echo $i % 2; ?>">
        <td>
			<?php echo JHtml::_('grid.id', $i, $item->id); ?>
		</td>
		<td>
			<?php 
			$class = "";
			if ($item->personne_morale != "") {
				$class = "structure";
				$label = $item->personne_morale;
			} else {
				switch (strtolower($item->titre)) {
					case "m." :
					case "mr" : $class = "user-male";
								break;
					case "mme" :
					case "mlle":$class = "user-female";
								break;
					default :	$class = "user-male";
								break;
				}
				$label = $item->nom." ".$item->prenom;
			}?>
			<a class="icon-<?php echo $class; ?>" href="<?php echo JRoute::_('index.php?option=com_adh&view=adherent&layout=edit&id=' . $item->id); ?>"><?php echo($label); ?></a>
		</td>
		<td>
			<a href='mailto:<?php echo $item->email; ?>'><?php echo $item->email; ?></a>
		</td>
		<td>
			<?php echo $item->ville; ?>
		</td>
		<td>
			<?php echo $item->pays; ?>
		</td>
		<td class="center">
			<?php //echo $item->visible; ?>
			<?php echo JHtml::_('jgrid.published', $item->published, $i, 'adherents.', $canChange, 'cb', '', ''); ?>
			<?php //		+-> /libraries/joomla/html/html/jgrid.php : published() ?>
			<?php //		+-> controllers/adherents.php : published() ?>
		</td>
		<td class="right">
			<?php echo $item->id; ?>
		</td>
	</tr>
<?php endforeach; ?>
