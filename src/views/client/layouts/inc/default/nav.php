<!-- Start Header -->
<header id="header" class="fixed-top">
    <div class="strip d-flex justify-content-between px-4 py-1 bg-light">
        <?php $settings = $this->getProperty('settings'); if (isset($settings) && !empty($settings)) :?>
        <p class="font-rale font-size-12 text-black-50 m-0">
            <?=$settings->site_address?>
            .
        </p>&nbsp;
        <p class="font-rale font-size-12 text-black-50 me-auto">Téléphone : <?=$settings->site_phone?>
        </p>
        <?php endif; ?>
        <div class="font-rale font-size-14 left-side">
            <?=$search_box?>
            <div class="connect">
                <?php if (!AuthManager::isLoggedIn()) : ?>
                <button type="button" class="px-3 border-right border-left text-dark connexion text-decoration-none"
                    data-bs-toggle="modal" data-bs-target="#login-box" id="login_btn">
                    <span class="icon login"></span>&nbsp;&nbsp;Login</button>
                <?php else : ?>
                <a class="dropdown-toggle px-3 border-right border-left text-dark connexion text-decoration-none"
                    id="navbarDropdownMenuLink" data-bs-toggle="dropdown" role="button">
                    <span class="icon login"></span>&nbsp;&nbsp;<?= 'Bonjour&nbsp;' . AuthManager::user(); ?>
                </a>
                <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                    <?php $drop = GrantAccess::getInstance()->getMenu('menu_acl', 'log_reg_menu')?>
                    <?php
                    foreach ($drop as $k => $v) :
                    $active = ($v == H::currentPage()) ? 'active' : ''; ?>
                    <?php if ($k == 'separator') : ?>
                    <li role="separator" class="dropdown-divider"></li>
                    <?php elseif ($k == 'Confirmez votre compte') : ?>
                    <li class="dropdown-item nav-item <?= $active ?> alert alert-warning">
                        <a class="nav-link text-danger" href="<?= ($k != 'Logout') ? $v : 'javascript:void(0)' ?>">
                            <?= $k ?>
                        </a>
                    </li>
                    <?php else : ?>
                    <li class="dropdown-item nav-item <?= $active ?>">
                        <a class="nav-link" href="<?= ($k != 'Logout') ? $v : 'javascript:void(0)' ?>">
                            <?= $k ?>
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
                <?php endif; ?>
                <a href="#" class="px-3 border-right text-dark text-decoration-none">Whishlist(0)</a>
            </div>
            <form action="#" class="font-size-14 font-rale" id="shopping-cart">
                <a href="<?= PROOT ?>cart" class="py-2 rounded-pill color-primary-bg">
                    <span class="font-size-16 px-2 text-white">
                        <!-- <i class="fa fa-shopping-cart"></i> -->
                        <span class="shopping-cart-icon"></span>
                    </span>
                    <span class="px-3 py-2 rounded-pill text-dark bg-light cart_nb_elt"><?=isset($this->user_cart) && is_array($this->user_cart[0]) ? count(array_filter($this->user_cart[0], function ($item) {
                        return $item->c_content == 'cart';
                    })) : 0?></span>
                </a>
                <a href="#"></a>
            </form>
        </div>
    </div>
    <!-- Primary navigation -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand" href="<?= $this->route('/')?>"><img src="../../../../../assets/img/logo1.png"
                    alt="Logo"></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#main-menu"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="bar"><i class="far fa-bars"></i></span>
            </button>
            <div class="collapse navbar-collapse" id="main-menu">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="<?=DS?>">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?=PROOT . 'clothing'?>">Promo</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?=PROOT . 'boutique'?>">Shop</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Blog</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?=PROOT . 'contact'?>">Contact
                            Us</a>
                    </li>

                </ul>

            </div>
        </div>
    </nav>
    <!-- End Primary navigation -->
</header>
<!-- End Header -->