<script type="text/javascript">

    $(document).ready(function(){
        //Hauteur maximale pour les thumbnails, pour qu'ils soient tous égaux
        var maxHeight = 0;
        $("p.description").each(function(){
            if ($(this).height() > maxHeight) {
                maxHeight = $(this).height(); }
        });
        $("p.description").height(maxHeight);
    });
</script>

<div class="jumbotron well">

    <h1 class="text-center">
        Paris <small> Pas la ville</small>
    </h1>

    <div class="carousel slide" id="carousel-15074">
        <ol class="carousel-indicators">
            <li class="active" data-slide-to="0" data-target="#carousel-15074"></li>
            <?php
            $nbParis = count($paris);

            for($i = 1 ; $i < $nbParis - 1; $i++){
                ?>
                <li data-slide-to="<?php echo $i;?>" data-target="#carousel-15074"></li>
            <?php
            }
            ?>

            <li data-slide-to="0" data-target="#carousel-15074"></li>
        </ol>
        <div class="carousel-inner">
            <?php
            $index = 0;
            $class = "item";
            foreach($paris as $pari){
                if($index == 0){
                    $class= "item active";
                    $index = 1;
                }
                else{
                    $class = "item";
                }
                ?>
                <div class="<?php echo $class;?> carousel-grand">
                    <?php echo $this->Html->image('uploads/'.$pari['Pari']['image'], array(
                        'class'=>'image-carousel-grand',
                        "alt" => $pari['Pari']['image'],
                        'url' => array('controller' => 'parieurs_paris', 'action' => 'miser', $pari['Pari']['id'])

                    )); ?>
                    <div class="carousel-caption">
                        <h4>
                            <?php echo $pari['Pari']['nom'];?>
                        </h4>
                        <small>
                            <?php echo $pari['Pari']['description'];?>
                        </small>
                    </div>
                </div>
        <?php } ?>
        </div>
        <a class="left carousel-control" href="#carousel-15074" data-slide="prev"><span
                class="glyphicon glyphicon-chevron-left"></span></a> <a class="right carousel-control"
                                                                        href="#carousel-15074" data-slide="next"><span
                class="glyphicon glyphicon-chevron-right"></span></a>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div id="thmb1" class="thumbnail">
            <div class="thumbnail-div-medium">
                <?php echo $this->Html->image($lot['Lot']['image'], array('class' => 'width-100')); ?>
            </div>

            <div class="caption">
                <h3>
                    Lots à gagner
                </h3>

                <p class="description">
                    En remportant votre mise sur des paris, vous accumulerez des jetons qui vous permettront de vous
                    acheter des lots incroyables.
                    <div class="clearfix"></div>
                    <?php echo $this->Html->link('Voir les lots', array('controller' => 'lots', 'action' => 'index'), array('class' => 'btn btn-primary')); ?>
                </p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div id="thmb2" class="thumbnail">
            <div class="thumbnail-div-medium">
                <?php echo $this->Html->image('faq.jpg'); ?>
            </div>

            <div class="caption">
                <h3>
                    Foire aux questions
                </h3>

                <p class="description">
                    Perdu ? Consultez notre foire aux questions pour bien connaître le fonctionnement du site.<div class="clearfix"></div>
                    <div class="clearfix"></div>
                    <?php echo $this->Html->link('Consulter la FAQ', array('controller' => 'divers', 'action' => 'faq'), array('class' => 'btn btn-primary')); ?>
                </p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div id="thmb3" class="thumbnail">
            <div class="thumbnail-div-medium">
                <?php echo $this->Html->image('inscription.jpg'); ?>
            </div>

            <div class="caption">
                <h3>
                    Inscrivez-vous !
                </h3>

                <p class="description">
                    L'inscription est simple et rapide. Une fois votre compte créé, vous pourrez commencer à miser en ligne et également profiter du système de messagerie.
                    <div class="clearfix"></div>
                    <?php echo $this->Html->link('S\'inscrire', array('controller' => 'parieurs', 'action' => 'inscription'), array('class' => 'btn btn-primary')); ?>
                </p>
            </div>
        </div>
    </div>
</div>
