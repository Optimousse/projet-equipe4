<!-- Fichier : /app/View/Posts/view.ctp -->

<?php
echo $this->Session->flash(); ?>

<div class="row">

    <div class="col-md-4">
        <h4>Nom</h4>
        <p><?php echo $paris['Pari']['nom']; ?></p>
    </div>
    <div class="col-md-4">
        <h4>Description</h4>
        <p><?php echo $paris['Pari']['description']; ?></p>
    </div>

</div>

<div class="row">

    <div class="col-md-6">
        <h4>Cote</h4>
        <p><?php echo $paris['Pari']['cote']; ?></p>
    </div>
    <div class="col-md-6">
        <h4>Se termine le</h4>
        <p><?php echo $paris['Pari']['date_fin']; ?></p>
    </div>

</div>

<div class="row">
    <img src=<?php echo $paris['Pari']['image']; ?> alt="img" />
</div>


<p>
    <?php echo $this->Html->link('Miser sur ce pari',
        array('controller' => 'parieurs_paris', 'action' => 'miser', $paris['Pari']['id'])); ?>
</p>