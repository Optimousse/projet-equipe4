<div class="users form">
    <?php echo $this->Form->create('Parieur', array('class'=>'well')); ?>
    <fieldset>
        <legend>
            <?php echo __('Connexion'); ?>
        </legend>
        <?php
        echo $this->Form->input('pseudo', array('label' => 'Pseudo:'));
        echo $this->Form->input('mot_passe', array('type'=> 'password','label'=>'Mot de passe:'));

        echo $this->Form->submit('Soumettre', array(
            'div' => false,
            'class' => 'btn btn-primary'
        ));
        ?>
    </fieldset>
    <?php echo $this->Form->end(); ?>
</div>