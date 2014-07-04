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

<form action="<?php echo JRoute::_('index.php?option=com_adh&view=1anomalies'); ?>" method="post" name="adminForm" id="adminForm">
	<fieldset id="filter-bar">
		<div class="filter-search fltlft">
			<label class="filter-search-lbl" for="filter_search"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?>&nbsp;</label>
			<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('COM_ADH_FILTER_SEARCH_DESC'); ?>" />

			<button type="submit" class="btn"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
			<button type="button" onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
		</div>
		<div class="filter-select fltrt">
			<input type ="hidden" name="letter_search" id="letter_search" value="<?php echo $this->escape($this->state->get('letter.search')); ?>" />
			<ul><?php
			$alphabet = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
			foreach ($alphabet as $letter) : ?>
				<li><a <?php echo(($this->escape($this->state->get('letter.search')) == $letter ? "class='selected'" : ""));?> href="javascript:document.id('letter_search').value='<?php echo $letter; ?>';document.id('adminForm').submit();"><?php echo $letter; ?></a></li>
			<?php endforeach; ?>
			</ul>
			<button style="float: right;" type="button" onclick="document.id('letter_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
		</div><br />
	</fieldset>
	<fieldset id="filter-bar">
		<div class="anomalie-type-select fltlft">
			<label class="pays-search-lbl" for="anomalie_type"><?php echo JText::_('COM_ADH_ANOMALIE_TYPE_LABEL'); ?>&nbsp;</label>
			<?php echo ADHcontrols::buildSelectAdhTypesAnomalies($this->state->get('anomalies.search')); ?>
		</div>
		<div class="pays-select fltrt">
			<label class="pays-search-lbl" for="pays_search"><?php echo JText::_('COM_ADH_PAYS_LABEL'); ?>&nbsp;</label>
			<?php echo ADHcontrols::selectColumnFromTable("#__adh_adherents", "pays", "ASC", $this->state->get('pays.search')); ?>
			<!--<button type="button" onclick="document.id('pays_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>-->
		</div>
		<div class="ville-select fltrt">
			<label class="ville-search-lbl" for="ville_search"><?php echo JText::_('COM_ADH_VILLE_LABEL'); ?>&nbsp;</label>
			<?php echo ADHcontrols::selectColumnFromTable("#__adh_adherents", "ville", "ASC", $this->state->get('ville.search')); ?>
			<!--<button type="button" onclick="document.id('ville_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>-->
		</div>
		<div class="cp-select fltrt">
			<label class="cp-search-lbl" for="cp_search"><?php echo JText::_('COM_ADH_DEPARTEMENT_LABEL'); ?>&nbsp;</label>
			<select name='cp_search' id='cp_search' onchange='this.form.submit();'>
				<option value=""></option>
			<?php for ($i = 01; $i < 100; $i++) : ?>
				<?php if (strlen((string)$i) < 2) { $dep = '0'.(string)$i; } else { $dep = (string)$i; } ?>
				<option value="<?php echo $dep; ?>" <?php echo ($dep == $this->state->get('cp.search') ? "selected" : ""); ?>><?php echo $dep; ?></option>
			<?php endfor; ?>
			</select>
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
				<?php echo JHtml::_('grid.sort', 'COM_ADH_NOMPRENOM_LABEL', 'LOWER(a.nom)', $listDirn, $listOrder); ?>
			</th>
			<th>
				<?php //echo JText::_('COM_ADH_EMAIL_LABEL'); ?>
				<?php echo JHtml::_('grid.sort', 'COM_ADH_EMAIL_LABEL', 'a.email', $listDirn, $listOrder); ?>
			</th>
			<th>
				<?php //echo JText::_('COM_ADH_VILLE_LABEL'); ?>
				<?php echo JHtml::_('grid.sort', 'COM_ADH_VILLE_LABEL', 'LOWER(a.ville)', $listDirn, $listOrder); ?>
			</th>
			<th>
				<?php //echo JText::_('COM_ADH_PAYS_LABEL'); ?>
				<?php echo JHtml::_('grid.sort', 'COM_ADH_PAYS_LABEL', 'LOWER(a.pays)', $listDirn, $listOrder); ?>
			</th>
			<th>
				<?php //echo JText::_('COM_ADH_STATUS_LABEL'); ?>
				<?php echo JHtml::_('grid.sort', 'COM_ADH_STATUS_LABEL', 'a.published', $listDirn, $listOrder); ?>
			</th>
			<th>
				<?php //echo JText::_('COM_ADH_HEADING_ID'); ?>
				<?php echo JHtml::_('grid.sort', 'COM_ADH_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
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