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

<h1>Rechercher un usager</h1>

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
                echo $this->Form->create('Parieur', array(
                    'inputDefaults' => array(
                        'div' => 'form-group',
                        'wrapInput' => false,
                        'class' => 'form-control'
                    )));

                echo $this->Form->input('pseudo', array(
                    'type' => 'search',
                    'class' => 'form-control',
                    'label' => 'Pseudo:',
                    'div' => false,
                    'placeholder' => 'Pseudo de l\'usager',
                    'id' => 'txtMotCle',
                    'data-toggle'=>"tooltip",
                    'data-placement'=>"top",
                    'title'=>"Une chaîne de lettres faisant partie du pseudo d'un usager"));

                ?>
                <div class="clearfix"></div>
                <br>
                <?php
                echo $this->Form->submit('Rechercher', array(
                    'div' => false,
                    'class' => 'btn btn-primary'
                ));
                ?>
                <div class="clearfix"></div>
                <?php
                echo $this->Html->link('Réinitialiser la recherche', array('controller' => 'parieurs', 'action' => 'rechercher'));
                echo $this->Form->end();
                ?>
            </div>
        </div>
    </div>
</div>
<div class="clearfix"></div>
<br/>

<div class="form-inline">

    <label>Trier selon:</label>
    <div class="clear-fix"></div>
    <ul class="pagination">
        <li><?php echo $this->Paginator->sort('pseudo', 'Pseudo'); ?></li>
    </ul>
    <?php
    echo $this->Paginator->pagination(array(
        'ul' => 'pagination'
    ));
    ?>

</div>

<div class="row">
    <?php
    $compteur = 0;
    foreach ($parieurs as $parieur){
    ?>
    <div class="col-md-2">
        <div class="thumbnail">
            <div class="thumbnail-div-medium">
                <?php echo $this->Html->image('avatars/' . $parieur['Parieur']['avatar'], array(
                    "alt" => $parieur['Parieur']['avatar'],
                    'class' => 'width-100',
                    'url' => array('controller' => 'parieurs', 'action' => 'consulter', $parieur['Parieur']['id'])
                ));
                ?>
            </div>

            <div class="caption">
                <div class="description">
                    <p class="text-center">
                        <?php echo $parieur['Parieur']['pseudo']; ?>
                    </p>
                </div>
            </div>
        </div>
    </div>


        <?php
        $compteur++;
        if ($compteur == 6) {
            echo '<div class="clearfix"></div>';
        }
    }?>
</div>
