<h1>Foire aux questions</h1>

<blockquote class="blockquote-info">
   Trouvez ici les réponses aux questions les plus fréquemment posées. Si vous ne parvenez pas à trouver une réponse à votre question, vous pouvez nous <a href="#">écrire un courriel</a>.
</blockquote>

<div class="panel-group" id="accordion">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                    L'inscription est-elle gratuite ?
                </a>
            </h4>
        </div>
        <div id="collapseOne" class="panel-collapse collapse in">
            <div class="panel-body padding-small">
                L'inscription au site est totalement gratuite. Une fois votre compte créé, vous vous verrez automatiquement
                donner 100 jetons en guise de cadeaux de bienvenue. Vous pourrez utiliser ces jetons pour miser sur des paris.
                <?php echo $this->Html->link('Inscrivez-vous maintenant !', array('controller' => 'parieurs', 'action' => 'inscription')); ?>

            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
                    Comment miser sur un pari ?
                </a>
            </h4>
        </div>
        <div id="collapseTwo" class="panel-collapse collapse">
            <div class="panel-body padding-small">
                Pour miser sur un pari, vous devez d'abord
                <?php echo $this->Html->link('posséder un compte', array('controller' => 'parieurs', 'action' => 'inscription')); ?>
                et
                <?php echo $this->Html->link('être connecté', array('controller' => 'parieurs', 'action' => 'connexion')); ?>.
                Une fois connecté, visitez la page
                <?php echo $this->Html->link('Catalogue', array('controller' => 'paris', 'action' => 'index')); ?>
                et cliquez sur le lien <strong>Miser</strong> d'un pari qui vous intéresse. Si ce pari n'est pas encore terminé et
                que vous n'avez pas déjà misé dessus, vous serez invité à remplir un formulaire où vous devrez indiquer:
                <ol>
                    <li>Le choix pour lequel vous voulez miser;</li>
                    <li>Le montant que vous voulez miser.</li>
                </ol>
                <blockquote class="blockquote-alert"><strong>Note</strong>: Vous ne pouvez miser plus de jetons que vous n'en possédez.</blockquote>
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree">
                    Comment fonctionnent les mises ?
                </a>
            </h4>
        </div>
        <div id="collapseThree" class="panel-collapse collapse">
            <div class="panel-body padding-small">
                Lorsque vous misez sur un pari, vous devez décider le choix sur lequel vous pariez. À chaque choix est associée une cote.
                Après avoir misé, le nombre de jetons que vous avez misé se soustrait au nombre que vous possédez.
                Lorsque le pari sera terminé, le choix gagnant sera annoncé. À ce moment:

                <dl>
                    <dt>
                        Si vous aviez misé sur le choix gagnant
                    </dt>
                    <dd>
                        On dit alors que vous avez remporté le pari. Vous recevrez un courriel vous annonçant que vous aviez correctement misé.
                        Vous gagnerez de nouveaux jetons selon la formule suivante:<br/>
                        <i>Nombre de jetons misés X Cote du choix sur lequel vous aviez misé</i>
                        <p>
                            Exemple:Le pari <i>A</i> vous offrait deux choix dont les cotes étaient:
                            <ol>
                                <li>1.5</li>
                                <li>2</li>
                            </ol>
                            Vous avez misé 100 jetons sur le deuxième choix. 100 jetons se sont déduits à votre compte.
                            Quelques jours plus tard, le choix gagnant est annoncé et vous avez remporté le pari.
                            Vous gagnez donc <i>100 jetons X Cote de 2</i>, soit 200 jetons. Vous avez fait un bénéfice de 100 jetons.
                        </p>

                    </dd>

                    <dt>
                        Si vous aviez misé sur le choix perdant
                    </dt>
                    <dd>
                        On dit alors que vous avez perdu votre pari. Vous recevez un courriel vous annonçant que vous aviez mal misé.
                        Aucun jeton supplémentaire n'est déduit à votre compte.
                    </dd>
                </dl>
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapseFour">
                    Comment créer un pari ?
                </a>
            </h4>
        </div>
        <div id="collapseFour" class="panel-collapse collapse">
            <div class="panel-body padding-small">
                Pour créer un pari, vous devez d'abord
                <?php echo $this->Html->link('posséder un compte', array('controller' => 'parieurs', 'action' => 'inscription')); ?>
                et
                <?php echo $this->Html->link('être connecté', array('controller' => 'parieurs', 'action' => 'connexion')); ?>.
                Visitez ensuite la page
                <?php echo $this->Html->link('Créer un pari', array('controller' => 'paris', 'action' => 'ajouter')); ?>
                et remplissez le formulaire.
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapseFive">
                    Qu'est-ce qu'un choix ?
                </a>
            </h4>
        </div>
        <div id="collapseFive" class="panel-collapse collapse">
            <div class="panel-body padding-small">
                Chaque pari possède ente deux et trois choix. Les choix sont les possibilités offertes aux parieurs lorsqu'ils misent sur un pari.
                Chaque choix possède un nom servant à le décrire et une cote comprise entre 1.1 et 5.
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapseSix">
                    Je suis à court de jetons. Que faire ?
                </a>
            </h4>
        </div>
        <div id="collapseSix" class="panel-collapse collapse">
            <div class="panel-body padding-small">
                Si vous ne possédez plus suffisamment de jetons pour miser sur un pari, vous pouvez
                <?php echo $this->Html->link('en acheter de nouveaux', array('controller' => 'parieurs', 'action' => 'acheter_jetons')); ?>.
                Les jetons s'achètent au coût de 5$ l'unité. Lors du processus d'achat, vous devrez indiquer le nombre de jetons que
                vous souhaitez acheter. Vous devrez ensuite fournir vos informations bancaires.
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapseSeven">
                    J'ai accumulé beaucoup de jetons. Que puis-je en faire ?
                </a>
            </h4>
        </div>
        <div id="collapseSeven" class="panel-collapse collapse">
            <div class="panel-body padding-small">
                Si vous avez accumulé suffisamment de jetons, vous pourrez peut-être vous offrir un ou plusieurs des lots disponibles.
                Consultez la section
                <?php echo $this->Html->link('Lots', array('controller' => 'lots', 'action' => 'index')); ?>
                pour consulter la liste des lots disponibles.
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapseHeight">
                    Comment acheter un lot ?
                </a>
            </h4>
        </div>
        <div id="collapseHeight" class="panel-collapse collapse">
            <div class="panel-body padding-small">
                Rendez-vous dans la section
                <?php echo $this->Html->link('Lots', array('controller' => 'lots', 'action' => 'index')); ?>
                , choisissez un item qui vous intéresse dans la liste et appuyez sur le bouton
                <strong>Acheter</strong>.
                Remplissez le formulaire qui s'affiche afin que nous puissions envoyer votre achat
                à la bonne adresse.
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapseNine">
                    Je suis le créateur d'un pari et la date de fin est maintenant passée. Que dois-je faire ?
                </a>
            </h4>
        </div>
        <div id="collapseNine" class="panel-collapse collapse">
            <div class="panel-body padding-small">
                En tant que créateur d'un pari, vous avez le devoir de déterminer le choix gagnant de ce
                pari une fois qu'il est terminé.
                <br/>
                Pour ce faire, rendez-vous dans la section
                <?php echo $this->Html->link('Mes paris', array('controller' => 'paris', 'action' => 'mes_paris')); ?>
                , cliquez sur le lien <strong>Déterminer le choix gagnant</strong> correspondant au pari terminé,
                puis remplissez le formulaire.
                <br/>
                Lorsque vous aurez soumis le formulaire, les parieurs qui avaient misé sur le choix que vous avez sélectionné
                se verront attribuer des jetons. Le pari sera alors clos et il ne sera plus possible d'y ajouter des mises.
            </div>
        </div>
    </div>
</div>


