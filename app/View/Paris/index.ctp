<script type="text/javascript">

    $(document).ready(function(){

        maxHeight = 0;
        $("div.description").each(function(){
            if ($(this).height() > maxHeight) { maxHeight = $(this).height(); }
        });
        $("div.description").height(maxHeight + 10);

        $("#txtMotCle").tooltip();
    });
</script>


<h1>Offres de paris</h1>

<div class="panel-group" id="accordion">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                    Critères de recherche
                    <?php
                        if(isset($critereActuel)){
                            echo '<small>Actuellement: '.$critereActuel.'</small>';
                        }
                    ?>
                </a>
            </h4>
        </div>
        <div id="collapseOne" class="panel-collapse collapse">
            <div class="panel-body padding-small">

                <?php
                echo $this->Form->create('Pari', array('type' => 'get'));
                ?>

                <div class="form-inline">

                    <label>Trier par:</label>

                    <div class="clearfix"></div>
                    <ul class="pagination">
                        <li><?php echo $this->Paginator->sort('nom'); ?></li>
                        <li><?php echo $this->Paginator->sort('date_fin', 'Date de fin'); ?></li>
                    </ul>
                </div>
                <div class="form-inline">
                    <div class="form-group">
                        <label class="sr-only" for="exampleInputEmail2">Nom</label>
                        <?php
                        if(!isset($critereActuel))
                            $critereActuel = "";
                        echo $this->Form->input('nom', array(
                            'type' => 'search',
                            'class' => 'form-control',
                            'value'=> $critereActuel,
                            'label' => false,
                            'div' => false,
                            'placeholder' => 'Mot-clé',
                            'id' => 'txtMotCle',
                            'data-toggle'=>"tooltip",
                            'data-placement'=>"top",
                            'title'=>"Un mot faisant partie du titre et/ou de la description d'un pari"));
                        ?>
                    </div>
                    <?php
                    echo $this->Form->submit('Rechercher', array(
                        'div' => false,
                        'class' => 'btn btn-primary'
                    ));?>

                </div>

                <?php

                echo $this->Html->link('Réinitialiser la recherche', array('controller' => 'paris', 'action' => 'index'));
                echo $this->Form->end();
                ?>
            </div>
        </div>
    </div>
</div>


<br/>
<?php
echo $this->Paginator->pagination(array(
    'ul' => 'pagination'
));
?>
<br/>
<div class="row">

    <?php
    $compteur = 0;
    foreach ($paris as $pari){?>
        <div class="col-md-4">
        <div class="thumbnail" >
            <div style="height:150px; overflow:hidden; ">
                <?php echo $this->Html->image($pari['Pari']['image'], array(
                    "alt" => $pari['Pari']['image'],
                    'style' => 'width:100%',
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

