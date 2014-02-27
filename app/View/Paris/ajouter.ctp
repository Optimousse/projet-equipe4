<div class="users form">
    <?php
    echo $this->Form->create('Pari', array('class'=>'well')); ?>
    <fieldset>
        <legend><?php echo __('Ajouter un pari'); ?></legend>
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
            'selected' => $sDate
            ));
        ?>

        <h4>Choix possibles</h4>
        <p>Note: Deux choix sont obligatoires. Le troisième est facultatif.</p>
        <ol>
            <li>
                <?php
                    echo $this->Form->input('Choix.0.nom', array('label'=>false, 'placeholder'=>'Nom (Obligatoire)',
                        'required' =>'required', 'class'=>'form-inline','div'=>false));

                    echo $this->Form->input('Choix.0.cote', array(
                        'label'=>false,'placeholder'=>'Cote (Obligatoire)',
                        'type' => 'number', 'class'=>'form-inline','div'=>false, 'required'=>'required'
                    ));
                ?>
            </li>
            <li>
                <?php
                    echo $this->Form->input('Choix.1.nom', array('label'=>false, 'placeholder'=>'Nom (Obligatoire)',
                        'required' =>'required', 'class'=>'form-inline','div'=>false));

                    echo $this->Form->input('Choix.1.cote', array(
                        'label'=>false,'placeholder'=>'Cote (Obligatoire)',
                        'type' => 'number', 'class'=>'form-inline','div'=>false, 'required'=>'required'
                    ));
                ?>
            </li>
            <li>
                <?php
                    echo $this->Form->input('Choix.2.nom', array('label'=>false, 'placeholder'=>'Nom',
                        'class'=>'form-inline','div'=>false));

                    echo $this->Form->input('Choix.2.cote', array(
                        'label'=>false,'placeholder'=>'Cote',
                        'type' => 'number', 'class'=>'form-inline','div'=>false
                    ));
                ?>
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