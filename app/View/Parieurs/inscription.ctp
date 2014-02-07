<!-- app/View/Users/add.ctp -->
<div class="users form">
    <?php echo $this->Form->create('Parieur'); ?>
    <fieldset>
        <legend><?php echo __('Inscription'); ?></legend>
        <?php
        echo $this->Form->input('pseudo');
        echo $this->Form->input('mot_passe', array('type' => 'password'));
        echo $this->Form->input('courriel', array('type' => 'email'));
        ?>
    </fieldset>
    <?php echo $this->Form->end(__('Inscription')); ?>
</div>