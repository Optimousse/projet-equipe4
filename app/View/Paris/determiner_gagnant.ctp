<!-- Fichier : /app/View/Posts/view.ctp -->


    <h1><?php echo $paris['Pari']['nom']; ?>
    <small>Déterminez le choix gagnant.</small>
    </h1>

<?php
    echo $this->Form->create('Pari', array(
        'inputDefaults' => array(
    'div' => 'form-group',
    'wrapInput' => false,
    'class' => 'form-control'
))); ?>
    <fieldset>
        <?php

        $attributes = array('legend' => false, 'separator' => '<br/>', 'required' => 'required');
        echo $this->Form->radio('choix_gagnant', $options, $attributes);

        echo $this->Form->submit('Soumettre', array(
            'class' => 'btn btn-primary'
        ));

        ?>
    </fieldset>
    <?php echo $this->Form->end(); ?>