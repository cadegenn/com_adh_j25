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
<div class="tr">
	<div class="tth"><?php echo $this->form->getField("email")->label; ?></div>
	<div class="td"><?php echo html_entity_decode($this->form->getField("email")->input); ?></div>
</div>
<div class="tr">
	<div class="tth"><?php echo $this->form->getField("telephone")->label; ?></div>
	<div class="ttd"><?php echo html_entity_decode($this->form->getField("telephone")->input); ?></div>
	<div class="tth"><?php echo $this->form->getField("gsm")->label; ?></div>
	<div class="ttd"><?php echo html_entity_decode($this->form->getField("gsm")->input); ?></div>
</div>
<div class="tr">
	<div class="tth"><?php echo $this->form->getField("adresseAC")->label; ?></div>
	<div class="td"><?php echo html_entity_decode($this->form->getField("adresseAC")->input); ?>
					<?php echo html_entity_decode($this->form->getField("adresse")->input); ?></div>
</div>
<div class="tr">
	<div class="tth"><?php echo $this->form->getField("adresse2")->label; ?></div>
	<div class="ttd"><?php echo html_entity_decode($this->form->getField("adresse2")->input); ?></div>
	<div class="tth"><?php echo $this->form->getField("cp")->label; ?></div>
	<div class="ttd"><?php echo html_entity_decode($this->form->getField("cp")->input); ?></div>
</div>
<div class="tr">
	<div class="tth"><?php echo $this->form->getField("ville")->label; ?></div>
	<div class="ttd"><?php //echo html_entity_decode($this->form->getField("villeAC")->input); ?>
					 <?php echo html_entity_decode($this->form->getField("ville")->input); ?></div>
	<div class="tth"><?php echo $this->form->getField("pays")->label; ?></div>
	<div class="ttd"><?php //echo html_entity_decode($this->form->getField("paysAC")->input); ?>
					 <?php echo html_entity_decode($this->form->getField("pays")->input); ?></div>
</div>
