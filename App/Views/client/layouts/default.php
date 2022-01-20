<?php declare(strict_types=1);
require_once 'inc/default/header.php'; ?>
<!----------------Navbar-------------------->
<?php require_once 'inc/default/nav.php'?>
<!----------------xNavbar-------------------->

<!----------------Body----------------------->
<?= $this->content('body'); ?>
<!----------------xBody---------------------->
<!----------------Modals-------------------->
<?php require_once 'inc/default/modal.php'; ?>
<!----------------xModals-------------------->

<?php require_once 'inc/default/footer.php';
