<!-- Fichier : /app/View/Posts/view.ctp -->
<script>
    $(document).ready(function(){
       $("#txtMise").tooltip();
    });
</script>

<div class="well">
    <h1>
        <?php echo $paris['Pari']['nom'];
            //Affiche le nombre de jours avant la fin du pari
            if (date("Y-m-d") < $paris['Pari']['date_fin']) {
                ?>
            <small>
                <?php
                    $aujourdhui = strtotime(date("Y-m-d"));
                    $dateFin = strtotime($paris['Pari']['date_fin']);
                    $jours = round(($dateFin - $aujourdhui) / 86400);
                    $accordJour = 'jour';
                    if($jours > 1)
                        $accordJour = $accordJour . 's';
                    echo 'Ce pari se termine dans ' . '<h2 style="display:inline-block; color:#2D6CA2;"><abbr title=' . $paris['Pari']['date_fin'] .'>' . $jours . '</abbr></h2> ' . $accordJour;
                ?>
            </small>
        <?php
            }
        ?>
    </h1>

    <div class="clearfix"></div>
    <?php echo $this->Facebook->like(array(
        "data-action"=>"like",
        "data-show-faces"=>true,
        "data-share"=>true,
        "id" => 'btnFacebook'
    )); ?>
    <div class="row">
        <div class="col-xs-12 col-md-8">
            <?php echo $this->Html->image('uploads/'.$paris['Pari']['image'], array('class'=>'img-rounded width-100')); ?>
        </div>
        <div class="col-xs-6 col-md-4">
            <div class="panel panel-default">
                <div class="panel-body">
                    <span class="btn btn-danger" style="width:100%;">Détails</span>
                    <div class="padding-medium">
                        <p><?php echo '<strong>'.$paris['Pari']['description'].'</strong>'; ?></p>
                        <p>
                            Créé par
                            <?php
                            if($createur['facebook_id'] != 0){
                                echo $this->Facebook->picture($createur['facebook_id']);
                            }
                            else{
                                echo '<i>'.$createur['pseudo'].'</i>';
                            }?>
                        </p>
                        <dl>
                            <span class="display-block"><strong>Choix et cotes</strong></span>
                            <?php
                            foreach ($choix as $choi):?>

                                    <?php echo $choi['Choix']['nom']; ?>
                                     <span class ="glyphicon glyphicon-chevron-right"></span>
                                    <?php echo $choi['Choix']['cote']; ?> <br/>
                            <?php endforeach; ?>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div>
    <?php
    $peutMiser = false; // va servir pour déterminer l'affichage
    if (date("Y-m-d") < $paris['Pari']['date_fin']) {

        if (!AuthComponent::user()) {
            echo '<p> <h3 style="display:inline-block;">' . $this->Html->link('Connectez-vous',
                    array('controller' => 'parieurs', 'action' => 'connexion')) .
                '</h3> pour miser sur ce pari.</p';
        } else {
            if ($paris['Pari']['parieur_id'] == $id_util) {
                echo '<blockquote class="blockquote-info">';
                ?>
                <span class="glyphicon glyphicon-remove"></span>
                <?php
                        echo 'Vous ne pouvez miser sur ce pari puisque vous êtes son créateur.
                        </blockquote>';
            } else if ($dejaMise) {
                ?>
                <blockquote class="blockquote-info">
                    <span class="glyphicon glyphicon-remove"></span>
                    Vous avez déjà misé sur ce pari.
                </blockquote>
                <?php
            } else if (AuthComponent::user()) {
                echo $this->Form->create('ParieursPari', array(
                    'inputDefaults' => array(
                        'div' => 'form-group',
                        'wrapInput' => false,
                        'class' => 'form-control'
                    ))); ?>
                <fieldset>
                    <h1>Faites votre mise !</h1>
                    <?php

                    $attributes = array('legend' => false, 'separator' => '<br/>');
                    echo $this->Form->radio('choix_id', $options, $attributes);
                    echo $this->Form->input('mise',
                        array('label' => 'Mise:', 'type' => 'number', 'autocomplete' => 'off',
                            'id' => 'txtMise',
                            'data-toggle'=>"tooltip",
                            'data-placement'=>"top",
                            'title'=>"Doit être un nombre entier positif supérieur au nombre de jetons que vous possédez."));

                    echo $this->Form->input('pari_id', array('type' => 'hidden', 'value' => $paris['Pari']['id']));
                    echo $this->Form->input('parieur_id', array('type' => 'hidden', 'value' => $id_util));

                    echo $this->Form->submit('Miser', array(
                        'div' => false,
                        'class' => 'btn btn-primary',
                    ));
                    $peutMiser = true; // pour l'affichage des boutons
                    echo $this->Html->link('Retour au catalogue', array('controller' => 'paris', 'action' => 'index'), array('class' => 'btn btn-default btn-separation'));

                    ?>
                </fieldset>
                <?php echo $this->Form->end();
            }
        }
    } else {
        // si le choix gagnant a été décidé on l'affiche
        if (isset($nom_choixGagnant)) {
            echo '<blockquote  class="blockquote-info">

                <span class="glyphicon glyphicon-remove"></span>
                Ce pari est déjà terminé.';
            echo '<br/><br/>';
            echo 'Le choix gagnant était <i>' . $nom_choixGagnant .'</i>.';
            echo '<br/>';
            // si le parieur avait misé on affiche son choix
            if (isset($nom_choixParieur)) {
                echo 'Vous avez misé  <i>' . $nom_choixParieur . '</i><br/><br/>';

                if ($nom_choixParieur == $nom_choixGagnant) {
                    ?>
                    <span class="glyphicon glyphicon-thumbs-up"></span>
                    <?php
                    echo 'Félicitations, vous aviez la bonne réponse !';
                } else {
                    ?>
                    <span class="glyphicon glyphicon-thumbs-down"></span>
                    <?php
                    echo 'Dommage, vous vous êtes trompé. Meilleure chance la prochaine fois ! ';
                }
            }
            echo '</blockquote>';
            // le choix n'a pas encore été fait
        } else {
            echo '<blockquote class="blockquote-info">Le pari est terminé. Le résultat n\'a pas encore été déterminé.</blockquote>';
        }
    }
    ?>

</div>
<div class="clearfix"></div>
<?php if($peutMiser == false) {
 echo $this->Html->link('Retour au catalogue', array('controller' => 'paris', 'action' => 'index'), array('class' => 'btn btn-default'));
}
?>
<div class="clearfix"></div>
<br/>
<?php echo $this->Facebook->comments(); ?>
