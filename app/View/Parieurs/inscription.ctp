<!-- app/View/Users/add.ctp -->
<h1>Inscription</h1>

<p>
    L'inscription est simple, rapide et gratuite ! Une fois votre compte créé, vous vous verrez attribuer 100
    jetons en guise de cadeau de bienvenue.

    <?php echo $this->Html->link('Vous possédez déjà un compte ?',
        array('controller' => 'parieurs', 'action' => 'connexion')); ?>
</p>

<div class="users form">
    <?php echo $this->Form->create('Parieur', array(
        'inputDefaults' => array(
            'div' => 'form-group',
            'wrapInput' => false,
            'class' => 'form-control'
        ))); ?>
    <fieldset>
        <?php
        echo $this->Form->input('pseudo', array('label'=>'Pseudo:', 'autoCapitalize' => 'off'));
        echo $this->Form->input('mot_passe', array('type' => 'password', 'label'=>'Mot de passe:'));
        echo $this->Form->input('mot_passe_confirmation', array('label'=>'Confirmation du mot de passe:', 'type' =>'password'));
        echo $this->Form->input('courriel', array('type' => 'email', 'label'=>'Adresse courriel:'));

        echo $this->Form->submit('Soumettre', array(
            'div' => false,
            'class' => 'btn btn-primary'
        ));
        ?>
    </fieldset>
    <?php echo $this->Form->end(); ?>
</div>