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
<div class='width-50 fltlft'>
<form action="<?php echo JRoute::_('index.php?option=com_adh&layout=edit&id='.(int) $this->user1->id.'&user1id='.(int) $this->user1->id.'&user2id='.(int) $this->form2->getField('id')->value); ?>"
      method="post" name="adminForm" id="adminFormUser1">
	<div>
		<?php	$bar1 = JToolBar::getInstance('toolbar-user1');
				echo $bar1->render();
		?>
		<div class="clr"></div>
		<fieldset class="adminform" id="fs_detailsUser1">
			<legend><?php echo JText::_( 'COM_ADH_ADHERENT_DETAILS' ); ?> <small>(<?php echo (int) $this->user1->id; ?>)</small></legend>
			<a class="fltrt" href="#none" onclick="javascript:adh_details_copy('fs_detailsUser1', 'fs_detailsUser2', 'jform1', 'jform2');"><img src="<?php echo JURI::base(); ?>components/com_adh/images/ico-32x32/go-next-5.png" alt="<?php echo JText::_( 'COM_ADH_ADHERENT_DETAILS_COPY_TO_RIGHT_SIDE' ); ?>" title="<?php echo JText::_( 'COM_ADH_ADHERENT_DETAILS_COPY_TO_RIGHT_SIDE' ); ?>" /></a>
			<div class="clr"></div>
			<div class="tr">
				<div class="tth"><?php echo $this->form1->getField("titre")->label; ?></div>
				<div class="ttd"><?php echo $this->form1->getField("titre")->input; ?></div>
				<div class="tth"><?php echo $this->form1->getField("personne_morale")->label; ?></div>
				<div class="ttd"><?php echo html_entity_decode($this->form1->getField("personne_morale")->input); ?></div>
			</div>
			<div class="tr">
				<div class="tth"><?php echo $this->form1->getField("nom")->label; ?></div>
				<div class="ttd"><?php echo html_entity_decode($this->form1->getField("nom")->input); ?></div>
				<div class="tth"><?php echo $this->form1->getField("prenom")->label; ?></div>
				<div class="ttd"><?php echo html_entity_decode($this->form1->getField("prenom")->input); ?></div>
			</div>
			<div class="tr">
				<div class="tth"><?php echo $this->form1->getField("date_naissance")->label; ?></div>
				<div class="ttd"><?php echo html_entity_decode($this->form1->getField("date_naissance")->input); ?></div>
				<div class="tth"><?php echo $this->form1->getField("profession_id")->label; ?></div>
				<div class="ttd"><?php echo $this->form1->getField("profession_id")->input; ?></div>
			</div>
			<div class="ttr">
				<div class="tth"><?php echo $this->form1->getField("email")->label; ?></div>
				<div class="ttd"><?php echo html_entity_decode($this->form1->getField("email")->input); ?></div>
				<div class="tth"><?php echo $this->form1->getField("password")->label; ?></div>
				<div class="ttd"><input type="password" name="jform[password]" id="jform_password" value="" class="inputbox"> <?php // on ne veut pas pré-remplir le champs avec le mot de passe encrypté; ?></div>
			</div>
			<div class="tr">
				<div class="tth"><?php echo $this->form1->getField("telephone")->label; ?></div>
				<div class="ttd"><?php echo html_entity_decode($this->form1->getField("telephone")->input); ?></div>
				<div class="tth"><?php echo $this->form1->getField("gsm")->label; ?></div>
				<div class="ttd"><?php echo html_entity_decode($this->form1->getField("gsm")->input); ?></div>
			</div>
			<div class="tr">
				<div class="tth"><?php echo $this->form1->getField("adresse")->label; ?></div>
				<div class="td"><?php echo html_entity_decode($this->form1->getField("adresse")->input); ?></div>
			</div>
			<div class="tr">
				<div class="tth"><?php echo $this->form1->getField("adresse2")->label; ?></div>
				<div class="ttd"><?php echo html_entity_decode($this->form1->getField("adresse2")->input); ?></div>
				<div class="tth"><?php echo $this->form1->getField("cp")->label; ?></div>
				<div class="ttd"><?php echo html_entity_decode($this->form1->getField("cp")->input); ?></div>
			</div>
			<div class="tr">
				<div class="tth"><?php echo $this->form1->getField("ville")->label; ?></div>
				<div class="ttd"><?php echo html_entity_decode($this->form1->getField("ville")->input); ?></div>
				<div class="tth"><?php echo $this->form1->getField("pays")->label; ?></div>
				<div class="ttd"><?php echo html_entity_decode($this->form1->getField("pays")->input); ?></div>
			</div>
			<div class="tr">
				<div class="tth"><?php echo $this->form1->getField("description")->label; ?></div>
				<div class="td"><?php echo $this->form1->getField("description")->input; ?></div>
			</div>
		</fieldset>

		<fieldset class="adminform" id="fs_publishUser1">
			<legend><?php echo JText::_( 'COM_ADH_FIELDSET_PUBLISHING' ); ?></legend>
			<a class="fltrt" href="#none" onclick="javascript:adh_details_copy('fs_publishUser1', 'fs_publishUser2', 'jform1', 'jform2');"><img src="<?php echo JURI::base(); ?>components/com_adh/images/ico-32x32/go-next-5.png" alt="<?php echo JText::_( 'COM_ADH_ADHERENT_DETAILS_COPY_TO_RIGHT_SIDE' ); ?>" title="<?php echo JText::_( 'COM_ADH_ADHERENT_DETAILS_COPY_TO_RIGHT_SIDE' ); ?>" /></a>
			<ul class="adminformlist">
				<?php foreach($this->form1->getFieldset('user_publish_options') as $field) :?>
					<li><?php echo $field->label; ?>
					<?php echo $field->input; ?></li>
				<?php endforeach; ?>
			</ul>
		</fieldset>

		<fieldset class="adminform" id="fs_optionsUser1">
			<legend><?php echo JText::_( 'COM_ADH_OPTIONS' ); ?></legend>
			<a class="fltrt" href="#none" onclick="javascript:adh_details_copy('fs_optionsUser1', 'fs_optionsUser2', 'jform1', 'jform2');"><img src="<?php echo JURI::base(); ?>components/com_adh/images/ico-32x32/go-next-5.png" alt="<?php echo JText::_( 'COM_ADH_ADHERENT_DETAILS_COPY_TO_RIGHT_SIDE' ); ?>" title="<?php echo JText::_( 'COM_ADH_ADHERENT_DETAILS_COPY_TO_RIGHT_SIDE' ); ?>" /></a>
			<ul class="adminformlist">
				<?php foreach($this->form1->getFieldset('user_options') as $field) :?>
					<li><?php echo $field->label; ?>
					<?php echo $field->input; ?></li>
				<?php endforeach; ?>
			</ul>
		</fieldset>
		<input type="hidden" name="task" value="adherent.edit" />
        <?php JFactory::getApplication()->setUserState('com_adh.edit.1anomalie.user1.id', (int) $this->user1->id); ?>
		<?php echo JHtml::_('form.token'); ?>
		
		<?php $session = JFactory::getSession();
		$registry = $session->get('registry');?>
	</div>
</form>

<form action="<?php echo JRoute::_('index.php?option=com_adh&layout=edit&id='.(int) $this->user1->id.'&user1id='.(int) $this->user1->id.'&user2id='.(int) $this->form2->getField('id')->value); ?>"
      method="post" name="adminForm" id="adminFormUser1Cotiz">
	<div>
		<fieldset class="adminform" id="fs_cotizUser1">
			<legend><?php echo JText::_( 'COM_ADH_FIELDSET_COTISATIONS' ); ?></legend>
			<input type="hidden" id="adherent_id" name="adherent_id" value="<?php echo $this->user1->id; ?>">
			<a class="fltrt" href="#none" onclick="javascript:Joomla.submitform('1anomalie.moveCotiz', document.getElementById('adminFormUser1Cotiz'));"><img src="<?php echo JURI::base(); ?>components/com_adh/images/ico-32x32/go-next-5.png" alt="<?php echo JText::_( 'COM_ADH_ADHERENT_DETAILS_COPY_TO_RIGHT_SIDE' ); ?>" title="<?php echo JText::_( 'COM_ADH_ADHERENT_DETAILS_COPY_TO_RIGHT_SIDE' ); ?>" /></a>
			<label><a href='<?php echo JRoute::_('index.php?option=com_adh&view=cotisation&layout=edit&id=0&adherent_id='.$this->user1->id); ?>'><?php echo JText::_('COM_ADH_FIELDSET_COTISATIONS_NEW'); ?></a></label>
			<ul id="ul_cotiz_<?php echo $this->user1->id; ?>" class="adminformlist">
				<?php 
					foreach ($this->user1->cotiz as $cotiz) : ?>
					<li>
						<input type="hidden" id="cb<?php echo $cotiz->id; ?>" name="cid[]" value="<?php echo $cotiz->id; ?>">
						<label class='cotiz_date hastip' title='<?php echo $cotiz->date_debut_cotiz; ?>'>
							<?php if (!$cotiz->payee) : ?>
								<img src='<?php echo JURI::base(); ?>/components/com_adh/images/ico-16x16/error.png' alt='error.png' style='margin: 0 5px 0 0;' />
							<?php else : ?>
								<img src='<?php echo JURI::base(); ?>/components/com_adh/images/ico-16x16/accept.png' alt='accept.png' style='margin: 0 5px 0 0;' />
							<?php endif; ?>
							&nbsp;<?php echo date('Y',strtotime($cotiz->date_debut_cotiz)); ?>
						</label>
						<input id="cotiz<?php echo $cotiz->id; ?>_prix" name="cotiz<?php echo $cotiz->id; ?>[prix]" class='readonly hastip' readonly='readonly' value="<?php echo($cotiz->montant." ".$params->getValue('symbol')." ".JText::_('COM_ADH_FIELDSET_COTISATIONS_PAR')." ".$cotiz->mode_paiement); ?>" />
						<ul class="jicons_cotiz_action fltrt">
							<li class="fltlft"><a href='<?php echo JRoute::_('index.php?option=com_adh&view=cotisation&layout=edit&id=' . $cotiz->id); ?>'><?php echo JText::_('JACTION_EDIT'); ?></a></li>
							<li class="fltlft"><a href='<?php echo JRoute::_('index.php?option=com_adh&layout=edit&id='.(int) $this->user1->id.'&user1id='.(int) $this->user1->id.'&user2id='.(int) $this->form2->getField('id')->value.'&cotiz_id=' . $cotiz->id.'&task=1anomalie.deleteCotiz'); ?>'><?php echo JText::_('JACTION_DELETE'); ?></a></li>
							<!--<li class="fltlft"><a href="#none" onclick="javascript:Joomla.submitform('1anomalie.deleteCotiz', document.getElementById('adminFormUser1Cotiz'));"><?php echo JText::_('JACTION_DELETE'); ?></a></li>-->
						</ul>
					</li>
					<?php endforeach; ?>
			</ul>
		</fieldset>
		<input type="hidden" name="task" value="adherent.moveCotiz" />
        <?php JFactory::getApplication()->setUserState('com_adh.edit.1anomalie.user1.id', (int) $this->user1->id); ?>
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
</div>


<?php if (JDEBUG):
	//echo("<pre>"); var_dump($bar1); echo("</pre>");
	//echo("<pre>"); var_dump($registry->get('com_adh.edit.1anomalie.user1.id')); echo("</pre>");
	//echo("<pre>"); var_dump($this->form1); echo("</pre>");
endif; ?>
