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
<!--<pre><?php //var_dump($params); ?></pre>-->

<form action="<?php echo JRoute::_('index.php?option=com_adh&layout=edit&id='.(int) $this->item->id); ?>"
      method="post" name="adminForm" id="adherent-form">
	<div class='width-60 fltlft'>
		<fieldset class="adminform">
			<legend><?php echo JText::_( 'COM_ADH_ADHERENT_DETAILS' ); ?> <small>(<?php echo $this->item->id; ?>)</small></legend>
			<div class="tr">
				<div class="tth"><?php echo $this->form->getField("titre")->label; ?></div>
				<div class="ttd"><?php echo ADHControls::buildSelectTitres($this->form->getField("titre")->value); ?></div>
				<div class="tth"><?php echo $this->form->getField("personne_morale")->label; ?></div>
				<div class="ttd"><?php echo html_entity_decode($this->form->getField("personne_morale")->input); ?></div>
			</div>
			<div class="tr">
				<div class="tth"><?php echo $this->form->getField("nom")->label; ?></div>
				<div class="ttd"><?php echo html_entity_decode($this->form->getField("nom")->input); ?></div>
				<div class="tth"><?php echo $this->form->getField("prenom")->label; ?></div>
				<div class="ttd"><?php echo html_entity_decode($this->form->getField("prenom")->input); ?></div>
			</div>
			<div class="tr">
				<div class="tth"><?php echo $this->form->getField("date_naissance")->label; ?></div>
				<div class="ttd"><?php echo html_entity_decode($this->form->getField("date_naissance")->input); ?></div>
				<div class="tth"><?php echo $this->form->getField("profession_id")->label; ?></div>
				<div class="ttd"><?php echo $this->form->getField("profession_id")->input; ?></div>
			</div>
			<div class="ttr">
				<div class="tth"><?php echo $this->form->getField("email")->label; ?></div>
				<div class="ttd"><?php echo html_entity_decode($this->form->getField("email")->input); ?></div>
				<div class="tth"><?php echo $this->form->getField("password")->label; ?></div>
				<div class="ttd"><input type="password" name="jform[password]" id="jform_password" value="" class="inputbox"> <?php // on ne veut pas pré-remplir le champs avec le mot de passe encrypté; ?></div>
			</div>
			<div class="tr">
				<div class="tth"><?php echo $this->form->getField("telephone")->label; ?></div>
				<div class="ttd"><?php echo html_entity_decode($this->form->getField("telephone")->input); ?></div>
				<div class="tth"><?php echo $this->form->getField("gsm")->label; ?></div>
				<div class="ttd"><?php echo html_entity_decode($this->form->getField("gsm")->input); ?></div>
			</div>
			<div class="tr">
				<div class="tth"><?php echo $this->form->getField("adresse")->label; ?></div>
				<div class="td"><?php echo html_entity_decode($this->form->getField("adresse")->input); ?></div>
			</div>
			<div class="tr">
				<div class="tth"><?php echo $this->form->getField("adresse2")->label; ?></div>
				<div class="ttd"><?php echo html_entity_decode($this->form->getField("adresse2")->input); ?></div>
				<div class="tth"><?php echo $this->form->getField("cp")->label; ?></div>
				<div class="ttd"><?php echo html_entity_decode($this->form->getField("cp")->input); ?></div>
			</div>
			<div class="tr">
				<div class="tth"><?php echo $this->form->getField("ville")->label; ?></div>
				<div class="ttd"><?php echo html_entity_decode($this->form->getField("ville")->input); ?></div>
				<div class="tth"><?php echo $this->form->getField("pays")->label; ?></div>
				<div class="ttd"><?php echo html_entity_decode($this->form->getField("pays")->input); ?></div>
			</div>
			<div class="tr">
				<div class="tth"><?php echo $this->form->getField("description")->label; ?></div>
				<div class="td"><?php echo $this->form->getField("description")->input; ?></div>
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

		<?php echo JHtml::_('sliders.start', 'content-sliders-'.$this->item->id, array('useCookie'=>1)); ?>
			<?php echo JHtml::_('sliders.panel', JText::_('COM_ADH_OPTIONS'), 'meta-options'); ?>
				<fieldset class="panelform">
					<ul class="adminformlist">
						<li><?php echo $this->form->getLabel('imposable'); ?>
						<?php echo $this->form->getInput('imposable'); ?></li>
						
						<li><?php echo $this->form->getLabel('recv_newsletter'); ?>
						<?php echo $this->form->getInput('recv_newsletter'); ?></li>
						
						<li><?php echo $this->form->getLabel('recv_infos'); ?>
						<?php echo $this->form->getInput('recv_infos'); ?></li>
					</ul>
				</fieldset>
		<?php echo JHtml::_('sliders.end'); ?>

		<?php echo JHtml::_('sliders.start', 'content-sliders-'.$this->item->id, array('useCookie'=>1)); ?>
			<?php echo JHtml::_('sliders.panel', JText::_('COM_ADH_FIELDSET_COTISATIONS'), 'meta-options'); ?>
				<fieldset class="panelform">
					<label><a href='<?php echo JRoute::_('index.php?option=com_adh&view=cotisation&layout=edit&id=0&adherent_id='.$this->form->getField("id")->value); ?>'><?php echo JText::_('COM_ADH_FIELDSET_COTISATIONS_NEW'); ?></a></label>
					<!--<ul class="adminformlist">-->
						<?php 
							if ($this->item->id != 0) : 
								$db = JFactory::getDbo();
								$query = $db->getQuery(true);
								$query->select('#__adh_cotisations.*, #__adh_tarifs.label as tarif')->from('#__adh_cotisations');
								$query->leftJoin('#__adh_tarifs ON (#__adh_cotisations.tarif_id = #__adh_tarifs.id)');
								$query->where('adherent_id = "'.$this->form->getField("id")->value.'"');
								$query->order('creation_date DESC');
								$db->setQuery($query);
								$db->execute();
								$rows = $db->loadObjectList();
								foreach ($rows as $row) : ?>
							<li>
								<label class='cotiz_date hastip' title='<?php echo $row->date_debut_cotiz; ?>'><?php if (!$row->payee) : ?><img src='<?php echo JURI::base(); ?>/components/com_adh/images/ico-16x16/error.png' alt='error.png' style='margin: 0 5px 0 0;' /><?php endif; ?> <?php echo date('Y',strtotime($row->date_debut_cotiz)); ?></label>
								<input class='readonly prix hastip' readonly='readonly' value="<?php echo($row->montant." ".$params->getValue('symbol')); ?>" title="<?php echo($row->tarif); ?>"/>
								<span><?php echo JText::_('COM_ADH_FIELDSET_COTISATIONS_PAR'); ?></span>
								<input class='readonly right' readonly='readonly' value='<?php echo($row->mode_paiement); ?>' size='10' />
								<span><a href='<?php echo JRoute::_('index.php?option=com_adh&view=cotisation&layout=edit&id=' . $row->id); ?>'><?php echo JText::_('JACTION_EDIT'); ?></a></span>
							</li>
							<?php endforeach; ?>
						<?php endif; ?>
					</ul>
				</fieldset>
		<?php echo JHtml::_('sliders.end'); ?>

	</div>
	<div>
		<input type="hidden" name="task" value="adherent.edit" />
        <?php JFactory::getApplication()->setUserState('com_adh.edit.adherent.id', (int) $this->item->id); ?>
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>

<!--<pre>
    <?php //echo var_dump($this); ?>
</pre>-->