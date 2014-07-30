<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
 
// Import library dependencies
JLoader::register('ADHFunctions', JPATH_COMPONENT_ADMINISTRATOR . '/helpers/functions.php');

// load tooltip behavior
JHtml::_('behavior.tooltip');
?>

    <?php
    
    if (isset($_GET['id'])) {
    }
?>

<form action="<?php echo JRoute::_('index.php?option=com_adh&view=importV2Adherents'); ?>" method="post" name="adminForm" id="adminForm">
	<fieldset id="filter-bar">
		<div class="filter-search fltlft">
			<label class="filter-search-lbl" for="filter_search"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>
			<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('COM_ADH_FILTER_SEARCH_DESC'); ?>" />

			<button type="submit" class="btn"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
			<button type="button" onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
			<input type="checkbox" id="cb_notimported_search" name="notimported_search" onclick="this.form.submit();" <?php echo $this->escape($this->state->get('notimported.search')); ?> /> <label for="cb_notimported_search"><?php echo JText::_('COM_ADH_SHOW_NOT_IMPORTED'); ?></label>
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

<?php if (JDEBUG): ?>
<pre>POST<br /><?php var_dump($_POST); ?></pre>
<pre>GET<br /><?php var_dump($_GET); ?></pre>
<pre>REQUEST<br /><?php var_dump($_REQUEST); ?></pre>
<pre>notimported_search<br /><?php echo JRequest::getVar("notimported_search", null, 'default', 'string'); ?></pre>
<pre>THIS<br /><?php var_dump($this); ?></pre>
<?php endif; ?>
