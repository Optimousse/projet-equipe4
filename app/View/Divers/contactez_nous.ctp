<h1>Contactez-nous</h1>

<blockquote class="blockquote-info">
    Commentaires, questions ? Écrivez-nous un courriel et nous tâcherons de vous répondre dans les plus brefs délais !
</blockquote>

<?php echo $this->Form->create('Contact');

    echo $this->Form->input('courriel', array(
        'label' => 'Votre adresse courriel:',
        'type' => 'email',
        'class' => 'form-control'
    ));

    echo $this->Form->input('titre', array(
        'label' => 'Titre du message:',
        'type' => 'text',
        'class' => 'form-control'
    ));

    echo $this->Form->input('message', array(
        'label' => 'Votre message:',
        'class' => 'form-control',
        'type' => 'textarea'));

echo $this->Form->submit('Envoyer', array(
    'div' => false,
    'class' => 'btn btn-primary'
));

echo $this->Html->link('Retour à la FAQ', array('controller' => 'divers', 'action' => 'faq'), array('class' => 'btn btn-default btn-separation'));
echo $this->Form->end();
?>