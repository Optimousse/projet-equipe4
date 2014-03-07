<script type="text/javascript">

    $(document).ready(function(){

        var maxHeight = 0;
        $("div.thumbnail").each(function(){
            if ($(this).height() > maxHeight) { maxHeight = $(this).height(); }
        });
        $("div.thumbnail").height(maxHeight + 10);
    });
</script>


<h1>Les lots</h1>
<!-- Affiche tous les lots -->
<div>
    <label>Trier par:</label>

    <div class="clearfix"></div>
    <ul class="pagination" style="margin-top: 0;">
        <li><?php echo $this->Paginator->sort('nom'); ?></li>
        <li><?php echo $this->Paginator->sort('prix'); ?></li>
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
    foreach ($lots as $lot){?>
        <div class="col-md-4">
            <div class="thumbnail" >
                <div style="max-height:100px; overflow:hidden; ">
                    <img style="width:100%; " src="<?php echo $lot['Lot']['image']; ?>"/>
                </div>

                <div class="caption">
                    <h3>
                        <?php echo $lot['Lot']['nom']; ?>
                    </h3>
                    <p>
                        <?php
                        $desc = $lot['Lot']['description'];

                        $prix = $lot['Lot']['prix'];
                        if(strlen($desc) > 75) {
                            echo substr($desc, 0, 75) . '[...]';
                        } else {
                            echo $desc;
                        }
                        echo '<br>';
                        echo 'prix :<strong> '.$prix.'</strong> jetons';

                        ?>
                    </p>
                    <?php
                    $nomLien = 'Acheter';
                    echo $this->Html->link($nomLien, array('controller' => 'achats', 'action' => 'informations', $lot['Lot']['id']), array('class' => 'btn btn-primary')); ?>
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

