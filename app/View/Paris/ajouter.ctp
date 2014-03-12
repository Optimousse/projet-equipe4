<script type="text/javascript">
    $(document).ready(function () {
        var today = new Date();
        today.setDate(today.getDate() + 1)
        jQuery.noConflict();
        $('#txtDate').datepicker({
            startDate: today,
            language: "fr"
        });

        if($('#txtDate').val() !== ""){
            var $tDate = $('#txtDate').val().split(' ');
            var sDate = $tDate[1] + '/' + $tDate[0] + '/' + $tDate[2];
            $('#txtDate').val(sDate);
        }

        if ($("#txtChoix3").val() !== "" || $("#txtCote3").val() !== "")
            AjouterChoix();

        $("#dNouveauChoix").click(function (e) {

            $("#dNouveauChoix").empty();
            if ($("#liTroisiemeChoix").css('display') === 'none') {
                AjouterChoix();
            } else {
                SupprimerChoix();
            }
            e.preventDefault();
        });

        function AjouterChoix() {
            $("#liTroisiemeChoix").css('display', 'block');
            $("#dNouveauChoix").append('Supprimer le troisième choix.');
        }

        function SupprimerChoix() {
            $("#liTroisiemeChoix").css('display', 'none');
            $("#dNouveauChoix").append('Ajouter un troisième choix.');
        }

        $("#txtDate").tooltip();
        $("#txtImage").tooltip();
        $("#txtCote1").tooltip();
        $("#txtCote2").tooltip();
        $("#txtCote3").tooltip();
    });
</script>

<div class="users form">
    <h1> Créer un pari </h1>
    <?php
    echo $this->Form->create('Pari', array(
        'inputDefaults' => array(
            'div' => 'form-group',
            'wrapInput' => false,
            'class' => 'form-control'
        ),
        'role' => 'form', 'enctype'=>"multipart/form-data")); ?>
    <fieldset>
        <?php
        echo $this->Form->input('parieur_id', array('type' => 'hidden', 'value' => $id_util));

        ?>
        <div class="form-group">
            <?php echo $this->Form->input('nom', array(
                'label' => 'Nom:'));?>
        </div>
        <div class="form-group">
            <?php echo $this->Form->input('date_fin', array(
                'label' => 'Se termine le:',
                'type' => 'text',
                'class' => 'form-control',
                'id' => 'txtDate',
                'data-toggle'=>"tooltip",
                'data-placement'=>"top",
                'title'=>"Doit être supérieure à la date actuelle"
            ));
            ?>
        </div>
        <div class="form-group">
            <input type="hidden" name="MAX_FILE_SIZE" value="2097152">
            <?php
            echo $this->Form->input('image', array(
                'label' => 'Url de l\'image:',
                'type' => 'file',
                'id' => 'txtImage',
                'class' =>'',
                'data-toggle'=>"tooltip",
                'data-placement'=>"top",
                'title'=>"Doit être dans l'un des formats suivants: jpg, jpeg, png, gif, bmp. Taille maximale: 2 Mo"
            )); ?>
        </div>
        <?php
        echo $this->Form->input('description', array(
            'label' => 'Description:',
            'type' => 'textarea'));
        ?>

        <h3>Choix possibles</h3>

        <p>Note: Deux choix sont obligatoires. Le troisième est facultatif.</p>

        <p><a href="#" id="dNouveauChoix">Ajouter un troisième choix.</a></p>

        <ol class="list-unstyled">
            <li>
                <blockquote class="blockquote-info">
                    <div class="form-horizontal">
                        <div class="form-group">
                            <strong>Choix #1</strong>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-1 control-label">Nom:</label>

                            <div class="col-sm-11">
                                <?php
                                echo $this->Form->input('Choix.0.nom', array('label' => false, 'placeholder' => 'Nom (Obligatoire)',
                                    'required' => 'required', 'class' => 'form-control', 'div' => false));?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-1 control-label">Cote:</label>

                            <div class="col-sm-11">
                                <?php echo $this->Form->input('Choix.0.cote', array(
                                    'label' => false,
                                    'placeholder' => 'Cote (Obligatoire)',
                                    'type' => 'number',
                                    'class' => 'form-control',
                                    'div' => false,
                                    'required' => 'required',
                                    'data-toggle'=>"tooltip",
                                    'data-placement'=>"top",
                                    'title'=>"Doit être comprise entre 1.1 et 5",
                                    'id' => 'txtCote1'
                                ));
                                ?>
                            </div>
                        </div>
                    </div>
                </blockquote>
            </li>

            <li>
                <blockquote class="blockquote-info">
                    <div class="form-horizontal">
                        <div class="form-group">
                            <strong>Choix #2</strong>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-1 control-label">Nom:</label>

                            <div class="col-sm-11">
                                <?php
                                echo $this->Form->input('Choix.1.nom', array('label' => false, 'placeholder' => 'Nom (Obligatoire)',
                                    'required' => 'required', 'class' => 'form-control', 'div' => false));?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-1 control-label">Cote:</label>

                            <div class="col-sm-11">
                                <?php echo $this->Form->input('Choix.1.cote', array(
                                    'label' => false, 'placeholder' => 'Cote (Obligatoire)',
                                    'type' => 'number', 'class' => 'form-control', 'div' => false, 'required' => 'required',
                                    'data-toggle'=>"tooltip",
                                    'data-placement'=>"top",
                                    'title'=>"Doit être comprise entre 1.1 et 5",
                                    'id' => 'txtCote2'
                                ));
                                ?>
                            </div>
                        </div>
                    </div>
                </blockquote>
            </li>

            <li id="liTroisiemeChoix" style="display:none;">
                <blockquote class="blockquote-info">
                    <div class="form-horizontal">
                        <div class="form-group">
                            <strong>Choix #3</strong>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-1 control-label">Nom:</label>

                            <div class="col-sm-11">
                                <?php
                                echo $this->Form->input('Choix.2.nom', array('label' => false, 'placeholder' => 'Nom',
                                    'class' => 'form-control', 'div' => false,
                                    'id' => 'txtChoix3'));?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-1 control-label">Cote:</label>

                            <div class="col-sm-11">
                                <?php echo $this->Form->input('Choix.2.cote', array(
                                    'label' => false, 'placeholder' => 'Cote',
                                    'type' => 'number', 'class' => 'form-control', 'div' => false,
                                    'id' => 'txtCote3',
                                    'data-toggle'=>"tooltip",
                                    'data-placement'=>"top",
                                    'title'=>"Doit être comprise entre 1.1 et 5"
                                ));
                                ?>
                            </div>
                        </div>
                    </div>
                </blockquote>
            </li>

        </ol>

        <div class="clearfix"></div>

        <?php
        echo $this->Form->submit('Soumettre', array(
            'div' => false,
            'class' => 'btn btn-primary'
        ));

        echo $this->Html->link('Retour au catalogue', array('controller' => 'paris', 'action' => 'index'), array('class' => 'btn btn-default')); ?>


    </fieldset>
    <?php echo $this->Form->end(); ?>
</div>