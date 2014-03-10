<!-- app/View/Users/add.ctp -->
<h1>Vos coordonnées</h1>

<p>
   Veuillez saisir vos coordonnées pour que l'on puisse expédier votre cadeau
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

        echo $this->Html->link('Retour au catalogue', array('controller' => 'paris', 'action' => 'index'), array('class' => 'btn btn-default btn-separation')); ?>
    </fieldset>
    <?php echo $this->Form->end(); ?>
</div>
