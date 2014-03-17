<!-- app/View/Users/add.ctp -->
<h1>Acheter un lot</h1>

<!-- infos sur le paris -->
 <div class="row">
<div class="col-md-4">
    <div class="thumbnail" >
        <?php

        echo '<strong>'.$lot['nom'].'</strong><br/>';
        echo $lot['description'];
        echo '<br/>Prix : <strong>'.$lot['prix'].'</strong> jetons<br/><br/>'; ?>

        <div style="height:150px; overflow:hidden; ">
            <?php
            echo $this->Html->image($lot['image'], array(
                "alt" => $lot['image'],
                'class' => 'width-100'
            ));
            ?>
        </div>
    </div>
</div>
<p>
   Veuillez saisir vos coordonnées afin que nous puissions vous expédier votre achat.
</p>

<div class="users form">
    <?php echo $this->Form->create('Achat', array(
        'inputDefaults' => array(
            'div' => 'form-group',
            'wrapInput' => false,
            'class' => 'form-control'
        ))); ?>
    <fieldset>
        <?php
        echo $this->Form->input('adresse', array('label'=>'Adresse :', 'autoCapitalize' => 'off'));
        echo $this->Form->input('code_postal', array('type' => 'numeric', 'label'=>'Code postal :'));
        echo $this->Form->input('ville', array('type' => 'text', 'label'=>'Ville :'));
        echo $this->Form->input('parieur_id', array('type' => 'hidden', 'value' => $id_util));
        echo $this->Form->input('lot_id', array('type' => 'hidden', 'value' => $id_lot));

        echo $this->Form->submit('Soumettre', array(
            'div' => false,
            'class' => 'btn btn-primary'

        ));

        echo $this->Html->link('Annuler', array('controller' => 'lots', 'action' => 'index'), array('class' => 'btn btn-default btn-separation')); ?>
    </fieldset>
    <?php echo $this->Form->end(); ?>
</div>
