<script type="text/javascript">
    $(document).ready(function(){

        $("#txtAmitieExiste").css('display', 'none');
        $("#btnAjouterAmis").click(function(e){
            var urlAjouter = '<?php echo $this->Html->url(array(
                "controller" => "amis",
                "action" => "ajouter",
                $parieur['Parieur']['id']
            )); ?>';
            var id_destinataire = '<?php echo $parieur['Parieur']['id']; ?>';
            $.ajax({
                type: "POST",
                url: urlAjouter,
                dataType: "json",
                success: function (data) {
                    if (data.error_code === 0) {

                        $("#btnAjouterAmis").css('display', 'none');
                        $("#txtAmitieExiste").css('display', 'block');
                        $("#txtAmitieExiste").append('La demande d\'amitié a bien été envoyée.');
                    }
                }
            });
            e.preventDefault();
        });
    });
</script>

<div>
    <h1>
        Profil de <?php echo $parieur['Parieur']['pseudo']; ?>
    </h1>

    <div class="row padding-small">
        <div class="col-xs-12 col-md-4">
            <?php echo $this->Html->image('avatars/'.$parieur['Parieur']['avatar'], array('class'=>'img-rounded width-80')); ?>
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
                            echo $nbParisCrees;
                            if($nbParisCrees > 0){
                                echo '&nbsp;';
                                echo $this->Html->link('Voir les paris', array('controller' => 'paris', 'action' => 'index','?' => array('createur' => '1', 'motCle' => $parieur['Parieur']['pseudo']) ));
                            }
                        ?>
                        <div class="clearfix"></div>
                        <blockquote class="blockquote-info" id="txtAmitieExiste"></blockquote>
                        <?php
                        if(AuthComponent::user() && $parieur['Parieur']['id'] != AuthComponent::user('id')){

                            if(isset($amitie['Ami'])){
                            ?>
                                <blockquote class="blockquote-info">
                                    <?php
                                    if($amitie['Ami']['amitie_acceptee'] == 1){
                                        echo 'Vous êtes déjà ami avec '.$parieur['Parieur']['pseudo'].'.';
                                    }
                                    else if($amitie['Ami']['destinateur_id'] == $parieur['Parieur']['id']){
                                        echo $parieur['Parieur']['pseudo'].' vous a envoyé une demande d\'amitié
                                        que vous n\'avez pas encore acceptée.';
                                    }
                                    else{
                                        echo 'Vous avez déjà envoyé une demande d\'amitié à '.$parieur['Parieur']['pseudo'].'.';
                                    }
                                    ?>
                                </blockquote>
                            <?php
                            }
                            else{
                                echo $this->Html->link('Faire une demande d\'amitié', array('controller' => 'amis', 'action' => 'ajouter', $parieur['Parieur']['id']), array('id' => 'btnAjouterAmis', 'class' => 'btn btn-primary'));
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php echo $this->Html->link('Retour à la recherche', array('controller' => 'parieurs', 'action' => 'rechercher'), array('class' => 'btn btn-default')); ?>