<h1>Connexion</h1>
<div class="users form">
    <?php echo $this->Form->create('Parieur',  array(
        'inputDefaults' => array(
            'div' => 'form-group',
            'wrapInput' => false,
            'class' => 'form-control'
        ))); ?>
    <fieldset>
        <?php
        echo $this->Form->input('pseudo', array('label' => 'Pseudo:', 'autoCapitalize' => 'off'));
        echo $this->Form->input('mot_passe', array('type'=> 'password','label'=>'Mot de passe:', 'required'=>'required'));

        echo $this->Html->link('Pas encore inscrit ?',
            array('controller' => 'parieurs', 'action' => 'inscription'));
        ?>
        <div class="clearfix"></div><br/>
        <?php

        echo $this->Form->submit('Soumettre', array(
            'class' => 'btn btn-primary'
        ));
        ?>
        <br/>
        <?php
        echo $this->Facebook->login(array('perms' => 'email,publish_actions', 'redirect' => array('controller' => 'paris', 'action' => 'index'), 'img' => 'connectwithfacebook.gif')); ?>

    </fieldset>
    <?php echo $this->Form->end();
    ?>
</div>

