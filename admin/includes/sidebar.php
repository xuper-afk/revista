<nav id="sidebar" class="sidebar js-sidebar">
    <div class="sidebar-content js-simplebar">
        <a class="sidebar-brand" href="<?= baseUrl('admin/dashboard.php') ?>">
            <span class="align-middle">Revista Digital PJ</span>
        </a>
        <ul class="sidebar-nav">
            <li class="sidebar-header">Contenido</li>

            <li class="sidebar-item <?= ($seccionActiva ?? '') === 'dashboard' ? 'active' : '' ?>">
                <a class="sidebar-link" href="<?= baseUrl('admin/dashboard.php') ?>">
                    <i class="align-middle" data-feather="home"></i> <span class="align-middle">Dashboard</span>
                </a>
            </li>
            <li class="sidebar-item <?= ($seccionActiva ?? '') === 'reportajes' ? 'active' : '' ?>">
                <a class="sidebar-link" href="<?= baseUrl('admin/reportajes/index.php') ?>">
                    <i class="align-middle" data-feather="file-text"></i> <span class="align-middle">Reportajes</span>
                </a>
            </li>
            <li class="sidebar-item <?= ($seccionActiva ?? '') === 'noticias' ? 'active' : '' ?>">
                <a class="sidebar-link" href="<?= baseUrl('admin/noticias/index.php') ?>">
                    <i class="align-middle" data-feather="zap"></i> <span class="align-middle">Noticias</span>
                </a>
            </li>
            <li class="sidebar-item <?= ($seccionActiva ?? '') === 'boletines' ? 'active' : '' ?>">
                <a class="sidebar-link" href="<?= baseUrl('admin/boletines/index.php') ?>">
                    <i class="align-middle" data-feather="book"></i> <span class="align-middle">Boletines</span>
                </a>
            </li>
            <li class="sidebar-item <?= ($seccionActiva ?? '') === 'podcasts' ? 'active' : '' ?>">
                <a class="sidebar-link" href="<?= baseUrl('admin/podcasts/index.php') ?>">
                    <i class="align-middle" data-feather="mic"></i> <span class="align-middle">Podcasts</span>
                </a>
            </li>
            <li class="sidebar-item <?= ($seccionActiva ?? '') === 'videos' ? 'active' : '' ?>">
                <a class="sidebar-link" href="<?= baseUrl('admin/videos/index.php') ?>">
                    <i class="align-middle" data-feather="video"></i> <span class="align-middle">Videos</span>
                </a>
            </li>

            <li class="sidebar-header">Administración</li>

            <li class="sidebar-item <?= ($seccionActiva ?? '') === 'autores' ? 'active' : '' ?>">
                <a class="sidebar-link" href="<?= baseUrl('admin/autores/index.php') ?>">
                    <i class="align-middle" data-feather="users"></i> <span class="align-middle">Autores</span>
                </a>
            </li>
            <li class="sidebar-item <?= ($seccionActiva ?? '') === 'usuarios' ? 'active' : '' ?>">
                <a class="sidebar-link" href="<?= baseUrl('admin/usuarios/index.php') ?>">
                    <i class="align-middle" data-feather="user-check"></i> <span class="align-middle">Usuarios</span>
                </a>
            </li>
            <li class="sidebar-item <?= ($seccionActiva ?? '') === 'suscriptores' ? 'active' : '' ?>">
                <a class="sidebar-link" href="<?= baseUrl('admin/suscriptores/index.php') ?>">
                    <i class="align-middle" data-feather="mail"></i> <span class="align-middle">Suscriptores</span>
                </a>
            </li>
        </ul>
    </div>
</nav>

<div class="main">
    <nav class="navbar navbar-expand navbar-light navbar-bg">
        <a class="sidebar-toggle js-sidebar-toggle"><i class="hamburger align-self-center"></i></a>
        <div class="navbar-collapse collapse">
            <ul class="navbar-nav navbar-align">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-none d-sm-inline-block" href="#" data-bs-toggle="dropdown">
                        <span class="text-dark"><?= htmlspecialchars($_SESSION['usuario_nombre']) ?></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end">
                        <a class="dropdown-item" href="<?= baseUrl('admin/logout.php') ?>">Cerrar sesión</a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
    <main class="content">
        <div class="container-fluid p-0"></div>