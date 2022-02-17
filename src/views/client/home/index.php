<?php declare(strict_types=1);
$this->start('head'); ?>
<!-------Costum-------->
<link href="<?= $this->asset('css/custom/client/home/home', 'css') ?? ''?>" rel="stylesheet" type="text/css">
<?php $this->end(); ?>
<?php $this->start('body'); ?>
<!-- Start Main -->
<main id="main-site">
<?=$table?>
<?=$pagination?>
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
