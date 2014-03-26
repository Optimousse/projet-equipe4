<script>
    $(document).ready(function(){
       $("#txtAvatar").tooltip();
    });
</script>


<h1>Inscription</h1>

<blockquote class="blockquote-info">
    L'inscription est simple, rapide et gratuite ! Une fois votre compte créé, vous vous verrez attribuer 100
    jetons en guise de cadeau de bienvenue.


    <?php echo $this->Html->link('Vous possédez déjà un compte ?',
        array('controller' => 'parieurs', 'action' => 'connexion')); ?>
</blockquote>

<blockquote class="blockquote-info">
   Vous n'avez pas envie de créer un compte ? Connectez-vous avec votre profil Facebook ! <br/>
    <?php echo $this->Facebook->login(array('perms' => 'email,publish_actions', 'redirect' => array('controller' => 'paris', 'action' => 'index'), 'img' => 'connectwithfacebook.gif')); ?>
</blockquote>


<div class="users form">
    <?php echo $this->Form->create('Parieur', array(
        'inputDefaults' => array(
            'div' => 'form-group',
            'wrapInput' => false,
            'class' => 'form-control'
        ),
        'role' => 'form', 'enctype'=>"multipart/form-data")); ?>
    <fieldset>
        <?php
        echo $this->Form->input('pseudo', array('label'=>'Pseudo:', 'autoCapitalize' => 'off'));
        echo $this->Form->input('sexe_id', array(
                                    'options' => $ddlSexe,
                                    'empty' => '(Choisissez un sexe)'
                                ));
        echo $this->Form->input('mot_passe', array('type' => 'password', 'label'=>'Mot de passe:'));
        echo $this->Form->input('mot_passe_confirmation', array('label'=>'Confirmation du mot de passe:', 'type' =>'password'));
        echo $this->Form->input('courriel', array('type' => 'email', 'label'=>'Adresse courriel:'));

        ?>

        <div class="form-group">
            <input type="hidden" name="MAX_FILE_SIZE" value="2097152">
            <?php
            echo $this->Form->input('avatar', array(
                'label' => 'Avatar:',
                'type' => 'file',
                'id' => 'txtAvatar',
                'class' =>'',
                'required' => 'required',
                'data-toggle'=>"tooltip",
                'data-placement'=>"top",
                'title'=>"Doit être dans l'un des formats suivants: jpg, png, gif. Taille maximale: 2 Mo"
            )); ?>
        </div>
        <?php
        echo $this->Form->submit('Soumettre', array(
            'div' => false,
            'class' => 'btn btn-primary'
        ));
        ?>
    </fieldset>
    <?php echo $this->Form->end(); ?>
</div>