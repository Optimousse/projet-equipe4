<script type="text/javascript">
    $(document).ready(function () {
        $('#txtNombreJetons').on('keyup', function () {
            $nombreJetons = $(this).val();

            $("#divMontant").empty();
            $("#divMontant").append("Total de l'achat: " + $nombreJetons * 5 + "$");
        });

        $("#txtNombreJetons").keydown(function (e) {
            // Allow: backspace, delete, tab, escape, enter and .
            if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 190]) !== -1 ||
                // Allow: Ctrl+A
                (e.keyCode == 65 && e.ctrlKey === true) ||
                // Allow: home, end, left, right
                (e.keyCode >= 35 && e.keyCode <= 39)) {
                // let it happen, don't do anything
                return;
            }
            // Ensure that it is a number and stop the keypress
            if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                e.preventDefault();
            }
        });
    });
</script>

<?php echo $this->Form->create('Parieur'); ?>

<legend><?php echo __('Acheter des jetons'); ?></legend>

<blockquote class="blockquote-info">
    Vous possédez présentement <?php echo $nombre_jetons; ?> jetons.
    Les jetons s'achètent au coût de 5$ l'unité.
</blockquote>


<?php echo $this->Form->input('nombre_jetons', array('label' => 'Nombre de jetons:', 'class' => 'form-control', 'required' => 'required', 'type' => 'number', 'id' => 'txtNombreJetons')); ?>
<label id="divMontant"></label>
<div class="clearfix"/>
<script
    src="https://checkout.stripe.com/checkout.js" class="stripe-button"
    data-key="pk_test_eeU8Ee9Yw9SceuqaYjNrBaCt"
    data-name="Acheter des jetons"
    data-panel-label="Passer la commande"
    data-label="Acheter">
</script>

<?php echo $this->Form->end(); ?>
