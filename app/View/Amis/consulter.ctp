<script type="text/javascript">
    $(document).ready(function(){
        $(".img-accepter-amitie").click(function(e){
            var id = $(this).parent().find("input:hidden").val();
            var url = '<?php echo $this->Html->url(array(
                "controller" => "amis",
                "action" => "accepterDemandeAmitie"
            )); ?>' + '/' + id;

            updateAmitie(url, $(this), true);
            e.preventDefault();
        });

        $(".img-refuser-amitie").click(function(e){
            var id = $(this).parent().find("input:hidden").val();
            var url = '<?php echo $this->Html->url(array(
                "controller" => "amis",
                "action" => "refuserDemandeAmitie"
            )); ?>' + '/' + id;

            updateAmitie(url, $(this), false);
            e.preventDefault();
        });

        function updateAmitie(url, $btn, Accepter){
            $.ajax({
                type: "POST",
                url: url,
                dataType: "json",
                success: function (data) {
                    if (data.error_code === 0) {
                        var $div_AmitieCourante = $btn.closest("div.row");
                        var nbElements = $div_AmitieCourante.children().length;

                        if(Accepter){
                            $("#SansAmis").css('display', 'none');
                            $btn.closest("div.col-md-2").appendTo($("#div_amis"));
                        }

                        $btn.siblings("img").remove();
                        $btn.remove();
                        $btn.closest("div.col-md-2").remove();

                        nbElements--;
                        if(nbElements === 0){
                            $("#div_Consulter_DemandesRecues").remove();
                        }
                    }
                }
            });
        }
    });
</script>

<h1>
    <?php
    if($parieur['Parieur']['id'] == AuthComponent::user('id')){
        echo 'Mes amis';
    }
    else
    {
        echo 'Amis de '.$parieur['Parieur']['pseudo'];
    }
    ?>
    <small class="font-size-40">
        <?php
        if($parieur['Parieur']['id'] == AuthComponent::user('id')){
            $nomLien = 'Retour à ma page personnelle';
        }
        else{
            $nomLien = 'Retour à la page personnelle';
        }

        echo $this->Html->link($nomLien, array('controller' => 'parieurs', 'action' => 'consulter', $parieur['Parieur']['id']));

        ?>
    </small>
</h1>

<div class="row" id="div_amis">
    <?php
    $compteur = 0;
    foreach ($amis as $ami){  ?>
        <div class="col-md-2">
            <div class="thumbnail">
                <div class="thumbnail-div-medium">
                    <?php
                    echo $this->Html->image('avatars/' . $ami['Parieur']['avatar'], array(
                        "alt" => $ami['Parieur']['avatar'],
                        'class' => 'width-100',
                        'url' => array('controller' => 'parieurs', 'action' => 'consulter', $ami['Parieur']['id'], 'class'=>'test')
                    ));
                    ?>
                </div>

                <div class="caption">
                    <div class="description">
                        <p class="text-center">
                            <?php echo $this->Html->link($ami['Parieur']['pseudo'], array('controller' => 'parieurs','action'=>'consulter', $ami['Parieur']['id'])); ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>


        <?php
        $compteur++;
        if ($compteur == 6) {
            echo '<div class="clearfix"></div>';
        }
    }
    if(empty($amis))
    {
        ?>
        <blockquote id="SansAmis" class="blockquote-info">
            <?php
            if($parieur['Parieur']['id'] == AuthComponent::user('id')){
                echo 'Vous n\'avez pas d\'amis.';
            }
            else{
                echo $parieur['Parieur']['pseudo'].' n\'a pas d\'amis.';
            }
            ?>
        </blockquote>
    <?php
    }
    unset($ami);
    ?>
</div>

<?php if(isset($demandesRecues) && $demandesRecues)
{
    ?>
    <div id="div_Consulter_DemandesRecues">
        <h3>Demandes d'amitié reçues</h3>
        <div class="row">
            <?php
            $compteur = 0;
            foreach ($demandesRecues as $ami){  ?>
                <div class="col-md-2">
                    <div class="thumbnail">
                        <div class="thumbnail-div-medium">
                            <?php echo $this->Html->image('avatars/' . $ami['Parieur']['avatar'], array(
                                "alt" => $ami['Parieur']['avatar'],
                                'class' => 'width-100',
                                'url' => array('controller' => 'parieurs', 'action' => 'consulter', $ami['Parieur']['id'])
                            ));
                            ?>
                        </div>

                        <div class="caption">
                            <div class="description">
                                <p class="text-center">
                                    <input type="hidden" value="<?php echo $ami['Ami']['id'];?>">
                                    <?php
                                    echo $this->Html->link($ami['Parieur']['pseudo'], array('controller' => 'parieurs','action'=>'consulter', $ami['Parieur']['id']));
                                    ?>
                                    <div class="clearfix"></div>
                                    <?php
                                    echo $this->Html->image('glyphicons_193_circle_ok.png', array('class'=>'img-accepter-amitie'));
                                    echo $this->Html->image('glyphicons_192_circle_remove.png', array('class'=>'img-refuser-amitie'));
                                    ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>


                <?php
                $compteur++;
                if ($compteur == 6) {
                    echo '<div class="clearfix"></div>';
                }
            }
            unset($ami);
            ?>
        </div>
    </div>
<?php
}
?>

<?php if(isset($demandesEnvoyees) && $demandesEnvoyees)
{
?>
<h3>Demandes d'amitié envoyées</h3>
    <div class="row">
        <?php
        $compteur = 0;
        foreach ($demandesEnvoyees as $ami){  ?>
            <div class="col-md-2">
                <div class="thumbnail">
                    <div class="thumbnail-div-medium">
                        <?php echo $this->Html->image('avatars/' . $ami['Parieur']['avatar'], array(
                            "alt" => $ami['Parieur']['avatar'],
                            'class' => 'width-100',
                            'url' => array('controller' => 'parieurs', 'action' => 'consulter', $ami['Parieur']['id'])
                        ));
                        ?>
                    </div>

                    <div class="caption">
                        <div class="description">
                            <p class="text-center">
                                <?php echo $this->Html->link($ami['Parieur']['pseudo'], array('controller' => 'parieurs','action'=>'consulter', $ami['Parieur']['id'])); ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>


            <?php
            $compteur++;
            if ($compteur == 6) {
                echo '<div class="clearfix"></div>';
            }
        }
        unset($ami);
        ?>
    </div>
<?php
}
?>
