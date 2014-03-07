<script type="text/javascript">
    $(document).ready(function () {

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
        'role' => 'form')); ?>
    <fieldset>
        <?php
        echo $this->Form->input('parieur_id', array('type' => 'hidden', 'value' => $id_util));
        echo $this->Form->input('nom', array(
            'label' => 'Nom du pari:'));
        echo $this->Form->input('description', array(
                'label' => 'Description:',
                'type' => 'textarea')
        );
        echo $this->Form->input('image', array(
            'label' => 'Image:'));

        //Date par défaut pour l'input: dans une semaine
        $dateactuelle = date_add(new DateTime('now'), new DateInterval('P7D'));
        $sDate = $dateactuelle->format('Y-m-d');
        echo $this->Form->input('date_fin', array(
            'label' => 'Se termine le:',
            'type' => 'date',
            'selected' => $sDate,
            'class' => 'form-control'
        ));
        ?>

        <h3>Choix possibles</h3>

        <p>Note: Deux choix sont obligatoires. Le troisième est facultatif.</p>

        <p><a href="#" id="dNouveauChoix">Ajouter un troisième choix.</a></p>

        <ol class="list-unstyled">
            <li>
                <blockquote style="border-color:#2D6CA2; padding-bottom:1px; background-color:#eee;">
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
                                    'label' => false, 'placeholder' => 'Cote (Obligatoire)',
                                    'type' => 'number', 'class' => 'form-control', 'div' => false, 'required' => 'required'
                                ));
                                ?>
                            </div>
                        </div>
                    </div>
                </blockquote>
            </li>

            <li>
                <blockquote style="border-color:#2D6CA2; padding-bottom:1px; background-color:#eee;">
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
                                    'type' => 'number', 'class' => 'form-control', 'div' => false, 'required' => 'required'
                                ));
                                ?>
                            </div>
                        </div>
                    </div>
                </blockquote>
            </li>

            <li id="liTroisiemeChoix" style="display:none;">
                <blockquote style="border-color:#2D6CA2; padding-bottom:1px; background-color:#eee;">
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
                                    'id' => 'txtCote3'
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