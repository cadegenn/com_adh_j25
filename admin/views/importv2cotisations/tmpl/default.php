<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
 
// load tooltip behavior
JHtml::_('behavior.tooltip');

// Import library dependencies
JLoader::register('ADHdb', JPATH_COMPONENT_ADMINISTRATOR . '/helpers/db.php');

?>

    <?php
    
    if (isset($_GET['id'])) {
    }
?>

<form action="<?php echo JRoute::_('index.php?option=com_adh&view=importV2Cotisations'); ?>" method="post" name="adminForm" id="adminForm">
	<fieldset id="filter-bar">
		<div class="year-search fltlft">
			<label class="year-search-lbl" for="year_search"><?php echo JText::_('JSEARCH_YEAR_LABEL'); ?></label>
			<select class="inputbox" name="year_search" id="year_search" onchange="this.form.submit();">
				<?php
					$old_db = ADHdb::getOldDbo();
					$old_db->setQuery("SELECT DISTINCT YEAR(date) AS value, YEAR(date) AS text FROM cotisations GROUP BY value ORDER BY value DESC");
					$old_db->execute();
					$rows = $old_db->loadObjectList();
					echo JHtml::_('select.options', $rows, 'value', 'text', $this->state->get('year.search'));
				?>
			</select>
			<button type="button" onclick="document.id('year_search').value='';"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
		</div>
	</fieldset>
	<table class="adminlist">
		<thead><?php echo $this->loadTemplate('head');?></thead>
		<tfoot><?php echo $this->loadTemplate('foot');?></tfoot>
		<tbody><?php echo $this->loadTemplate('body');?></tbody>
	</table>
	<div>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>

<pre>
    <?php //echo var_dump($_POST); ?>
    <?php //echo var_dump($_GET); ?>
    <?php //echo var_dump($this); ?>
</pre>