
<!-- Affiche tous les paris -->
<table class="table">
    <tr>
        <th>Nom</th>
        <th>Description</th>
        <th>Cote</th>
        <th>Image</th>
        <th>Date de fin</th>
        <th></th>
    </tr>

    <?php foreach ($paris as $pari): ?>
        <tr>
            <td><?php echo $pari['Pari']['nom']; ?></td>
            <td><?php echo $pari['Pari']['description']; ?></td>
            <td><?php echo $pari['Pari']['cote']; ?></td>
            <td><?php echo $pari['Pari']['image']; ?></td>
            <td><?php echo $pari['Pari']['date_fin']; ?></td>
            <td>
                <?php echo $this->Html->link('Miser',
                    array('controller' => 'parieurspari', 'action' => 'miser', $pari['Pari']['id'])); ?>
                |
                <?php echo $this->Html->link('Consulter',
                    array('action' => 'consulter', $pari['Pari']['id'])); ?>
            </td>
        </tr>
    <?php endforeach;
    if(empty($paris))
    {
    ?>
        <tr>
            <td colspan="6">Aucun pari</td>
        </tr>
    <?php
    }

    unset($pari); ?>
</table>

<?php echo $this->Html->link('Ajouter un pari',
    array('controller' => 'paris', 'action' => 'ajouter')); ?>