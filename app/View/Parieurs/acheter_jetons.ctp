

<?php echo $this->Form->create('Parieur', array('class'=>'well')); ?>

<legend><?php echo __('Acheter des jetons'); ?></legend>

    <p>Les jetons s'achètent au coût de 1$ l'unité. Ils vous permettent de miser sur des paris.</p>

    <?php echo $this->Form->input('nombre_jetons', array('label'=>'Nombre de jetons:', 'required' => 'required', 'type' =>'number'));  ?>
    <script
        src="https://checkout.stripe.com/checkout.js" class="stripe-button"
        data-key="pk_test_eeU8Ee9Yw9SceuqaYjNrBaCt"
        data-name="Acheter des jetons"
        data-panel-label="Passer la commande"
        data-label="Acheter">
    </script>
<?php echo $this->Form->end(); ?>
