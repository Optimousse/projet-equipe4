
<!-- Affiche tous les paris -->
<table class="table table-striped">
    <tr>
        <th>Nom</th>
        <th>Description</th>
        <th>Statut</th>
        <th></th>
    </tr>

    <?php foreach ($paris as $pari): ?>
        <tr>
            <td><?php echo $pari['Pari']['nom']; ?></td>
            <td><?php echo $pari['Pari']['description']; ?></td>
            <td>
                <?php
                if(date("Y-m-d") < $pari['Pari']['date_fin'])
                    echo 'Pari en cours';
                else
                    echo $this->Html->link('Pari terminé. Déterminez le choix gagnant.',
                        array('controller' => 'paris', 'action' => 'ajouter'));
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

    unset($pari); ?>
</table>

<?php echo $this->Html->link('Ajouter un pari',
    array('controller' => 'paris', 'action' => 'ajouter')); ?>