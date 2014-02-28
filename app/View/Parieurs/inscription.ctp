<!-- app/View/Users/add.ctp -->
<div class="users form">
    <?php echo $this->Form->create('Parieur', array('class'=>'well')); ?>
    <fieldset>
        <legend><?php echo __('Inscription'); ?></legend>
        <?php
        echo $this->Form->input('pseudo', array('label'=>'Pseudo:'));
        echo $this->Form->input('mot_passe', array('type' => 'password', 'label'=>'Mot de passe:', 'required'=>'required'));
        echo $this->Form->input('courriel', array('type' => 'email', 'label'=>'Adresse courriel:'));

        echo $this->Form->submit('Soumettre', array(
            'div' => false,
            'class' => 'btn btn-primary'
        ));
        ?>
    </fieldset>
    <?php echo $this->Form->end(); ?>
</div>