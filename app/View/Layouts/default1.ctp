<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <title>
        <?php echo $title_for_layout; ?>
    </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <?php
    echo $this->Html->css("notreCss", null, array("inline" => false));
    echo $this->Html->script('jquery');
    ?>
    <script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
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

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <?php
    echo $this->fetch('meta');
    echo $this->fetch('css');
    echo $this->fetch('script');
    ?>

    <script type="text/javascript">
        $(document).ready(function(){
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
            getMessages();

            setInterval(function(){
                getMessages();
            }, 5000);

            $("#liMessagerie").click(function(){
                $.ajax({
                    url: urlTousMessagesLus,
                    success: function(){
                        $("#badgeNouveauMessage").hide();
                    }
                });
            });

            $( "#btnSoumettre" ).click(function(e) {
                $.ajax({
                    type: "POST",
                    url: urlAjouter,
                    data: $( "#frmMessages" ).serialize()
                });

                getMessages();

                e.preventDefault();
            });

            function getMessages(){
                $.ajax({
                    type: "POST",
                    url: urlGetMessages,
                    dataType: "json",
                    success: function (data) {
                        $("#txtMessage").val('');

                        if(data[0].nouveauMessage){
                            $("#badgeNouveauMessage").show();
                            remplirConversation(data);
                        }

                        if(document.getElementById("divMessages").innerHTML === ""){
                            remplirConversation(data);
                        }
                    }
                });
            }

            function remplirConversation(data){

                var str = '<ul style="list-style-type: none;">' +
                    '<li><h4>Derniers messages</h4></li>';
                $.each(data, function() {
                    if(this.parieurs != undefined){
                        str += '<li><blockquote> <b>' + this.parieurs.pseudo + '</b> dit:<br/>';
                        str += this.Message.message + '</blockquote></li>';
                    }
                });
                str += '</ul>'

                $("#divMessages").empty();
                $("#divMessages").append(str);
            }
        });
    </script>
</head>

<body>
<div class="navbar navbar-fixed-top ">
    <div class="navbar-inner">
        <div class="container">
            <?php echo $this->Html->link('Paris, pas la ville', array(
                'controller' => 'paris',
                'action' => 'index'
            ), array('class' => 'brand')); ?>
            <ul class="nav">
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
                    <li><?php echo $this->Html->link('Créer un pari', array('controller' => 'paris',
                            'action' => 'ajouter'
                        )); ?></li>
                    <li class="dropdown-submenu"><?php echo $this->Html->link('Mon compte', array('controller' => 'parieurs',
                            'action' => 'mon_compte'
                        )); ?>
                        <ul class="dropdown-menu">
                            <li><?php echo $this->Html->link('Modifier mon compte', array('controller' => 'parieurs',
                                    'action' => 'mon_compte'
                                )); ?></li>
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
                    <li><?php echo $this->Html->link('|', array('controller' => 'null',
                            'action' => 'null'
                        )); ?></li>
                    <li id="liMessagerie" class="dropdown-submenu">
                        <a href="#">
                            <span id="badgeNouveauMessage" style="display:none;" class="badge badge-important pull-right">&nbsp;     1</span>
                            Messagerie&nbsp;
                        </a>
                        <ul id="ulMessagerie" class="dropdown-menu">

                            <div id="divMessages"></div>
                            <?php
                            echo $this->Form->create('Message', array(
                                'inputDefaults' => array(
                                    'div' => 'form-group',
                                    'label' => false,
                                    'wrapInput' => false,
                                    'class' => 'form-control'
                                ),
                                'class' => 'well form-inline',
                                'id' => 'frmMessages'
                            ));

                            echo $this->Form->input('message', array(
                                'label' => false, 'type' => 'text', 'placeholder' => 'Écrivez votre message ici', 'id' => 'txtMessage', 'autocomplete' => 'off'
                            ));
                            echo $this->Form->input('parieur_id', array('type' => 'hidden', 'value' => AuthComponent::user('id')));

                            echo $this->Form->submit('Envoyer', array(
                                'div' => false,
                                'class' => 'btn btn-primary', 'id' => 'btnSoumettre'
                            ));

                            echo $this->Form->end();
                            ?>
                        </ul>
                    </li>
                    <li><?php echo $this->Html->link('Déconnexion', array('controller' => 'parieurs',
                            'action' => 'logout'
                        )); ?></li>
                <?php
                }
                ?>


            </ul>
        </div>
    </div>
</div>

<div class="container">

    <?php
    echo $this->Session->flash();
    echo $this->Session->flash('auth');

    echo $this->fetch('content');
    ?>

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
