<!-- Fichier : /app/View/Posts/view.ctp -->


<p>Intitulé : <?php echo $paris['Pari']['nom']; ?></p>
<p>Description : <?php echo $paris['Pari']['description']; ?></p>
<p>Cote : <?php echo $paris['Pari']['cote']; ?></p>
<p><img src=<?php echo $paris['Pari']['image']; ?> alt = "test"></p>
<p>Date de fin de pari : <?php echo $paris['Pari']['date_fin']; ?></p>

<p>
    <?php echo $this->Html->link('Miser sur ce pari',
        array('action' => 'miser')); ?>
</p>