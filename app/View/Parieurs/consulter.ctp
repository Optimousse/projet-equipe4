
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
                        <?php
                        if(AuthComponent::user() && $parieur['Parieur']['id'] != AuthComponent::user('id') && $amitieExiste == false){
                        //todo ajax
                            echo $this->Html->link('Faire une demande d\'amitié', array('controller' => 'amis', 'action' => 'ajouter', $parieur['Parieur']['id']), array('class' => 'btn btn-primary'));
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php echo $this->Html->link('Retour à la recherche', array('controller' => 'parieurs', 'action' => 'rechercher'), array('class' => 'btn btn-default')); ?>