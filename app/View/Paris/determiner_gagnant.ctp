<!-- Fichier : /app/View/Posts/view.ctp -->


    <h3><?php echo $paris['Pari']['nom']; ?></h3>

<?php
    echo $this->Form->create('Pari', array('class'=>'well')); ?>
    <fieldset>
        <legend>Déterminer le choix gagnant</legend>
        <?php
        echo $this->Form->input('choix_gagnant',
            array('options' => $options, 'type' => 'radio', 'required'=>'required','legend'=>false));

        echo $this->Form->submit('Soumettre', array(
            'div' => false,
            'class' => 'btn btn-primary'
        ));

        ?>
    </fieldset>
    <?php echo $this->Form->end(); ?>