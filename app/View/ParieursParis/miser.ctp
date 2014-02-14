<!-- Fichier : /app/View/Posts/view.ctp -->


<div style="max-width: 50%;">
    <h3><?php echo $paris['Pari']['nom']; ?></h3>
    <img src=<?php echo $paris['Pari']['image']; ?> alt="img"/>

    <p><?php echo $paris['Pari']['description']; ?></p>


    <p>Ce pari se termine le <?php echo $paris['Pari']['date_fin']; ?></p>

    <?php foreach ($choix as $choi):?>

        <h5>
            <?php echo $choi['Choix']['nom']; ?>
        </h5>
        <p>
            Cote: <?php echo $choi['Choix']['cote']; ?>
        </p>

    <?php endforeach ;

    echo $this->Form->create('ParieursPari', array('class'=>'well')); ?>
    <fieldset>
        <legend>Faites votre mise !</legend>
        <?php
        echo $this->Form->input('choix_id',
            array('options' => $options, 'type' => 'radio', 'required'=>'required','legend'=>false));

        echo $this->Form->input('mise',
            array('label'=>'Mise:', 'type'=>'number'));

        echo $this->Form->input('pari_id', array('type' => 'hidden', 'value' => $paris['Pari']['id']));
        echo $this->Form->input('parieur_id', array('type' => 'hidden', 'value' => $id_util));

        echo $this->Form->submit('Miser', array(
            'div' => false,
            'class' => 'btn btn-primary'
        ));

        ?>
    </fieldset>
    <?php echo $this->Form->end(); ?>

</div>