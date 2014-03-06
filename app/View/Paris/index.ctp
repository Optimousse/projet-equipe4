<script type="text/javascript">

    $(document).ready(function(){
        var max = 0, jThumbnails = $("div.thumbnail");
        jThumbnails .each(function(index, elt){
            max = Math.max(max, $(elt).height());
        });
        jThumbnails.css('height', max);
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
            <div style="max-height:100px; overflow:hidden; ">
                <img style="width:100%; " src="<?php echo $pari['Pari']['image']; ?>"/>
            </div>

            <div class="caption">
                <h3>
                    <?php echo $pari['Pari']['nom']; ?>
                </h3>
                <p>
                    <?php
                    $desc = $pari['Pari']['description'];
                    if(strlen($desc) > 75)
                        echo substr($desc, 0, 75) . '[...]';
                    else
                        echo $desc;
                    ?>
                </p>
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

