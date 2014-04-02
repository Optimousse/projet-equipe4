<script>
    $(document).ready(function(){
       $("#txtAvatar").tooltip();
    });
</script>
<h1>Modifier mon compte</h1>

<?php
echo $this->Form->create('Parieur', array(
    'inputDefaults' => array(
        'div' => 'form-group',
        'wrapInput' => false,
        'class' => 'form-control'
    ),
    'role' => 'form', 'enctype'=>"multipart/form-data")); ?>

<div class="form-inline">
        <?php
        echo $this->Form->input('pseudo', array('label'=>'Pseudo:', 'readonly' => 'readonly'));

        echo $this->Form->input('created', array('label'=>'Compte créé le:', 'type' => 'text', 'readonly' => 'readonly'));

        echo $this->Form->input('nombre_jetons', array('label' => 'Nombre de jetons: ('.$this->Html->link('+', array('controller' => 'parieurs', 'action' => 'acheter_jetons')).')', 'readonly' =>'readonly')) ;
        ?>
</div>

<?php

echo $this->Form->input('sexe_id', array(
    'label'=>'Sexe:',
    'options' => $ddlSexe,
    'empty' => '(Choisissez un sexe)'
));
echo $this->Form->input('avatar', array(
    'label' => 'Avatar:',
    'type' => 'file',
    'id' => 'txtAvatar',
    'class' =>'',
    'data-toggle'=>"tooltip",
    'data-placement'=>"top",
    'title'=>"Doit être dans l'un des formats suivants: jpg, png, gif. Taille maximale: 2 Mo"
));

if(isset($avatar)){

    echo $this->Form->input('avatar.delete', array(
        'type' => 'checkbox',
        'class' => false,
        'label' => 'Delete this image'));
    echo $this->Html->image('avatars/'.$avatar, array('class'=>'img-rounded'));
}
?>
<?php
    echo $this->Form->input('mot_passe', array('type' => 'password', 'label'=>'Mot de passe:'));
    echo $this->Form->input('mot_passe_confirmation', array('label'=>'Confirmation du mot de passe:', 'type' =>'password', 'required' => false));
    echo $this->Form->input('courriel', array('label'=>'Adresse courriel:', 'type' => 'email'));
?>

<?php echo $this->Form->input('id', array('type' => 'hidden')); ?>
<?php echo $this->Form->submit('Soumettre', array(
'class' => 'btn btn-primary'
)); ?>

<?php echo $this->Form->end(); ?>

