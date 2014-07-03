<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
 
// load tooltip behavior
JHtml::_('behavior.tooltip');

// Import library dependencies
JLoader::register('ADHControls', JPATH_COMPONENT_ADMINISTRATOR . '/helpers/controls.php');
?>
<form action="<?php echo JRoute::_('index.php?option=com_adh&view=extractAddresses'); ?>" method="post" name="adminForm" id="adminForm">
	<fieldset id="filter-bar">
		<div class="year-select fltlft">
			<label class="year-search-lbl" for="year_search"><?php echo JText::_('COM_ADH_FILTER_YEAR_LABEL'); ?>&nbsp;</label>
			<!--<input type="hidden" name="year_search" id="year_search" value="<?php echo $this->escape($this->state->get('year.search')); ?>" title="<?php echo JText::_('COM_ADH_FILTER_YEAR_DESC'); ?>" />-->
			<?php echo ADHcontrols::selectYearsFromTable("#__adh_cotisations", "date_debut_cotiz", "DESC", $this->state->get('year.search'), "year_search"); ?>
		</div>
		<div class="with_email-checkbox fltlft">
			<input type="checkbox" name="with_email_search" id="with_email_search" onchange='this.form.submit();' <?php echo (($this->state->get('with_email.search') == 'on') ? "checked" : ""); ?> />
			<label class="with_email-search-lbl" for="with_email_search"><?php echo JText::_('COM_ADH_HIDE_ADH_WITH_EMAILS_LABEL'); ?>&nbsp;</label>
			<pre><?php //var_dump($this->state); ?></pre>
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
	</fieldset>
	<div class="list_columns">
		<?php foreach($this->items as $i => $item): ?>
			<p> <?php echo ($item->personne_morale != "" ? $item->personne_morale : $item->nom." ".$item->prenom); ?><br />
				<?php echo $item->adresse; ?><br />
				<?php echo ($item->adresse2 != "" ? $item->adresse2."<br />" : ""); ?>
				<?php echo $item->cp; ?> <?php echo $item->ville; ?> <?php echo $item->pays; ?><br />
			</p>
		<?php endforeach; ?>
	</div>
	<table class="adminlist">
		<tr><td colspan="10"><?php echo $this->pagination->getListFooter(); ?></td></tr>
	</table>
	<div>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>