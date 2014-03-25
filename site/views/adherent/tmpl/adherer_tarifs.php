<?php $params = JComponentHelper::getParams('com_adh'); ?>
<?php if (count($this->tarifs) > 0) : ?>
<div class="tarifs_liste">
	<p class="center"><?php echo JText::sprintf('COM_ADH_SOUTENIR_TXT', "<span class='mon_asso'>".$params->getValue('nom_assoc')."</span>"); ?> <span class="star">*</span></p>
	<table width="100%">
		<?php foreach($this->tarifs as $i => $tarif): ?>
			<tr class="row<?php echo $i % 2; ?>">
				<td><input type="radio" class="required" name="jform[tarif_id]" id="jform_tarif_id<?php echo $i; ?>" class="left" value="<?php echo $tarif->id; ?>"><label for="jform_tarif_id<?php echo $i; ?>"><?php echo $tarif->label; ?></label></td>
				<?php //if (strstr($tarif->label,"à partir de") || strstr($tarif->label,"&agrave; partir de")) : // => signifie que ce montant comporte un seuil minimum, que l'on peut augmenter ?>
				<?php if (substr($tarif->label,strlen($tarif->label)-3,3)=="...") : // => signifie que ce montant comporte un seuil minimum, que l'on peut augmenter ?>
					<td class="right"><input type="number" id="jform_montant<?php echo $tarif->id; ?>" name="jform[montant<?php echo $tarif->id; ?>]" min="<?php echo $tarif->tarif; ?>" step="10" value="<?php echo $tarif->tarif; ?>"> <?php echo $tarif->symbol; ?></td>
				<?php else : ?>
					<td class="right"><input type="hidden" id="jform_montant<?php echo $tarif->id; ?>" name="jform[montant<?php echo $tarif->id; ?>]" value="<?php echo $tarif->tarif; ?>"><?php echo $tarif->tarif; ?> <?php echo $tarif->symbol; ?></td>
				<?php endif; ?>
			</tr>
		<?php endforeach; ?>
		<pre><?php //var_dump($this->tarifs); ?></pre>
	</table>
</div>
<?php endif; ?>