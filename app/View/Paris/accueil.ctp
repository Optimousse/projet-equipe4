<script type="text/javascript">

    $(document).ready(function(){
        var max = 0, jThumbnails = $("div.thumbnail");
        jThumbnails .each(function(index, elt){
            max = Math.max(max, $(elt).height());
        });
        jThumbnails.css('height', '220px');
    });
</script>

<div class="jumbotron well">

    <h1 class="text-center">
        Paris <small> Pas la ville</small>
    </h1>

    <div class="carousel slide" id="carousel-15074">
        <ol class="carousel-indicators">
            <li data-slide-to="0" data-target="#carousel-15074">
            </li>
            <li data-slide-to="1" data-target="#carousel-15074">
            </li>
            <li data-slide-to="2" data-target="#carousel-15074">
            </li>
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
                <div class="<?php echo $class;?>" style="max-width:1200px; max-height:400px;">
                    <img style="width:1200px; overflow:hidden;" alt="" src="<?php echo $pari['Pari']['image'];?>"/>

                    <div class="carousel-caption">
                        <h4>
                            <?php echo $pari['Pari']['nom'];?>
                        </h4>
                        <p>
                            <?php echo $pari['Pari']['description'];?>
                        </p>
                    </div>
                </div>
        <?php } ?>
        </div>
        <a class="left carousel-control" href="#carousel-15074" data-slide="prev"><span
                class="glyphicon glyphicon-chevron-left"></span></a> <a class="right carousel-control"
                                                                        href="#carousel-15074" data-slide="next"><span
                class="glyphicon glyphicon-chevron-right"></span></a>
    </div>

    <p></p>
</div>

<div class="row">
    <div class="col-md-4">
        <div id="thmb1" class="thumbnail" style="min-height: 205px;">
            <img src="http://lorempixel.com/400/100/city/"/>

            <div class="caption">
                <h3>
                    Lots à gagner
                </h3>

                <p>
                    En remportant votre mise sur des paris, vous accumulerez des jetons qui vous permettront de vous
                    acheter des lots incroyables.
                </p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div id="thmb2" class="thumbnail" style="min-height: 205px;">
            <img src="http://orionschoolwear.co.uk/image/cache/data/Smaller%20banner/about-1-1200x300.jpg"/>

            <div class="caption">
                <h3>
                    Messagerie instantanée
                </h3>

                <p>
                    Discutez et pariez avec des milliers d'utilisateurs à travers le monde entier.
                </p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div id="thmb3" class="thumbnail" style="min-height: 205px;">
            <img src="http://lorempixel.com/400/100/people/"/>

            <div class="caption">
                <h3>
                    Inscrivez-vous !
                </h3>

                <p>
                    L'inscription est simple et rapide.
                <div class="clearfix"></div>
                <?php echo $this->Html->link('S\'inscrire', array('controller' => 'parieurs', 'action' => 'inscription'), array('class' => 'btn btn-primary')); ?>
                </p>
            </div>
        </div>
    </div>
</div>
