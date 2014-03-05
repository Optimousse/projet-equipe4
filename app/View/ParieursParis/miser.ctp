<!-- Fichier : /app/View/Posts/view.ctp -->

<div class="jumbotron well">
    <h1><?php echo $paris['Pari']['nom']; ?></h1>
    <img src=<?php echo $paris['Pari']['image']; ?> alt="img"/>
</div>

<div>
    <p class="lead"><?php echo $paris['Pari']['description']; ?></p>
    <p>
        <?php
            if(date("Y-m-d") < $paris['Pari']['date_fin']){
                echo 'Ce pari se termine le ' .$paris['Pari']['date_fin'] .'.';
            }
        ?>
    <p></p>

    </p>
    <hr/>
    <h3>Cotes</h3>
    <dl>
    <?php
        foreach ($choix as $choi):?>
            <dt><?php echo $choi['Choix']['nom']; ?></dt>
            <dd><?php echo $choi['Choix']['cote']; ?></dd>

    <?php endforeach ; ?>
    </dl>

    <hr/>

    <?php
    if(date("Y-m-d") < $paris['Pari']['date_fin']){
        if($paris['Pari']['parieur_id'] == $id_util){
            echo '<div class="alert alert-info">Vous ne pouvez miser sur ce pari puisque vous êtes son créateur.</div>';
        }
        else if($dejaMise){
            echo '<div class="alert alert-info">Vous avez déjà misé sur ce pari.</div>';
        }
        else if(AuthComponent::user()){
            echo $this->Form->create('ParieursPari',array(
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
                    array('label'=>'Mise:', 'type'=>'number'));

                echo $this->Form->input('pari_id', array('type' => 'hidden', 'value' => $paris['Pari']['id']));
                echo $this->Form->input('parieur_id', array('type' => 'hidden', 'value' => $id_util));

                echo $this->Form->submit('Miser', array(
                    'div' => false,
                    'class' => 'btn btn-primary'
                ));

                ?>
            </fieldset>
        <?php echo $this->Form->end();
        }
    }
    else
    {
        // si le choix gagnant a été décidé on l'affiche
        if (isset($nom_choixGagnant)) {
            echo '<div class="alert alert-info">Ce pari est déjà terminé.';
            echo '<br/><br/>';
            echo 'Le choix gagnant est : '.$nom_choixGagnant;
            echo '<br/>';
            // si le parieur avait misé on affiche son choix
            if (isset($nom_choixParieur)) {
               echo 'Vous avez misé : '.$nom_choixParieur.'<br/><br/>';

                if($nom_choixParieur == $nom_choixGagnant) {
                    echo 'Félicitations, vous aviez la bonne réponse !';
                } else {
                    echo 'Dommage, vous vous êtes trompé. Meilleure chance la prochaine fois !';
                }
            }
            echo'</div>';
        // le choix n'a pas encore été fait
        } else {
            echo '<div class="alert alert-info">Le pari est terminé. Le résultat n\'a pas encore été déterminé.</div>';
        }
    }
    ?>


</div>
