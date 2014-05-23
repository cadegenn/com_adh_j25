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
 
// load tooltip behavior
JHtml::_('behavior.tooltip');
?>

<script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Year', 'nb cotisations', 'primo-adhérents'],
		  <?php foreach ($this->stats_cotiz_by_year as $i => $stat) : ?>
			['<?php echo $stat->year; ?>', <?php echo $stat->nb; ?>, <?php echo $stat->primo; ?>],
		  <?php endforeach; ?>
        ]);

        var options = {
          title: 'nb adhésions par année'
        };

        var chart = new google.visualization.LineChart(document.getElementById('chart_cotiz_div'));
        chart.draw(data, options);
      }
</script>

<table class="dashboard"">
	<thead>	<th style="width: 50%;"><?php echo JText::_('COM_ADH_LAST_REGISTERED_USERS'); ?></th>
			<th><?php echo JText::_('COM_ADH_PENDING_PAYMENTS'); ?></th></thead>
	<tr><td><ul>
		<?php foreach ($this->online_registrations as $i => $adherent) : ?>
		<li><a href='index.php?option=<?php echo JRequest::getVar('option', '0', 'get', 'string'); ?>&view=adherent&layout=edit&id=<?php echo $adherent->id; ?>'><?php echo($adherent->nom." ".$adherent->prenom);?></a> <?php echo(JText::_('COM_ADH_REGISTERED_DATE_LABEL')." ".$adherent->creation_date); ?></li>
		<?php endforeach; ?>
		</ul>
	</td><td><ul>
		<?php foreach ($this->pending_payments as $i => $payment) : ?>
		<li><a href='index.php?option=<?php echo JRequest::getVar('option', '0', 'get', 'string'); ?>&view=cotisation&layout=edit&id=<?php echo $payment->id; ?>'><?php echo($payment->nom." ".$payment->prenom);?></a> <?php echo(JText::_('COM_ADH_REGISTERED_DATE_LABEL')." ".$payment->date_debut_cotiz); ?></li>
		<?php endforeach; ?>
		</ul>
	</td></tr>
</table>

<div id="chart_cotiz_div" style="width: 100%; height: 500px;"></div>

<!--<form action="<?php echo JRoute::_('index.php?option=com_adh'); ?>" method="post" name="adminForm" id="adminForm">
	<table class="adminlist">
		<thead><?php //echo $this->loadTemplate('head');?></thead>
		<tfoot><?php //echo $this->loadTemplate('foot');?></tfoot>
		<tbody><?php //echo $this->loadTemplate('body');?></tbody>
	</table>
	<div>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>-->
<p align="center"><?php echo $this->component->name; ?> - <?php echo $this->manifest->version; ?></p>
<pre><?php //var_dump($this); ?></pre>