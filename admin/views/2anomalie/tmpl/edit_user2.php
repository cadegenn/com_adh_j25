<?php
/**
 * @package		com_adh
 * @subpackage	
 * @brief		com_adh helps you manage the people within an association
 * @copyright	Copyright (C) 2010 - 2014 DEGENNES Charles-Antoine <cadegenn@gmail.com>
 * @license		Affero GNU General Public License version 3 or later; see LICENSE.txt
 * 
 * @TODO		use cotiz helper to display cotisations and delete $param variable
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

<form action="<?php echo JRoute::_('index.php?option=com_adh&layout=edit&id='.(int) $this->form2->getField('id')->value.'&user1id='.(int) $this->form1->getField('id')->value.'&user2id='.(int) $this->form2->getField('id')->value); ?>"
      method="post" name="adminForm" id="adminFormUser2">
	<div class='width-50 fltrt'>
		<?php	$bar1 = JToolBar::getInstance('toolbar-user2');
				echo $bar1->render();
		?>
		<div class="clr"></div>
		<fieldset class="adminform">
			<legend><?php echo JText::_( 'COM_ADH_ADHERENT_DETAILS' ); ?> <small>(<?php echo (int) $this->form2->getField('id')->value; ?>)</small></legend>
			<div class="tr">
				<div class="tth"><?php echo $this->form2->getField("titre")->label; ?></div>
				<div class="ttd"><?php echo $this->form2->getField("titre")->input; ?></div>
				<div class="tth"><?php echo $this->form2->getField("personne_morale")->label; ?></div>
				<div class="ttd"><?php echo html_entity_decode($this->form2->getField("personne_morale")->input); ?></div>
			</div>
			<div class="tr">
				<div class="tth"><?php echo $this->form2->getField("nom")->label; ?></div>
				<div class="ttd"><?php echo html_entity_decode($this->form2->getField("nom")->input); ?></div>
				<div class="tth"><?php echo $this->form2->getField("prenom")->label; ?></div>
				<div class="ttd"><?php echo html_entity_decode($this->form2->getField("prenom")->input); ?></div>
			</div>
			<div class="tr">
				<div class="tth"><?php echo $this->form2->getField("date_naissance")->label; ?></div>
				<div class="ttd"><?php echo html_entity_decode($this->form2->getField("date_naissance")->input); ?></div>
				<div class="tth"><?php echo $this->form2->getField("profession_id")->label; ?></div>
				<div class="ttd"><?php echo $this->form2->getField("profession_id")->input; ?></div>
			</div>
			<div class="ttr">
				<div class="tth"><?php echo $this->form2->getField("email")->label; ?></div>
				<div class="ttd"><?php echo html_entity_decode($this->form2->getField("email")->input); ?></div>
				<div class="tth"><?php echo $this->form2->getField("password")->label; ?></div>
				<div class="ttd"><input type="password" name="jform[password]" id="jform_password" value="" class="inputbox"> <?php // on ne veut pas pré-remplir le champs avec le mot de passe encrypté; ?></div>
			</div>
			<div class="tr">
				<div class="tth"><?php echo $this->form2->getField("telephone")->label; ?></div>
				<div class="ttd"><?php echo html_entity_decode($this->form2->getField("telephone")->input); ?></div>
				<div class="tth"><?php echo $this->form2->getField("gsm")->label; ?></div>
				<div class="ttd"><?php echo html_entity_decode($this->form2->getField("gsm")->input); ?></div>
			</div>
			<div class="tr">
				<div class="tth"><?php echo $this->form2->getField("adresse")->label; ?></div>
				<div class="td"><?php echo html_entity_decode($this->form2->getField("adresse")->input); ?></div>
			</div>
			<div class="tr">
				<div class="tth"><?php echo $this->form2->getField("adresse2")->label; ?></div>
				<div class="ttd"><?php echo html_entity_decode($this->form2->getField("adresse2")->input); ?></div>
				<div class="tth"><?php echo $this->form2->getField("cp")->label; ?></div>
				<div class="ttd"><?php echo html_entity_decode($this->form2->getField("cp")->input); ?></div>
			</div>
			<div class="tr">
				<div class="tth"><?php echo $this->form2->getField("ville")->label; ?></div>
				<div class="ttd"><?php echo html_entity_decode($this->form2->getField("ville")->input); ?></div>
				<div class="tth"><?php echo $this->form2->getField("pays")->label; ?></div>
				<div class="ttd"><?php echo html_entity_decode($this->form2->getField("pays")->input); ?></div>
			</div>
			<div class="tr">
				<div class="tth"><?php echo $this->form2->getField("description")->label; ?></div>
				<div class="td"><?php echo $this->form2->getField("description")->input; ?></div>
			</div>
		</fieldset>

		<?php echo JHtml::_('sliders.start', 'content-sliders-'.(int) $this->form2->getField('id')->value, array('useCookie'=>1)); ?>
			<?php echo JHtml::_('sliders.panel', JText::_('COM_ADH_FIELDSET_PUBLISHING'), 'meta-options'); ?>
				<fieldset class="panelform">
					<ul class="adminformlist">
						<li><?php echo $this->form2->getLabel('creation_date'); ?>
						<?php echo $this->form2->getInput('creation_date'); ?></li>
						
						<li><?php echo $this->form2->getLabel('published'); ?>
						<?php echo $this->form2->getInput('published'); ?></li>
						
						<li><?php echo $this->form2->getLabel('modified_by'); ?>
						<?php echo $this->form2->getInput('modified_by'); ?></li>
						
						<li><?php echo $this->form2->getLabel('modification_date'); ?>
						<?php echo $this->form2->getInput('modification_date'); ?></li>
					</ul>
				</fieldset>
		<?php echo JHtml::_('sliders.end'); ?>

		<?php echo JHtml::_('sliders.start', 'content-sliders-'.(int) $this->form2->getField('id')->value, array('useCookie'=>1)); ?>
			<?php echo JHtml::_('sliders.panel', JText::_('COM_ADH_OPTIONS'), 'meta-options'); ?>
				<fieldset class="panelform">
					<ul class="adminformlist">
						<li><?php echo $this->form2->getLabel('imposable'); ?>
						<?php echo $this->form2->getInput('imposable'); ?></li>
						
						<li><?php echo $this->form2->getLabel('recv_newsletter'); ?>
						<?php echo $this->form2->getInput('recv_newsletter'); ?></li>
						
						<li><?php echo $this->form2->getLabel('recv_infos'); ?>
						<?php echo $this->form2->getInput('recv_infos'); ?></li>
					</ul>
				</fieldset>
		<?php echo JHtml::_('sliders.end'); ?>

		<?php echo JHtml::_('sliders.start', 'content-sliders-'.(int) $this->form2->getField('id')->value, array('useCookie'=>1)); ?>
			<?php echo JHtml::_('sliders.panel', JText::_('COM_ADH_FIELDSET_COTISATIONS'), 'meta-options'); ?>
				<fieldset class="panelform">
					<label><a href='<?php echo JRoute::_('index.php?option=com_adh&view=cotisation&layout=edit&id=0&adherent_id='.$this->form2->getField("id")->value); ?>'><?php echo JText::_('COM_ADH_FIELDSET_COTISATIONS_NEW'); ?></a></label>
					<!--<ul class="adminformlist">-->
						<?php 
							if ((int) $this->form2->getField('id')->value != 0) : 
								$db = JFactory::getDbo();
								$query = $db->getQuery(true);
								$query->select('#__adh_cotisations.*, #__adh_tarifs.label as tarif')->from('#__adh_cotisations');
								$query->leftJoin('#__adh_tarifs ON (#__adh_cotisations.tarif_id = #__adh_tarifs.id)');
								$query->where('adherent_id = "'.$this->form2->getField("id")->value.'"');
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

		<input type="hidden" name="task" value="adherent.edit" />
        <?php JFactory::getApplication()->setUserState('com_adh.edit.1anomalie.user2.id', (int) $this->form2->getField('id')->value); ?>
		<?php echo JHtml::_('form.token'); ?>

		<pre><?php //var_dump($bar1); ?></pre>
		<?php $session = JFactory::getSession();
		$registry = $session->get('registry');?>
		<pre><?php //var_dump($registry->get('com_adh.edit.1anomalie.user2.id')); ?></pre>
	</div>
</form>
