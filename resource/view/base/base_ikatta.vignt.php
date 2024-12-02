
<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default"
    data-assets-path="layout/assets/" data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <meta name="description" content="" />

    <link rel="icon" type="image/x-icon" href="layout/assets/img/favicon/favicon.ico" />

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="layout/assets/vendor/fonts/boxicons.css" />
    <link rel="stylesheet" href="layout/assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="layout/assets/vendor/css/theme-default.css"
        class="template-customizer-theme-css" />
    <link rel="stylesheet" href="layout/assets/css/demo.css" />
    <link rel="stylesheet" href="layout/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <script src="layout/assets/vendor/js/helpers.js"></script>
    <script src="layout/assets/js/config.js"></script>
</head>

<body>
    <!-- <div class="loader">
        <div></div>
    </div> -->
    <div class="layout-wrapper layout-content-navbar content">
        <div class="layout-container">
            <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
                <div class="app-brand demo">
                    <div class="app-brand-link">
                        <span class="app-brand-logo demo">
                            <img src="layout/assets/img/favicon/piggy-bank.png" alt="logo">
                        </span>
                        <span class="app-brand-text demo menu-text fw-bold ms-2">Ikatta</span>
                    </div>
                    <a href="javascript:void(0);"
                        class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
                        <i class="bx bx-chevron-left bx-sm align-middle"></i>
                    </a>
                </div>

                <div class="menu-inner-shadow"></div>
                <ul class="menu-inner py-1">
                    <li class="menu-item <?php if ($active == 'home') {echo 'active';}?>">
                        <a href="/home" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-home"></i>
                            <div data-i18n="Basic">Home</div>
                        </a>
                    </li>
                
                    <li class="menu-item <?php if ($active == 'in') {echo 'active';}?>">
                        <a href="/in" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-dollar-circle"></i>
                            <div data-i18n="Basic">Pemasukan</div>
                        </a>
                    </li>

                    <li class="menu-header small text-uppercase"><span class="menu-header-text">Pengeluaran</span></li>

                    <li class="menu-item <?php if ($active == 'out') {echo 'active';}?>">
                        <a href="/out" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-right-top-arrow-circle"></i>
                            <div data-i18n="Basic">Keseluruhan</div>
                        </a>
                    </li>
                    <li class="menu-item <?php if ($active == 'out-label') {echo 'active';}?>">
                        <a href="/out-label" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-right-top-arrow-circle"></i>
                            <div data-i18n="Basic">Per Label</div>
                        </a>
                    </li>

                    <li class="menu-header small text-uppercase"><span class="menu-header-text">Riwayat Transaksi</span></li>
                    <li class="menu-item <?php if ($active == 'monthly') {echo 'active';}?>">
                        <a href="/monthly" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-calendar"></i>
                            <div data-i18n="Basic">Transaksi Bulanan</div>
                        </a>
                    </li>
                </ul>
            </aside>
            <div class="layout-page">
                <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
                    id="layout-navbar">
                    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
                        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                            <i class="bx bx-menu bx-sm"></i>
                        </a>
                    </div>

                    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
                        <h6 class="py-3 mt-3 navbar-text me-sm-3"><i class="bx {! $icon !}"></i> {! $menu !}</h6>

                        <ul class="navbar-nav flex-row align-items-center ms-auto">
                            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);"
                                    data-bs-toggle="dropdown">
                                    <div class="avatar avatar-online">
                                        <img src="layout/assets/img/avatars/user.png" alt
                                            class="w-px-35 h-auto rounded-circle" />
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="#">
                                            <div class="avatar mx-auto mb-2">
                                                <img src="layout/assets/img/avatars/user.png" alt class="w-px-25 h-auto rounded-circle" />
                                            </div>
                                            <span class="fw-semibold d-block text-center">{! 'pp' !}</span>
                                        </a>
                                    </li>
                                    <li>
                                        <div class="dropdown-divider"></div>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="auth-login-basic.html">
                                            <i class="bx bx-power-off me-2"></i>
                                            <span class="align-middle">Log Out</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </nav>

                !@require_content

            </div>
        </div>
        <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <script src="layout/assets/vendor/libs/jquery/jquery.js"></script>
    <script src="layout/assets/vendor/libs/popper/popper.js"></script>
    <script src="layout/assets/vendor/js/bootstrap.js"></script>
    <script src="layout/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="layout/assets/vendor/js/menu.js"></script>
    <script src="layout/assets/js/main.js"></script>
    <script async defer src="https://buttons.github.io/buttons.js"></script>
</body>

</html>