<!-- Fichier : /app/View/Posts/view.ctp -->

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
    <div class="row">
        <div class="col-xs-12 col-md-8">
            <img alt="" style="max-width:100%;" src="<?php echo $paris['Pari']['image']; ?>"/>
        </div>
        <div class="col-xs-6 col-md-4">
            <div class="panel panel-default">
                <div class="panel-body">
                    <p><?php echo $paris['Pari']['description']; ?></p>
                        <span class="lead" style="margin:0;">Choix et cotes</span>
                    <dl>
                        <?php
                        foreach ($choix as $choi):?>
                            <dt><?php echo $choi['Choix']['nom']; ?></dt>
                            <dd><?php echo $choi['Choix']['cote']; ?></dd>
                        <?php endforeach; ?>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>

<div>
    <?php
    if (date("Y-m-d") < $paris['Pari']['date_fin']) {

        if (!AuthComponent::user()) {
            echo '<p> <h3 style="display:inline-block;">' . $this->Html->link('Connectez-vous',
                    array('controller' => 'parieurs', 'action' => 'connexion')) .
                '</h3> pour miser sur ce pari.</p';
        } else {
            if ($paris['Pari']['parieur_id'] == $id_util) {
                echo '<blockquote style="border-color:#2D6CA2; background-color:#eee;">
                        Vous ne pouvez miser sur ce pari puisque vous êtes son créateur.
                        </blockquote>';
            } else if ($dejaMise) {
                echo '<blockquote style="border-color:#2D6CA2; background-color:#eee;">Vous avez déjà misé sur ce pari.</blockquote>';
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
                        array('label' => 'Mise:', 'type' => 'number'));

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
    } else {
        // si le choix gagnant a été décidé on l'affiche
        if (isset($nom_choixGagnant)) {
            echo '<blockquote style="border-color:#2D6CA2; background-color:#eee;">Ce pari est déjà terminé.';
            echo '<br/><br/>';
            echo 'Le choix gagnant était <i>' . $nom_choixGagnant .'</i>.';
            echo '<br/>';
            // si le parieur avait misé on affiche son choix
            if (isset($nom_choixParieur)) {
                echo 'Vous avez misé  <i>' . $nom_choixParieur . '</i><br/><br/>';

                if ($nom_choixParieur == $nom_choixGagnant) {
                    echo 'Félicitations, vous aviez la bonne réponse !';
                } else {
                    echo 'Dommage, vous vous êtes trompé. Meilleure chance la prochaine fois !';
                }
            }
            echo '</blockquote>';
            // le choix n'a pas encore été fait
        } else {
            echo '<blockquote style="border-color:#2D6CA2; background-color:#eee;">Le pari est terminé. Le résultat n\'a pas encore été déterminé.</blockquote>';
        }
    }
    ?>

</div>
<div class="clearfix"></div>
<?php echo $this->Html->link('Retour au catalogue', array('controller' => 'paris', 'action' => 'index'), array('class' => 'btn btn-default')); ?>

