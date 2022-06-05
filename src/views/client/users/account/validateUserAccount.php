<?php declare(strict_types=1);
$this->start('head'); ?>
<!-------Costum-------->
<link href="<?= $this->asset('css/custom/client/users/account/validate', 'css') ?? ''?>" rel="stylesheet"
    type="text/css">
<?php $this->end(); ?>
<?php $this->start('body'); ?>
<main id="main-site">
    <!-- Content -->
    <h3 class="text-center pt-5">Email Verification Status</h3>
    <?=$msg; ?>
    <!-- Fin Content -->
</main>
<?php $this->end(); ?>
<?php $this->start('footer') ?>
<!----------custom--------->
<script type="text/javascript" src="<?= $this->asset('js/custom/client/users/account/validate', 'js') ?? ''?>">
</script>
<?php $this->end();