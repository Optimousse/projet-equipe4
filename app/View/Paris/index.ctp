<h1>Tous les paris</h1>
<!-- Affiche tous les paris -->
<div>
    <label>Trier par:</label>
    <div class="clearfix"></div>
    <ul class="pagination">
        <li><?php echo $this->Paginator->sort('nom'); ?></li>
        <li><?php echo $this->Paginator->sort('date_fin', 'Date de fin'); ?></li>
    </ul>
</div>
<div class="table-responsive">
<table class="table table-striped table-hover ">
    <tr>
        <th></th>
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
            <td>
                <?php
                if(date("Y-m-d") < $pari['Pari']['date_fin'])
                    echo $pari['Pari']['date_fin'];
                else
                    echo 'Pari terminé';
                ?>
            </td>
            <td>
                <?php
                $nomLien = 'Consulter';
                if(date("Y-m-d") < $pari['Pari']['date_fin'])
                    $nomLien = 'Miser';
                echo $this->Html->link($nomLien, array('controller' => 'parieurs_paris', 'action' => 'miser', $pari['Pari']['id'])); ?>
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
</div>
<?php
echo $this->Paginator->pagination(array(
    'ul' => 'pagination'
)); ?>

