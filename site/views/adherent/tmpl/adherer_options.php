<?php $params = JComponentHelper::getParams('com_adh'); ?>

<?php // Comment ai-je connu $monasso$ ? ?>
<div class="tr">
	<?php echo JText::sprintf('COM_ADH_ADHERER_ORIGINE_TXT', "<span class='apl'>".$params->get('nom_assoc')."</span>"); ?>
</div>
<div class="tr">
	<div class="adherer_options">
		<?php echo $this->form->getField("origine_id")->input; ?>
		<?php echo $this->form->getField("origine_text")->input; ?>
	</div>
</div>

<?php // Je choisis les options ?>
<div class="tr">
	<?php echo JText::_('COM_ADH_ADHERER_CHOOSEOPTIONS_TXT'); ?>
</div>
<div class="tr">
	<div class="adherer_options">
		<div class='left'><?php echo $this->form->getField("imposable")->input; ?></div>
		<div style='margin-left:24px;'><?php echo JText::_('COM_ADH_ADHERER_IMPOSABLE_LABEL'); ?><br />
								<small><?php echo JText::_('COM_ADH_ADHERER_IMPOSABLE_DESC'); ?></small>
		</div>
	</div>
</div>

<?php // Je choisis le mode paiement ?>
<?php if ($params->getValue('gratuit') != 1) : ?>
<div class="tr">
	<?php echo JText::_('COM_ADH_ADHERER_REGLEMENT_TXT'); ?> <span class="star">*</span>
</div>
<div class="tr">
	<div class="adherer_options">
		<?php if ($params->getValue('gratuit')) : ?>
			<input type="radio" class="required" name="jform[mode_paiement]" id="jform_mode_paiement0" value="<?php echo strtolower(JText::_('COM_ADH_SETTINGS_PAIEMENT_GRATUIT_LABEL')); ?>"> <label for="jform_mode_paiement0"><?php echo JText::_('COM_ADH_ADHERER_REGLEMENT_GRATUIT'); ?></label>
		<?php endif; ?>
		<?php if ($params->getValue('espece')) : ?>
			<input type="radio" class="required" name="jform[mode_paiement]" id="jform_mode_paiement1" value="<?php echo strtolower(JText::_('COM_ADH_SETTINGS_PAIEMENT_ESPECE_LABEL')); ?>"> <label for="jform_mode_paiement1"><?php echo JText::_('COM_ADH_ADHERER_REGLEMENT_ESPECE'); ?></label>
		<?php endif; ?>
		<?php if ($params->getValue('cheque')) : ?>
			<input type="radio" class="required" name="jform[mode_paiement]" id="jform_mode_paiement2" value="<?php echo strtolower(JText::_('COM_ADH_SETTINGS_PAIEMENT_CHEQUE_LABEL')); ?>"> <label for="jform_mode_paiement2"><?php echo JText::_('COM_ADH_ADHERER_REGLEMENT_CHEQUE'); ?></label>
		<?php endif; ?>
		<?php if ($params->getValue('virement')) : ?>
			<input type="radio" class="required" name="jform[mode_paiement]" id="jform_mode_paiement3" value="<?php echo strtolower(JText::_('COM_ADH_SETTINGS_PAIEMENT_VIREMENT_LABEL')); ?>"> <label for="jform_mode_paiement3"><?php echo JText::_('COM_ADH_ADHERER_REGLEMENT_VIREMENT'); ?></label>
		<?php endif; ?>
		<?php if ($params->getValue('paypal')) : ?>
			<input type="radio" class="required" name="jform[mode_paiement]" id="jform_mode_paiement4" value="<?php echo strtolower(JText::_('COM_ADH_SETTINGS_PAIEMENT_PAYPAL_LABEL')); ?>"> <label for="jform_mode_paiement4"><?php echo JText::_('COM_ADH_ADHERER_REGLEMENT_PAYPAL'); ?></label>
		<?php endif; ?>
	</div>
</div>
<?php endif; ?>

<div class="spacer"></div>

<?php // Validité de l'inscription / Mentions légales ?>
<div class="tr">
	<p><small><?php echo JText::_('COM_ADH_ADHERER_ADHESIONVALIDITE_TXT'); ?>
		<?php switch ($params->getValue('validite_cotisation')) :
			case 0 :	echo JText::_('COM_ADH_ADHERER_ADHESIONVALIDITE_ILLIMITE');
						break;
			case 1 :	echo JText::_('COM_ADH_ADHERER_ADHESIONVALIDITE_ANNEECIVILE');?>
						<input type="radio" name="cotiz_annee" value="<?php echo date("Y"); ?>" checked /><span class="warning"><?php echo date("Y"); ?></span>
						<?php // à partir de Novembre, on affiche le choix de l'année en cours (pour un chantier en décembre par exemple) et l'année prochaine ?>
						<?php if (date("m") >= 11) : ?>
						<input type="radio" name="cotiz_annee" value="<?php echo intval(date("Y")+1); ?>" /><span class="warning"><?php echo intval(date("Y")+1); ?></span>
						<?php endif;
						break;
			case 2 :	echo JText::_('COM_ADH_ADHERER_ADHESIONVALIDITE_ANNEEENCOURS');
						$timestamp = strtotime("+1 year");?>
						<span class="warning"><?php echo ( date("d/m/Y")); ?></span>)
						<?php break;
		endswitch; ?>
	</small><br />

	<?php // Mentions légales ?>
	<small class="mentions_legales"><?php echo ($params->getValue('mentions_legales')); ?></small></p>
</div>
