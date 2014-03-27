<h1>Modifier mon compte</h1>

<?php
echo $this->Form->create('Parieur', array(
    'inputDefaults' => array(
        'div' => 'form-group',
        'wrapInput' => false,
        'class' => 'form-control'
    ),
    'role' => 'form', 'enctype'=>"multipart/form-data")); ?>

<?php
    echo $this->Form->input('pseudo', array('label'=>'Pseudo:', 'readonly' => 'readonly'));

    echo $this->Form->input('sexe_id', array(
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

        echo $this->Html->image('avatars/'.$avatar, array('class'=>'img-rounded'));
        echo $this->Form->input('avatar.delete', array(
            'type' => 'checkbox',
            'class' => false,
            'label' => 'Delete this image'));
    }

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

