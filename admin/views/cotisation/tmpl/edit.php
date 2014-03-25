<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.tooltip');

// Import library dependencies
JLoader::register('ADHFunctions', JPATH_COMPONENT_ADMINISTRATOR . '/helpers/functions.php');
JLoader::register('ADHControls', JPATH_COMPONENT_ADMINISTRATOR . '/helpers/controls.php');
JLoader::register('ADHdb', JPATH_COMPONENT_ADMINISTRATOR . '/helpers/db.php');

$params = JComponentHelper::getParams('com_adh');
?>

<form action="<?php echo JRoute::_('index.php?option=com_adh&layout=edit&id='.(int) $this->item->id); ?>"
      method="post" name="adminForm" id="adherent-form">
	<div class='width-60 fltlft'>
		<fieldset class="adminform">
			<legend><?php echo JText::_( 'COM_ADH_COTISATION_DETAILS' ); ?> <small>(<?php echo $this->item->id; ?>)</small></legend>
			<div class="tr">
				<div class="th"><?php echo $this->form->getField("adherent_id")->label; ?></div>
				<div class="td"><?php echo $this->form->getField("adherent_id")->input; ?></div>
				<script type="text/javascript">
					<?php	// si on clique sur "Ajouter une cotisation" à partir de la fiche de l'adhérent, on veut que celui-ci soit automagiquement
							// pré-sélectionné dans le select ?>
					window.addEvent('domready', function() {
						adherent_id = <?php echo JRequest::getVar('adherent_id', 0, 'get', 'int'); ?>;
						if (adherent_id != 0) { document.getElementById('jform_adherent_id').value = adherent_id; }
					});
				</script>
			</div>
			<div class="tr">
				<div class="tth"><?php echo $this->form->getField("tarif_id")->label; ?></div>
				<div class="ttd"><?php echo $this->form->getField("tarif_id")->input; ?></div>
				<div class="tth"><?php echo $this->form->getField("payee")->label; ?></div>
				<div class="ttd"><?php echo $this->form->getField("payee")->input; ?></div>
			</div>
			<div class='tr'>
				<div class="tth"><?php echo $this->form->getField("montant")->label; ?></div>
				<div class="ttd"><?php echo $this->form->getField("montant")->input; ?><span><?php echo $params->get('symbol'); ?></span></div>
				<div class="tth"><?php echo $this->form->getField("mode_paiement")->label; ?></div>
				<div class="ttd"><?php echo $this->form->getField("mode_paiement")->input; ?></div>
			</div>
			<div class="tr">
				<div class="tth"><?php echo $this->form->getField("date_debut_cotiz")->label; ?></div>
				<div class="ttd"><?php echo $this->form->getField("date_debut_cotiz")->input; ?></div>
				<div class="tth"><?php echo $this->form->getField("date_fin_cotiz")->label; ?></div>
				<div class="ttd"><?php echo $this->form->getField("date_fin_cotiz")->input; ?></div>
			</div>
			<div class="tr">
				<div class="tth"><?php echo $this->form->getField("commentaire")->label; ?></div>
				<div class="td"><?php echo html_entity_decode($this->form->getField("commentaire")->input); ?></div>
			</div>
		</fieldset>
	</div>
	<div class='width-40 fltrt'>
		<?php echo JHtml::_('sliders.start', 'content-sliders-'.$this->item->id, array('useCookie'=>1)); ?>
			<?php echo JHtml::_('sliders.panel', JText::_('COM_ADH_FIELDSET_PUBLISHING'), 'meta-options'); ?>
				<fieldset class="panelform">
					<ul class="adminformlist">
						<li><?php echo $this->form->getLabel('creation_date'); ?>
						<?php echo $this->form->getInput('creation_date'); ?></li>
						
						<li><?php echo $this->form->getLabel('published'); ?>
						<?php echo $this->form->getInput('published'); ?></li>
						
						<li><?php echo $this->form->getLabel('modified_by'); ?>
						<?php echo $this->form->getInput('modified_by'); ?></li>
						
						<li><?php echo $this->form->getLabel('modification_date'); ?>
						<?php echo $this->form->getInput('modification_date'); ?></li>
					</ul>
				</fieldset>
		<?php echo JHtml::_('sliders.end'); ?>

	</div>
	<div>
		<input type="hidden" name="task" value="cotisation.edit" />
                <?php JFactory::getApplication()->setUserState('com_adh.edit.cotisation.id', (int) $this->item->id); ?>
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>

<!--<pre>
    <?php //echo var_dump($this); ?>
</pre>-->