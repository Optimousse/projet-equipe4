<script type="text/javascript">
    $(document).ready(function(){
       $("#dNouveauChoix").click(function(e){

           $(this).empty();
           if($("#liTroisiemeChoix").css('display') === 'none'){
               $("#liTroisiemeChoix").css('display','block');
               $(this).append('Supprimer le troisième choix.');
           }else{
               $("#liTroisiemeChoix").css('display','none');
               $(this).append('Ajouter un troisième choix.');
           }
           e.preventDefault();
       }) ;
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
                <blockquote style="border-color:#2D6CA2; background-color:#eee;">
                    <div class="form-inline">
                        <div class="form-group">
                            <strong>Choix #1</strong>
                        </div>
                        <div class="form-group">
                            <label class="sr-only">Nom</label>
                            <?php
                            echo $this->Form->input('Choix.0.nom', array('label'=>false, 'placeholder'=>'Nom (Obligatoire)',
                                'required' =>'required', 'class'=>'form-control','div'=>false));?>
                        </div>

                        <div class="form-group">
                            <label class="sr-only">Cote</label>
                            <?php echo $this->Form->input('Choix.0.cote', array(
                                'label'=>false,'placeholder'=>'Cote (Obligatoire)',
                                'type' => 'number', 'class'=>'form-control','div'=>false, 'required'=>'required'
                            ));
                            ?>
                        </div>
                    </div>
                </blockquote>
            </li>

            <li>
                <blockquote style="border-color:#2D6CA2; background-color:#eee;">
                    <div class="form-inline">
                        <div class="form-group">
                            <strong>Choix #2</strong>
                        </div>
                        <div class="form-group">
                            <label class="sr-only">Nom</label>
                            <?php
                            echo $this->Form->input('Choix.1.nom', array('label'=>false, 'placeholder'=>'Nom (Obligatoire)',
                                'required' =>'required', 'class'=>'form-control form-inline','div'=>false));?>
                        </div>

                        <div class="form-group">
                            <label class="sr-only">Cote</label>
                            <?php echo $this->Form->input('Choix.1.cote', array(
                                'label'=>false,'placeholder'=>'Cote (Obligatoire)',
                                'type' => 'number', 'class'=>'form-control form-inline','div'=>false, 'required'=>'required'
                            ));
                            ?>
                        </div>
                    </div>
                </blockquote>
            </li>

            <li id="liTroisiemeChoix" style="display:none;">
                <blockquote style="border-color:#2D6CA2; background-color:#eee;">
                    <div class="form-inline">
                        <div class="form-group">
                            <strong>Choix #3</strong>
                        </div>
                        <div class="form-group">
                            <label class="sr-only">Nom</label>
                            <?php
                            echo $this->Form->input('Choix.2.nom', array('label'=>false, 'placeholder'=>'Nom',
                                'class'=>'form-control form-inline','div'=>false));?>
                        </div>

                        <div class="form-group">
                            <label class="sr-only">Cote</label>
                            <?php echo $this->Form->input('Choix.2.cote', array(
                                'label'=>false,'placeholder'=>'Cote',
                                'type' => 'number', 'class'=>'form-control form-inline','div'=>false
                            ));
                            ?>
                        </div>
                    </div>
                </blockquote>
            </li>

        </ol>
        <?php
        echo $this->Form->submit('Soumettre', array(
            'div' => false,
            'class' => 'btn btn-primary'
        ));?>
    </fieldset>
    <?php echo $this->Form->end(); ?>
</div>