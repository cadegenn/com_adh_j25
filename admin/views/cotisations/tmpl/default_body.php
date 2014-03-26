<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

$params = JComponentHelper::getParams('com_adh');

$user           = JFactory::getUser();
$userId         = $user->get('id');
?>

<?php foreach($this->items as $i => $item):
	$item->max_ordering = 0; //??
	//$ordering       = ($listOrder == 'a.ordering');
	//$canCreate      = $user->authorise('core.create',		'com_adh.category.'.$item->catid);
	$canEdit        = $user->authorise('core.edit',			'com_adh.cotisation.'.$item->id);
	$canCheckin     = $user->authorise('core.manage',		'com_checkin') || $item->checked_out == $userId || $item->checked_out == 0;
	//$canEditOwn     = $user->authorise('core.edit.own',		'com_adh.cotisation.'.$item->id) && $item->created_by == $userId;
	$canChange      = $user->authorise('core.edit.state',	'com_adh.cotisation.'.$item->id) && $canCheckin;
	?>
	<tr class="row<?php echo $i % 2; ?>">
        <td>
			<?php echo JHtml::_('grid.id', $i, $item->id); ?>
		</td>
		<td>
			<a href="<?php echo JRoute::_('index.php?option=com_adh&view=cotisation&layout=edit&id=' . $item->id); ?>"><?php echo ($item->personne_morale != "" ? $item->personne_morale : $item->nom." ".$item->prenom); ?></a>
		</td>
		<td>
			<?php echo $item->date_debut_cotiz; ?>
		</td>
		<td class="right">
			<?php echo $item->montant.' '.$params->get('symbol'); ?>
		</td>
		<td>
			<?php echo $item->mode_paiement; ?>
		</td>
		<td class="center">
			<?php echo JHtml::_('jgrid.published', $item->payee, $i, 'cotisations.', $canChange, 'cb', $item->date_debut_cotiz, $item->date_fin_cotiz); ?>
			<?php //		+-> /libraries/joomla/html/html/jgrid.php : published() ?>
			<?php //		+-> controllers/cotisations.php : published() ?>
			<?php //		+-> /libraries/joomla/application/component/controlleradmin.php : publish() ?>
			<?php //		+-> models/cotisation.php : publish() ?>
			<?php //echo JHtml::_('cotisations.paid', $item->payee, $i, 'cotisations.'); ?>
		</td>
		<td class="right">
			<?php echo $item->id; ?>
		</td>
	</tr>
<?php endforeach; ?>
