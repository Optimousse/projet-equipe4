<!-- Fichier : /app/View/Posts/view.ctp -->


<div style="max-width: 50%;">
    <h3><?php echo $paris['Pari']['nom']; ?></h3>
    <img src=<?php echo $paris['Pari']['image']; ?> alt="img"/>
<br/><br/>
    <blockquote><?php echo $paris['Pari']['description']; ?></blockquote>
    <?php
    if(date("Y-m-d") < $paris['Pari']['date_fin']){
    ?>
        <p>Ce pari se termine le <?php echo $paris['Pari']['date_fin']; ?>.</p>

        <?php foreach ($choix as $choi):?>

            <h5>
                <?php echo $choi['Choix']['nom']; ?>
            </h5>
            <p>
                Cote: <?php echo $choi['Choix']['cote']; ?>
            </p>

        <?php endforeach ;

        if($paris['Pari']['parieur_id'] == $id_util){
            echo '<div class="alert alert-info">Vous ne pouvez miser sur ce pari puisque vous êtes son créateur.</div>';
        }
        else if($dejaMise){
            echo '<div class="alert alert-info">Vous avez déjà misé sur ce pari.</div>';
        }
        else{
            echo $this->Form->create('ParieursPari', array('class'=>'well')); ?>
            <fieldset>
                <legend>Faites votre mise !</legend>
                <?php
                echo $this->Form->input('choix_id',
                    array('options' => $options, 'type' => 'radio', 'required'=>'required','legend'=>false));

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

    // si le choix gagnant a été décidé on l'affiche
    if (isset($nom_choixGagnant)) {
        echo '<div class="alert alert-info">Ce pari est déjà terminé. Félicitations aux gagnants !';
        echo '<br/><br/>';
        echo 'le choix gagnant est : '.$nom_choixGagnant;
        echo '<br/>';
        // si le parieur avait misé on affiche son choix
        if (isset($nom_choixParieur)) {
           echo 'Vous avez misé : '.$nom_choixParieur.'<br/><br/>';

            if($nom_choixParieur == $nom_choixGagnant) {
                echo 'bravo vous aviez la bonne réponse';
            } else {
                echo 'dommage vous vous etes trompé';
            }
        }
        echo'</div>';
    // le choix n'a pas encore été fait
    } else {
        echo 'le paris est terminé. Le résultat n\'a pas encore été déterminé';
    }
    ?>


</div>