<script type="text/javascript">
    $(document).ready(function(){
        $('.datepicker').datepicker()
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
            'type' => 'text',
                'class' => 'datepicker form-control'
            ));
        ?>

        <h4>Choix possibles</h4>
        <p>Note: Deux choix sont obligatoires. Le troisième est facultatif.</p>
        <ol>
            <li>
                <div class="form-group">
                <?php
                    echo $this->Form->input('Choix.0.nom', array('label'=>false, 'placeholder'=>'Nom (Obligatoire)',
                        'required' =>'required', 'class'=>'form-control','div'=>false));

                    echo $this->Form->input('Choix.0.cote', array(
                        'label'=>false,'placeholder'=>'Cote (Obligatoire)',
                        'type' => 'number', 'class'=>'form-control','div'=>false, 'required'=>'required'
                    ));
                ?>
                </div>
            </li>
            <li>
                <div class="form-group">
                <?php
                    echo $this->Form->input('Choix.1.nom', array('label'=>false, 'placeholder'=>'Nom (Obligatoire)',
                        'required' =>'required', 'class'=>'form-control form-inline','div'=>false));

                    echo $this->Form->input('Choix.1.cote', array(
                        'label'=>false,'placeholder'=>'Cote (Obligatoire)',
                        'type' => 'number', 'class'=>'form-control form-inline','div'=>false, 'required'=>'required'
                    ));
                ?>
                </div>
            </li>
            <li>
                <div class="form-group">
                <?php
                    echo $this->Form->input('Choix.2.nom', array('label'=>false, 'placeholder'=>'Nom',
                        'class'=>'form-control form-inline','div'=>false));

                    echo $this->Form->input('Choix.2.cote', array(
                        'label'=>false,'placeholder'=>'Cote',
                        'type' => 'number', 'class'=>'form-control form-inline','div'=>false
                    ));
                ?>
            </div>
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