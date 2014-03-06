<?php echo $this->Form->create('Parieur', array('class'=>'well')); ?>

<legend><?php echo __('Modifier mes informations'); ?></legend>


<?php
echo $this->Form->input('pseudo', array('label'=>'Pseudo:', 'readonly' => 'readonly'));
echo $this->Form->input('mot_passe', array('type' => 'password', 'label'=>'Mot de passe:'));
echo $this->Form->input('mot_passe_confirmation', array('label'=>'Confirmation du mot de passe:', 'type' =>'password', 'required' => false));
echo $this->Form->input('courriel', array('label'=>'Adresse courriel:', 'type' => 'email'));
echo $this->Form->input('nombre_jetons', array('label' => 'Nombre de jetons:', 'readonly' =>'readonly'));
?>
<div class="input text"><?php echo $this->Html->link('Acheter des jetons', array('controller' => 'parieurs', 'action' => 'acheter_jetons')); ?></div>
<br/>

<?php echo $this->Form->input('id', array('type' => 'hidden')); ?>
<?php echo $this->Form->submit('Soumettre', array(
'class' => 'btn btn-primary'
)); ?>

<?php echo $this->Form->end(); ?>

