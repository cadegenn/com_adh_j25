<?php
/**
 * @package		com_adh
 * @subpackage	
 * @brief		com_adh helps you manage the people within an association
 * @copyright	Copyright (C) 2010 - 2014 DEGENNES Charles-Antoine <cadegenn@gmail.com>
 * @license		Affero GNU General Public License version 3 or later; see LICENSE.txt
 * 
 * @TODO		use cotiz helper to display cotisations's pane
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
				<div class="ttd"><?php echo $this->form->getField("titre")->input; ?></div>
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
						<?php foreach($this->form->getFieldset('user_publish_options') as $field) :?>
							<li><?php echo $field->label; ?>
							<?php echo $field->input; ?></li>
						<?php endforeach; ?>
					</ul>
				</fieldset>
		<?php echo JHtml::_('sliders.end'); ?>

		<?php echo JHtml::_('sliders.start', 'content-sliders-'.$this->item->id, array('useCookie'=>1)); ?>
			<?php echo JHtml::_('sliders.panel', JText::_('COM_ADH_OPTIONS'), 'meta-options'); ?>
				<fieldset class="panelform">
					<ul class="adminformlist">
						<?php foreach($this->form->getFieldset('user_options') as $field) :?>
							<li><?php echo $field->label; ?>
							<?php echo $field->input; ?></li>
						<?php endforeach; ?>
					</ul>
				</fieldset>
		<?php echo JHtml::_('sliders.end'); ?>

		<?php echo JHtml::_('sliders.start', 'content-sliders-'.$this->item->id, array('useCookie'=>1)); ?>
			<?php echo JHtml::_('sliders.panel', JText::_('COM_ADH_FIELDSET_COTISATIONS'), 'meta-options'); ?>
				<fieldset class="panelform">
					<label><a href='<?php echo JRoute::_('index.php?option=com_adh&view=cotisation&layout=edit&id=0&adherent_id='.$this->form->getField("id")->value); ?>' target='_blank'><?php echo JText::_('COM_ADH_FIELDSET_COTISATIONS_NEW'); ?></a></label>
					<ul id="ul_cotiz" class="adminformlist">
						<?php 
							foreach ($this->item->cotiz as $cotiz) : ?>
							<li>
								<label class='cotiz_date hastip' title='<?php echo $cotiz->date_debut_cotiz; ?>'>
									<?php if (!$cotiz->payee) : ?>
										<img src='<?php echo JURI::base(); ?>/components/com_adh/images/ico-16x16/error.png' alt='error.png' style='margin: 0 5px 0 0;' />
									<?php else : ?>
										<img src='<?php echo JURI::base(); ?>/components/com_adh/images/ico-16x16/accept.png' alt='accept.png' style='margin: 0 5px 0 0;' />
									<?php endif; ?>
									&nbsp;<?php echo date('Y',strtotime($cotiz->date_debut_cotiz)); ?>
								</label>
								<input id="cotiz<?php echo $cotiz->id; ?>_prix" name="cotiz<?php echo $cotiz->id; ?>[prix]" class='readonly prix hastip' readonly='readonly' value="<?php echo($cotiz->montant." ".$params->getValue('symbol')); ?>" />
								<span><?php echo JText::_('COM_ADH_FIELDSET_COTISATIONS_PAR'); ?></span>
								<input id="cotiz<?php echo $cotiz->id; ?>_mode_paiement" name="cotiz<?php echo $cotiz->id; ?>[mode_paiement]" class='readonly right' readonly='readonly' value='<?php echo($cotiz->mode_paiement); ?>' size='10' />
								<span><a href='<?php echo JRoute::_('index.php?option=com_adh&view=cotisation&layout=edit&id=' . $cotiz->id); ?>'><?php echo JText::_('JACTION_EDIT'); ?></a></span>
							</li>
							<?php endforeach; ?>
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

<?php if (JDEBUG): ?>
<pre style="clear: both;">
    <?php var_dump($this); ?>
</pre>
<?php endif; ?>
