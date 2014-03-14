<script type="text/javascript">

    $(document).ready(function(){

        maxHeight = 0;
        $("div.description").each(function(){
            if ($(this).height() > maxHeight) { maxHeight = $(this).height(); }
        });
        $("div.description").height(maxHeight + 10);

        $("#txtMotCle").tooltip();
        jQuery.noConflict();
        $("#aCriteresRecherche").click(function(e){
            if($('#spanCriteresRecherche').attr('class').contains("down")){
            $("#spanCriteresRecherche").removeClass("glyphicon-chevron-down");
            $("#spanCriteresRecherche").addClass("glyphicon-chevron-up");
            }
            else
            {

                $("#spanCriteresRecherche").removeClass("glyphicon-chevron-up");
                $("#spanCriteresRecherche").addClass("glyphicon-chevron-down");
            }
        });
    });
</script>


<h1>Offres de paris</h1>

<div class="panel-group" id="accordion">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" id="aCriteresRecherche">
                    <span id="spanCriteresRecherche" class="glyphicon glyphicon-chevron-down">&nbsp;</span>Critères de recherche
                </a>
            </h4>
        </div>
        <div id="collapseOne" class="panel-collapse collapse">
            <div class="panel-body padding-small">

                <?php
                echo $this->Form->create('Pari', array('type' => 'get'));
                if(!isset($estRechercheParDescription))
                    $estRechercheParDescription = array();

                if(!isset($estRechercheParNom))
                    $estRechercheParNom = array();

                if(!isset($estRechercheEnCours))
                    $estRechercheEnCours = array();

                if(!isset($estRechercheTermine))
                    $estRechercheTermine = array();
                ?>

                <div class="form-inline">
                    <label>Type de pari:</label>
                    <div class="clearfix"></div>
                    <div class="form-group">
                        <?php echo $this->Form->input('enCours', array(
                            'label' => 'En cours',
                            'class' => false,
                            'type' => 'checkbox',
                            $estRechercheEnCours,
                            'hiddenField' => false
                        )); ?>
                    </div>
                    <div class="form-group">
                        <?php echo $this->Form->input('termine', array(
                            'label' => 'Terminé',
                            'class' => false,
                            'type' => 'checkbox',
                            $estRechercheTermine,
                            'hiddenField' => false
                        )); ?>
                    </div>
                    <div class="clearfix"></div>
                    <br/>

                    <label>Champs de recherche:</label>
                    <div class="clearfix"></div>
                    <div class="form-group">
                        <?php echo $this->Form->input('nom', array(
                            'label' => 'Nom',
                            'class' => false,
                            'type' => 'checkbox',
                            $estRechercheParNom,
                            'hiddenField' => false
                        )); ?>
                    </div>
                    <div class="form-group">
                        <?php echo $this->Form->input('description', array(
                            'label' => 'Description',
                            'class' => false,
                            'type' => 'checkbox',
                            $estRechercheParDescription,
                            'hiddenField' => false
                        )); ?>
                    </div>
                    <div class="clearfix"></div>
                    <br/>

                    <label for="txtMotCle" class="form-control-static">Mot-clé: </label>
                    <div class="clearfix"></div>

                    <div class="form-group">
                        <?php
                        if(!isset($critereActuel))
                            $critereActuel = "";
                        echo $this->Form->input('motCle', array(
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
                    <div class="clearfix"></div>
                    <br/>
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

if(!empty($paris)){
?>

    <div class="form-inline">

            <label>Trier selon:</label>
            <div class="clear-fix"></div>
            <ul class="pagination">
                <li><?php echo $this->Paginator->sort('nom'); ?></li>
                <li><?php echo $this->Paginator->sort('date_fin', 'Date de fin'); ?></li>
            </ul>
            <?php
            echo $this->Paginator->pagination(array(
            'ul' => 'pagination'
            ));
            ?>

    </div>
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
    ));
}
else{
    ?>
    <blockquote class="blockquote-info">
        Aucun pari ne correspond à ces critères.
    </blockquote>
    <?php
}

?>


