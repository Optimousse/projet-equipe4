<h1>Mes mises</h1>

<!-- Affiche tous les mises  -->
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
        <th>Mon choix</th>
        <th>Ma mise</th>
    </tr>

    <?php foreach ($mises as $mise): ?>
        <tr>
            <td><img src="<?php echo $mise['Pari']['image']; ?>" class="img-rounded" style="max-width: 150px;" /></td>
            <td><?php echo $this->Html->link($mise['Pari']['nom'], array('controller' => 'parieurs_paris', 'action' => 'miser', $mise['Pari']['id']));?></td>

            <td><?php echo $mise['Choix']['nom']; ?></td>
            <td><?php echo $mise['ParieursPari']['mise']; ?></td>
        </tr>
    <?php endforeach;
    if(empty($mises))
    {
        ?>
        <tr>
            <td colspan="6">Vous n'avez fait aucune mise</td>
        </tr>
    <?php
    }

    unset($mise); ?>
</table>
<?php
echo $this->Paginator->pagination(array(
    'ul' => 'pagination'
)); ?>