<!DOCTYPE html>
<?php echo $this->Facebook->html(); ?>
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <title>
        <?php echo $title_for_layout; ?>
    </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <script src="http://code.jquery.com/jquery-1.9.0.js"></script>
    <!-- Le styles -->
    <?php
    echo $this->Html->css("bootstrap", null, array("inline" => false));
    echo $this->Html->css("bootstrap-theme", null, array("inline" => false));
    echo $this->Html->css("datepicker3", null, array("inline" => false));
    echo $this->Html->css("notreCss", null, array("inline" => false));
    echo $this->Html->script('bootstrap.min');
    echo $this->Html->script('bootstrap-datepicker');
    ?>
    <style>
        body {
            padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
        }

        .affix {
            position: fixed;
            top: 60px;
            width: 220px;
        }
    </style>
    <?php
    echo $this->fetch('meta');
    echo $this->fetch('css');
    echo $this->fetch('script');
    ?>

    <script type="text/javascript">
        $(document).ready(function () {
            var _messagesLus = false;
            var _nbMessagesLus = 0;
            var urlAjouter = '<?php echo $this->Html->url(array(
                "controller" => "messages",
                "action" => "ajouter"
            )); ?>';
            var urlGetMessages = '<?php echo $this->Html->url(array(
                "controller" => "messages",
                "action" => "getMessages"
            )); ?>';
            var urlTousMessagesLus = '<?php echo $this->Html->url(array(
                "controller" => "messages",
                "action" => "setTousMessagesLus"
            )); ?>';

            jQuery.noConflict();
            $("#badgeParisTermines").tooltip();
            $("#badgeNouveauMessage").tooltip();

            if ($("#txtConnecte").val() === '1') {
                getMessages(false, true);

                setInterval(function () {
                    getMessages(false);
                }, 5000);
            }

            $("#txtMessage").keydown(function (e) {
                if (e.keyCode === 13) {
                    soumettreForm();
                    e.preventDefault();
                }
            });

            $('#myModal').on('shown.bs.modal', function (e) {
                $("#divMessages").animate({ scrollTop: $('#divMessages')[0].scrollHeight}, 500);
                $("#txtMessage").focus();
            })

            $("#liMessagerie").click(function () {
                _messagesLus = true;
                setMessagesLus();
            });
            $("#divMessagerie").click(function () {
                setMessagesLus();
            });
            $("#divMessages").click(function () {
                setMessagesLus();
            });

            $("#btnSoumettre").click(function (e) {
                soumettreForm();
                e.preventDefault();
            });

            function setMessagesLus() {
                _nbMessagesLus = 0;
                $.ajax({
                    url: urlTousMessagesLus,
                    success: function () {
                        $("#badgeNouveauMessage").css('display', 'none');
                    }
                });
            }

            //Va chercher les derniers messages qui n'ont pas été lus
            function getMessages(estAjout, estPageLoad) {
                if (estAjout === undefined)
                    estAjout = false;
                if (estPageLoad === undefined)
                    estPageLoad = false;
                $.ajax({
                    type: "POST",
                    url: urlGetMessages,
                    data: {'estPageLoad': estPageLoad, 'estAjout': estAjout},
                    dataType: "json",
                    success: function (data) {
                        console.log(data);
                        if (data.length > 0) {
                            //On n'affiche le badge que si l'utilisateur a utilisé la messagerie
                            //(Pour ne pas l'importuner) et si le nouveau message en question n'est pas celui
                            //qu'il vient d'écrire
                            if (estAjout === false) {
                                _nbMessagesLus = data[0].nbMessagesNonLus;
                                if(_nbMessagesLus > 0){
                                    $("#badgeNouveauMessage").empty();
                                    $("#badgeNouveauMessage").append(_nbMessagesLus);
                                    $("#badgeNouveauMessage").css('display', 'inline-block');
                                }
                            }
                            remplirConversation(data);
                        }
                    }
                });
            }

            //Ajoute les derniers messages à la liste non ordonnée
            function remplirConversation(data) {

                var str = "";
                $.each(data, function () {
                    if (this.parieurs != undefined) {
                        str += '<li><blockquote>' +
                             this.Message.message +
                            '<small><b>'+ this.parieurs.pseudo + '</b>, ' + this.Message.created + '</small>' +
                            '</blockquote></li><hr>';
                    }
                });

                $("#divMessages").append(str);
                $("#divMessages").animate({ scrollTop: $('#divMessages')[0].scrollHeight}, 1000);
            }

            function soumettreForm(){

                $.ajax({
                    type: "POST",
                    url: urlAjouter,
                    data: $("#frmMessages").serialize(),
                    success: function (data) {
                        $("#txtMessage").val('');
                        getMessages(true, false)
                    }
                });
            }
        });
    </script>
</head>

<body>
<?php echo $this->Facebook->init();?>
<div class="container">
        <div class="col-md-12 column">
            <nav class="navbar navbar-default navbar-fixed-top navbar-inverse" role="navigation">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse"
                            data-target="#bs-example-navbar-collapse-1">
                        <span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span
                            class="icon-bar"></span><span class="icon-bar"></span>
                    </button> <?php echo $this->Html->link('Paris, pas la ville', array(
                        'controller' => 'divers',
                        'action' => 'accueil'
                    ), array('class' => 'navbar-brand')); ?>
                </div>

                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav">

                        <li><?php echo $this->Html->link('Catalogue', array('controller' => 'paris',
                                'action' => 'index'
                            )); ?></li>

                        <li><?php echo $this->Html->link('Lots', array('controller' => 'lots',
                                'action' => 'index'
                            )); ?></li>

                        <?php
                        if (!AuthComponent::user()) {
                            ?>
                            <li><?php echo $this->Html->link('Connexion', array('controller' => 'parieurs',
                                    'action' => 'connexion'
                                )); ?></li>
                            <li><?php echo $this->Html->link('Inscription', array('controller' => 'parieurs',
                                    'action' => 'inscription'
                                )); ?></li>
                        <?php
                        } else {
                            ?>
                            <input type="hidden" id="txtConnecte" value="1"/>
                            <li><?php echo $this->Html->link('Créer un pari', array('controller' => 'paris',
                                    'action' => 'ajouter'
                                )); ?></li>

                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <?php
                                    if($nbParisTermines > 0){
                                        if($nbParisTermines == 1){
                                            $msg = 'Un pari attend que vous déterminiez le choix gagnant.';
                                        }
                                        else{
                                            $msg = $nbParisTermines.' paris attendent que vous déterminiez le choix gagnant.';
                                        }
                                        ?>
                                        <span id="badgeParisTermines" class="badge badge-important"
                                            data-toggle="tooltip"
                                            data-placement="bottom"
                                            title="<?php echo $msg;?>"
                                            ><?php echo $nbParisTermines;?></span>
                                    <?php } ?>
                                    Mon compte <strong class="caret"></strong>
                                </a>

                                <ul class="dropdown-menu">
                                    <?php
                                    if($this->Session->check('connexionNormale')){
                                    ?>
                                        <li>
                                            <?php
                                            echo $this->Html->link('Modifier mon compte', array('controller' => 'parieurs',
                                                'action' => 'mon_compte'
                                            )); ?></li>
                                    <?php
                                    }
                                    ?>
                                    <li><?php echo $this->Html->link('Mes paris', array('controller' => 'paris',
                                            'action' => 'mes_paris'
                                        )); ?></li>
                                    <li><?php echo $this->Html->link('Mes mises', array('controller' => 'parieurs_paris',
                                            'action' => 'mes_mises'
                                        )); ?></li>
                                    <li><?php echo $this->Html->link('Acheter des jetons', array('controller' => 'parieurs',
                                            'action' => 'acheter_jetons'
                                        )); ?></li>
                                </ul>
                            </li>
                            <li><?php
                                //Affiche un lien html normal si on est connecté via le système d'inscription normale.
                                //Sinon, affiche un lien html qui déconnectera l'utilisateur de facebook et du site.
                                if($this->Session->check('connexionNormale')){
                                    echo $this->Html->link('Déconnexion', array('controller' => 'parieurs',
                                        'action' => 'logout'
                                    ));
                                }
                                else{
                                    echo $this->Facebook->logout(array('label' => 'Déconnexion', 'redirect' => array('controller' => 'parieurs', 'action' => 'logout')));
                                }

                                ?></li>
                            <li id="liMessagerie" class="dropdown">
                                <a id="modal-473524" href="#myModal" role="button" class="btn" data-toggle="modal">
                                    <span id="badgeNouveauMessage" style="display:none;"
                                          class="badge badge-important"
                                          data-toggle="tooltip"
                                          data-placement="bottom"
                                          title="Un ou plusieurs message(s) non lu(s).">1</span>
                                    <?php echo $this->Html->image('glyphicons_010_envelope.png'); ?>
                                    &nbsp;&nbsp;<strong class="caret"></strong>
                                </a>
                            </li>
                        <?php
                        }
                        ?>
                    </ul>
                </div>
            </nav>

            <?php
            echo $this->Session->flash();
            echo $this->Session->flash('auth');
            echo $this->fetch('content'); ?>
            <div class="clearfix"></div>
            <?php echo $this->Facebook->friendpile();

            if (AuthComponent::user()) { ?>
            <div class="modal fade" style="margin-top:22px;" id="myModal" role="dialog" aria-labelledby="myModalLabel"
                 aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h4 class="modal-title" id="myModalLabel">
                                Derniers messages
                            </h4>
                        </div>

                        <ul id="divMessages"></ul>
                        <?php
                        echo $this->Form->create('Message', array(
                            'inputDefaults' => array(
                                'div' => 'form-group',
                                'label' => false,
                                'wrapInput' => false,
                                'class' => 'form-control'
                            ),
                            'id' => 'frmMessages'
                        ));
                        ?>

                        <div id="divMessagerie" class="modal-body">
                            <?php                            echo $this->Form->input('parieur_id', array('type' => 'hidden', 'value' => $id_utilisateur));

                            echo $this->Form->input('message', array(
                                'label' => false, 'id' => 'txtMessage', 'div' => false, 'type' => 'text', 'placeholder' => 'Écrivez votre message ici', 'id' => 'txtMessage', 'autocomplete' => 'off'
                            ));?>
                            <br/>

                            <div class="input-group">
                                <br/>

                                <button id="btnSoumettre" class="btn btn-primary" type="button">Envoyer</button>
                                <button class="btn btn-default btn-separation" contenteditable="true" data-dismiss="modal"
                                        type="button">Fermer
                                </button>

                            </div>
                        </div>
                        <?php
                        echo $this->Form->end();
                        ?>
                    </div>
                </div>
            </div>
            <?php } ?>

        </div>

    </div>
</div>

<!-- /container -->

<!-- Le javascript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script>
<script src="//google-code-prettify.googlecode.com/svn/loader/run_prettify.js"></script>
<?php
echo $this->Js->writeBuffer();
?>
</body>
</html>
