<?php declare(strict_types=1);
$this->start('head'); ?>
<!-------Accueil-------->
<link href="<?= $this->asset('css/custom/client/brand/phones/product/product', 'css') ?? ''?>" rel="stylesheet"
    type="text/css">
<?php $this->end(); ?>
<?php $this->start('body'); ?>
<!-- Start Main -->
<main id="main-site">
    <!-- Products -->
    <?= $singleProduct ?? ''?>
    <!-- end Products -->
    <!-- Top Sales -->
    <?= $topSales ?? '' ?>
    <!-- End top Sales -->
    <input type="hidden" id="ip_address" style="display:none" value="<?=H_visitors::getIP()?>">
</main>
<!-- End  Main -->
<?php $this->end(); ?>
<?php $this->start('footer')?>
<!-- Html visitors -->
<script type="text/javascript" src="<?= $this->asset('js/custom/client/brand/phones/product/product', 'js') ?? ''?>">
</script>
<?php $this->end();