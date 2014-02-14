<h1>Tous les paris</h1>
<!-- Affiche tous les paris -->
<table class="table table-striped">
    <tr>
        <th>Image</th>
        <th>Nom</th>
        <th>Description</th>
        <th>Se termine le</th>
        <th></th>
    </tr>

    <?php foreach ($paris as $pari): ?>
        <tr>
            <td><img src="<?php echo $pari['Pari']['image']; ?>" class="img-rounded" style="max-width: 150px;" /></td>
            <td><?php echo $pari['Pari']['nom']; ?></td>
            <td style="max-width: 200px;"><?php echo $pari['Pari']['description']; ?></td>
            <td><?php echo $pari['Pari']['date_fin']; ?></td>
            <td>
                <?php echo $this->Html->link('Miser',
                    array('controller' => 'parieurs_paris', 'action' => 'miser', $pari['Pari']['id'])); ?>
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