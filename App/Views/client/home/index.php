<?php declare(strict_types=1);
$this->start('head'); ?>
<!-------Costum-------->
<link href="<?= $this->asset('css/custom/client/home/home', 'css') ?? ''?>" rel="stylesheet" type="text/css">
<?php $this->end(); ?>
<?php $this->start('body'); ?>
<!-- Start Main -->
<main id="main-site">
    <!-- Owl carousel -->
    <?php require_once 'partials/_banner_area.php'?>
    <!-- End Owl carousel -->
    <!-- Top Sales -->
    <?php require_once 'partials/_top_sales.php'?>
    <!-- End top Sales -->

    <!-- Special price -->
    <?php require_once 'partials/_special_price.php'?>
    <!-- End Special price -->

    <!-- Banner Adds -->
    <?php require_once 'partials/_banner_adds.php'?>
    <!-- End Banner Adds -->

    <!-- New Phones -->
    <?php require_once 'partials/_new_products.php'?>
    <!-- End New Phones -->

    <!-- Blog -->
    <?php require_once 'partials/_blog.php'?>
    <!-- End blog -->
    <input type="hidden" id="ip_address" style="display:none" value="<?=H_visitors::getIP()?>">
</main>
<!-- End  Main -->
<?php $this->end(); ?>
<?php $this->start('footer') ?>
<!----------custom--------->
<script type="text/javascript" src="<?= $this->asset('js/custom/client/home/home', 'js') ?? ''?>">
</script>
<?php $this->end();
