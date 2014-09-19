<?php

/* 
 *  Copyright (C) 2012-2014 charly
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

?>

<table class="dashboard"">
	<thead>	<th style="width: 50%;"><?php echo JText::_('COM_ADH_LAST_REGISTERED_USERS'); ?></th>
			<th><?php echo JText::_('COM_ADH_PENDING_PAYMENTS'); ?></th></thead>
	<tr><td><ul>
		<?php foreach ($this->online_registrations as $i => $adherent) : ?>
				<li><a href='index.php?option=<?php echo JRequest::getVar('option', '0', 'get', 'string'); ?>&view=adherent&layout=edit&id=<?php echo $adherent->id; ?>' title="id: <?php echo $adherent->id; ?>"><?php echo($adherent->nom." ".$adherent->prenom);?></a> <?php echo(JText::_('COM_ADH_REGISTERED_DATE_LABEL')." ".$adherent->creation_date); ?></li>
		<?php endforeach; ?>
		</ul>
	</td><td><ul>
		<?php foreach ($this->pending_payments as $i => $payment) : ?>
		<li><a href='index.php?option=<?php echo JRequest::getVar('option', '0', 'get', 'string'); ?>&view=cotisation&layout=edit&id=<?php echo $payment->id; ?>'><?php echo($payment->nom." ".$payment->prenom);?></a> <?php echo(JText::_('COM_ADH_REGISTERED_DATE_LABEL')." ".$payment->date_debut_cotiz); ?></li>
		<?php endforeach; ?>
		</ul>
	</td></tr>
</table>

