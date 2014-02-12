<!-- Fichier : /app/View/Posts/view.ctp -->


<?php echo $this->Form->create('ParieursPari'); ?>
<fieldset>
    <legend><?php echo __('Créer une mise pour <i>' .$pari['Pari']['nom'] .'</i>'); ?></legend>


    Cote : <?php echo $pari['Pari']['cote']; ?>

    <?php echo $this->Form->input('mise', array(
        'label' => 'Mise:'));

    echo $this->Form->input('pari_id', array('type' => 'hidden', 'value' => $pari['Pari']['id']));
    echo $this->Form->input('parieur_id', array('type' => 'hidden', 'value' => $id_util));

    echo $this->Form->submit('Miser', array(
        'div' => false,
        'class' => 'btn'
    ));

    ?>
</fieldset>
<?php echo $this->Form->end(); ?>