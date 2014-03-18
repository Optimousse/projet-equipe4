<h1>Mes paris</h1>

<!-- Affiche tous les paris -->

<div>
    <label>Trier par:</label>
    <div class="clearfix"></div>
    <ul class="pagination">
        <li><?php echo $this->Paginator->sort('nom'); ?></li>
        <li><?php echo $this->Paginator->sort('date_fin', 'Date de fin'); ?></li>
    </ul>
</div>

<table class="table table-striped table-hover table-responsive">
    <tr>
        <th></th>
        <th>Nom</th>
        <th>Description</th>
        <th>Statut</th>
        <th></th>
    </tr>

    <?php foreach ($paris as $pari): ?>
        <tr>
            <td><?php echo $this->Html->image('uploads/thumbs/'.$pari['Pari']['image'], array('class'=>'img-rounded', 'style'=>'max-width:150px')); ?></td>
            <td><?php echo $pari['Pari']['nom']; ?></td>
            <td><?php echo $pari['Pari']['description']; ?></td>
            <td>
                <?php
                if(date("Y-m-d") < $pari['Pari']['date_fin'])
                    echo 'Pari en cours.';
                else{
                    echo 'Pari terminé.';
                    if(!isset($pari['Pari']['choix_gagnant']))
                        echo $this->Html->link(' Déterminez le choix gagnant.',
                            array('controller' => 'paris', 'action' => 'determiner_gagnant', $pari['Pari']['id']));
                }
                ?>
            </td>
        </tr>
    <?php endforeach;
    if(empty($paris))
    {
        ?>
        <tr>
            <td colspan="6">Vous n'avez créé aucun pari</td>
        </tr>
    <?php
    }

    unset($pari);
    ?>
</table>
<?php
echo $this->Paginator->pagination(array(
    'ul' => 'pagination'
));

echo $this->Html->link('Retour au catalogue', array('controller' => 'paris', 'action' => 'index'), array('class' => 'btn btn-default btn-separation'));
?>


