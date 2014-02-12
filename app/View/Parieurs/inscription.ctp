<!-- app/View/Users/add.ctp -->
<div class="users form">
    <?php echo $this->Form->create('Parieur'); ?>
    <fieldset>
        <legend><?php echo __('Inscription'); ?></legend>
        <?php
        echo $this->Form->input('pseudo');
        echo $this->Form->input('mot_passe', array('type' => 'password'));
        echo $this->Form->input('courriel', array('type' => 'email'));

        echo $this->Form->submit('Soumettre', array(
            'div' => false,
            'class' => 'btn'
        ));
        ?>
    </fieldset>
    <?php echo $this->Form->end(); ?>
</div>