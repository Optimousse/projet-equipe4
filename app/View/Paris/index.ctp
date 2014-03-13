<script type="text/javascript">

    $(document).ready(function(){

        maxHeight = 0;
        $("div.description").each(function(){
            if ($(this).height() > maxHeight) { maxHeight = $(this).height(); }
        });
        $("div.description").height(maxHeight + 10);
    });
</script>


<h1>Offres de paris</h1>
<!-- Affiche tous les paris -->
<div>
    <label>Trier par:</label>

    <div class="clearfix"></div>
    <ul class="pagination" style="margin-top: 0;">
        <li><?php echo $this->Paginator->sort('nom'); ?></li>
        <li><?php echo $this->Paginator->sort('date_fin', 'Date de fin'); ?></li>
    </ul>
    <br/>
    <?php
    echo $this->Paginator->pagination(array(
        'ul' => 'pagination'
    )); ?>
</div>

<div class="row">

    <?php
    $compteur = 0;
    foreach ($paris as $pari){?>
        <div class="col-md-4">
        <div class="thumbnail" >
            <div style="height:150px; overflow:hidden; ">
                <?php echo $this->Html->image($pari['Pari']['image'], array(
                    "alt" => $pari['Pari']['image'],
                    'style' => 'height:150px; width:100%',
                    'url' => array('controller' => 'parieurs_paris', 'action' => 'miser', $pari['Pari']['id'])
                ));
                ?>
            </div>

            <div class="caption">
                <div class="description">
                    <h3 class="text-center">
                        <?php echo $pari['Pari']['nom']; ?>
                    </h3>
                    <p style="color: #595959;">
                        <?php
                        $desc = $pari['Pari']['description'];
                        if(strlen($desc) > 150)
                            echo substr($desc, 0, 150) . '[...]';
                        else
                            echo $desc;
                        ?>
                    </p>
                </div>
                <p class="text-center">
                    <?php
                $nomLien = 'Consulter';
                    if(date("Y-m-d") < $pari['Pari']['date_fin'])
                    $nomLien = 'Miser';
                echo $this->Html->link($nomLien, array('controller' => 'parieurs_paris', 'action' => 'miser', $pari['Pari']['id']), array('class' => 'btn btn-primary')); ?>
            </div>
        </div>
    </div>

    <?php
    $compteur++;
        if($compteur == 3){
            echo '<div class="clearfix"></div>';
        }
    }?>
</div>

<?php
echo $this->Paginator->pagination(array(
    'ul' => 'pagination'
)); ?>

