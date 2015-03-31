<?php
/**
 * @package		com_adh
 * @subpackage	
 * @brief		com_adh helps you manage the people within an association
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

// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

$params = JComponentHelper::getParams('com_adh');
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
			<a href="<?php echo JRoute::_('index.php?option=com_adh&view=adherent&layout=edit&id=' . $item->adherent_id); ?>">
				<?php echo ($item->nom.' '); ?>
				<?php echo ($item->prenom.' '); ?>
			</a>
			<?php if ($item->personne_morale <> '') { echo ('('.$item->personne_morale.') '); } ?>
			<small><a href="<?php echo JRoute::_('index.php?option=com_adh&view=adherent&layout=edit&id=' . $item->adherent_id); ?>">
					(<?php echo $item->adherent_id; ?>)
			</a></small>
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
			<a href="<?php echo JRoute::_('index.php?option=com_adh&view=cotisation&layout=edit&id=' . $item->id); ?>"><?php echo $item->id; ?></a>
		</td>
	</tr>
<?php endforeach; ?>
