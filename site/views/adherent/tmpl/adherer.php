<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.tooltip');
 
// Import library dependencies
JLoader::register('ADHFunctions', JPATH_COMPONENT_ADMINISTRATOR . '/helpers/functions.php');
JLoader::register('ADHControls', JPATH_COMPONENT_ADMINISTRATOR . '/helpers/controls.php');
JLoader::register('ADHdb', JPATH_COMPONENT_ADMINISTRATOR . '/helpers/db.php');

$params = JComponentHelper::getParams('com_adh');

switch ($params->get("map_provider")) {
	case 'googlemap-v3'	:	require(JPATH_COMPONENT_ADMINISTRATOR . '/js/google-places.php');
							break;
}
?>
<h1><?php echo $this->document->title; ?></h1>

<form class="form-validate" action="<?php echo JRoute::_('index.php'); ?>" method="post" id="adherer" name="adherer">
	<fieldset class="adminform">
		<?php echo $this->form->getField("id")->input; ?>
		<?php echo $this->loadTemplate('adh');?>
		<?php echo $this->loadTemplate('tarifs');?>
		<?php echo $this->loadTemplate('options');?>
		<div class="tr">
			<?php echo $this->form->getField("accept")->input; ?> <?php echo $this->form->getField("accept")->label; ?>
		</div>
		<div class="tr">
			<div class="td" style="margin-top: 20px;"><small><span class="star left">*</span> : <?php echo JText::_('COM_ADH_STAR_MANDATORY_FIELD'); ?></small></div>
			<button type="submit" class="button right"><?php echo JText::_('COM_ADH_ADHERER_LABEL'); ?></button>
		</div>
	</fieldset>
	<?php echo JHtml::_('form.token'); ?>
	<input type="hidden" name="task" value="adherent.adherer" /> <?php // -> controllers/adherent.php : adherer() ?>
</form>
<div class="clr"></div>
<!--<pre><?php //var_dump($params); ?></pre>-->