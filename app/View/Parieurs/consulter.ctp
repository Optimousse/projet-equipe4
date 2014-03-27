<!-- Fichier : /app/View/Posts/view.ctp -->
<script>
    $(document).ready(function(){
        $("#txtMise").tooltip();
    });
</script>

<div>
    <h1>
        <?php echo $parieur['Parieur']['pseudo']; ?>
    </h1>

    <div class="clearfix"></div>
    <?php echo $this->Facebook->like(array(
        "data-action"=>"like",
        "data-show-faces"=>true,
        "data-share"=>true,
        "id" => 'btnFacebook'
    )); ?>
    <div class="clearfix"></div>
    <div class="row padding-small">
        <div class="col-xs-12 col-md-4">
            <?php echo $this->Html->image('avatars/'.$parieur['Parieur']['avatar'], array('class'=>'img-rounded width-100')); ?>
        </div>
        <div class="col-xs-6 col-md-8">
            <div class="panel panel-default">
                <div class="panel-body">
                    <span class="btn btn-danger width-100">Détails</span>
                    <div class="padding-medium">
                        <label>Pseudo:</label>
                        <?php echo $parieur['Parieur']['pseudo'];?>
                        <div class="clearfix"></div>
                        <label>Compte créé le:</label>
                        <?php echo date('Y-m-d',strtotime($parieur['Parieur']['created']));?>
                        <div class="clearfix"></div>
                        <label>Nombre de paris créés:</label>
                        <?php
                        //TODO nombre de paris créés
                            echo '5';
                        echo '&nbsp;';
                        //TODO lien vers le catalogue
                            echo $this->Html->link('Voir les paris', array());
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>