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

// Import library dependencies
JLoader::register('ADHControls', JPATH_COMPONENT_ADMINISTRATOR . '/helpers/controls.php');

$user           = JFactory::getUser();
$userId         = $user->get('id');
$listOrder      = $this->escape($this->state->get('list.ordering'));
$listDirn       = $this->escape($this->state->get('list.direction'));
//$saveOrder      = $listOrder == 'a.ordering';
?>

<form action="<?php echo JRoute::_('index.php?option=com_adh&view=2anomalies'); ?>" method="post" name="adminForm" id="adminForm">
	<fieldset id="filter-bar">
		<div class="year-select fltlft">
			<label class="year-search-lbl" for="year_search"><?php echo JText::_('COM_ADH_FILTER_YEAR_LABEL'); ?></label>
			<!--<input type="hidden" name="year_search" id="year_search" value="<?php echo $this->escape($this->state->get('year.search')); ?>" title="<?php echo JText::_('COM_ADH_FILTER_YEAR_DESC'); ?>" />-->
			<?php echo ADHcontrols::selectYearsFromTable("#__adh_cotisations", "date_debut_cotiz", "DESC", $this->state->get('year.search'), "year_search"); ?>
		</div>
	</fieldset>
	<fieldset id="filter-bar">
		<div class="anomalie-type-select fltlft">
			<label class="pays-search-lbl" for="anomalie_type"><?php echo JText::_('COM_ADH_ANOMALIE_TYPE_LABEL'); ?>&nbsp;</label>
			<?php echo ADHcontrols::buildSelectCotizTypesAnomalies($this->state->get('anomalies.search')); ?>
		</div>
		<div class="fltlft">
			<label><span class="bold"><?php echo(number_format($this->total, 0, ".", " ")); ?></span> <?php echo JText::_('COM_ADH_ANOMALIES_FOUND_TXT'); ?> </label>
		</div>
	</fieldset>
	<table class="adminlist">
		<thead><tr>
			<th width="20">
				<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->items); ?>);" />
			</th>			
			<th>
				<?php //echo JText::_('COM_ADH_NOM_LABEL'); ?> <?php //echo JText::_('COM_ADH_PRENOM_LABEL'); ?>
				<?php echo JHtml::_('grid.sort', 'COM_ADH_NOM_LABEL', 'a.nom', $listDirn, $listOrder); ?>
			</th>
			<th>
				<?php //echo JText::_('COM_ADH_DATE_LABEL'); ?>
				<?php echo JHtml::_('grid.sort', 'COM_ADH_DATE_LABEL', 'c.date_debut_cotiz', $listDirn, $listOrder); ?>
			</th>
			<th>
				<?php //echo JText::_('COM_ADH_MONTANT_LABEL'); ?>
				<?php echo JHtml::_('grid.sort', 'COM_ADH_MONTANT_LABEL', 'c.montant', $listDirn, $listOrder); ?>
			</th>
			<th>
				<?php //echo JText::_('COM_ADH_MODEPAIEMENT_LABEL'); ?>
				<?php echo JHtml::_('grid.sort', 'COM_ADH_MODEPAIEMENT_LABEL', 'c.mode_paiement', $listDirn, $listOrder); ?>
			</th>
			<th>
				<?php //echo JText::_('COM_ADH_PAYEE_LABEL'); ?>
				<?php echo JHtml::_('grid.sort', 'COM_ADH_PAYEE_LABEL', 'c.payee', $listDirn, $listOrder); ?>
			</th>
			<th>
				<?php //echo JText::_('COM_ADH_HEADING_ID'); ?>
				<?php echo JHtml::_('grid.sort', 'COM_ADH_HEADING_ID', 'c.id', $listDirn, $listOrder); ?>
			</th>
		</tr></thead>
		<tfoot><?php echo $this->loadTemplate('foot');?></tfoot>
		<tbody><?php echo $this->loadTemplate('body');?></tbody>
	</table>
	<div>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="return" value="<?php echo base64_encode(JFactory::getURI()); ?>" />
		<input type="hidden" name="boxchecked" value="0" />
        <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
        <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>

<pre>
    <?php //echo var_dump($_POST); ?>
    <?php //echo var_dump($_GET); ?>
    <?php //echo var_dump($this); ?>
</pre>