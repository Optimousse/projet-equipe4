<div class="users form">
    <?php echo $this->Form->create('Pari'); ?>
    <fieldset>
        <legend><?php echo __('Ajouter un pari'); ?></legend>
        <?php
        echo $this->Form->input('nom', array(
            'label' => 'Nom:'));
        echo $this->Form->input('description', array(
                'label' => 'Description:',
                'type' => 'textarea')
        );
        echo $this->Form->input('cote', array(
            'label' => 'Cote:',
            'type' => 'number'
        ));
        echo $this->Form->input('image', array(
            'label' => 'Image:'));
        echo $this->Form->input('date_fin', array(
            'label' => 'Date de fin:',
            'type' => 'date'
            ));
        ?>

        <h4>Choix possibles</h4>
        <p>Note: Deux choix sont obligatoires. Le troisième est facultatif.</p>
        <ol>
            <li>
                <?php echo $this->Form->input('Choix.0.nom', array('label'=>false, 'after'=>' *')); ?>
            </li>
            <li>
                <?php echo $this->Form->input('Choix.1.nom', array('label'=>false, 'after'=>' *')); ?>
            </li>
            <li>
                <?php echo $this->Form->input('Choix.2.nom', array('label'=>false)); ?>
            </li>
        </ol>

        <?php
        echo $this->Form->submit('Soumettre', array(
            'div' => false,
            'class' => 'btn'
        ));?>
    </fieldset>
    <?php echo $this->Form->end(); ?>
</div>