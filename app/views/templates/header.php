<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['judul']; ?> - LENDA-TOOL</title>
    <!-- Bootstrap 5.3.0 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Google Fonts: Outfit -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= BASEURL; ?>/assets/css/style.css">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="<?= BASEURL; ?>">
                <i class="bi bi-tools me-2"></i> LENDA-TOOL
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <?php if (isset($_SESSION['user'])) : ?>
                        <li class="nav-item mx-lg-2">
                            <a class="nav-link <?= ($data['judul'] == 'Dashboard') ? 'active fw-bold' : ''; ?>" href="<?= BASEURL; ?>/dashboard">Dashboard</a>
                        </li>
                        
                        <?php if($_SESSION['user']['role'] == 'admin') : ?>
                        <li class="nav-item mx-lg-2">
                            <a class="nav-link <?= ($data['judul'] == 'Manajemen User') ? 'active fw-bold' : ''; ?>" href="<?= BASEURL; ?>/user">User</a>
                        </li>
                        <li class="nav-item mx-lg-2">
                            <a class="nav-link <?= ($data['judul'] == 'Kategori') ? 'active fw-bold' : ''; ?>" href="<?= BASEURL; ?>/kategori">Kategori</a>
                        </li>
                        <?php endif; ?>

                        <li class="nav-item mx-lg-2">
                            <a class="nav-link <?= ($data['judul'] == 'Daftar Alat') ? 'active fw-bold' : ''; ?>" href="<?= BASEURL; ?>/alat">Alat</a>
                        </li>
                        <li class="nav-item mx-lg-2">
                            <a class="nav-link <?= ($data['judul'] == 'Peminjaman') ? 'active fw-bold' : ''; ?>" href="<?= BASEURL; ?>/peminjaman">Peminjaman</a>
                        </li>

                        <?php if($_SESSION['user']['role'] != 'peminjam') : ?>
                        <li class="nav-item mx-lg-2">
                            <a class="nav-link" href="<?= BASEURL; ?>/peminjaman/laporan" target="_blank">Laporan</a>
                        </li>
                        <?php endif; ?>

                        <!-- Notifications -->
                        <?php 
                        require_once '../app/models/Notification_model.php';
                        $notifModel = new Notification_model;
                        $allNotifs = $notifModel->getUnreadNotif($_SESSION['user']['id_user']);
                        $notifCount = count($allNotifs);
                        ?>
                        <li class="nav-item ms-lg-2 dropdown">
                            <a class="nav-link dropdown-toggle position-relative" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-bell fs-5"></i>
                                <?php if($notifCount > 0) : ?>
                                <span class="position-absolute translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem; top: 10px; left: 85%;">
                                    <?= $notifCount; ?>
                                </span>
                                <?php endif; ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2 rounded-3 p-2" style="width: 250px;">
                                <li class="px-2 py-1"><h6 class="dropdown-header px-0 fw-bold">Notifikasi Baru</h6></li>
                                <li><hr class="dropdown-divider"></li>
                                <?php if(empty($allNotifs)) : ?>
                                    <li class="px-3 py-2 text-center text-muted small">Tidak ada notifikasi</li>
                                <?php else : ?>
                                    <?php foreach(array_slice($allNotifs, 0, 5) as $n) : ?>
                                    <li class="px-2 mb-1">
                                        <div class="px-2 py-1 rounded-2 hover-bg-light">
                                            <div class="small fw-bold text-truncate"><?= $n['pesan']; ?></div>
                                            <div class="text-muted" style="font-size: 0.7rem;"><?= date('H:i', strtotime($n['created_at'])); ?></div>
                                        </div>
                                    </li>
                                    <?php endforeach; ?>
                                    <?php if($notifCount > 0) : ?>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-center small text-primary fw-bold" href="<?= BASEURL; ?>/notification/markAllRead">Tandai Semua Dibaca</a></li>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </ul>
                        </li>

                        <li class="nav-item ms-lg-3 dropdown">
                            <a class="nav-link dropdown-toggle btn btn-light text-primary px-3 rounded-pill fw-semibold" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle me-1"></i> <?= $_SESSION['user']['nama']; ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2 rounded-3">
                                <li><a class="dropdown-item py-2" href="<?= BASEURL; ?>/auth/logout"><i class="bi bi-box-arrow-right me-2 text-danger"></i> Logout</a></li>
                            </ul>
                        </li>
                    <?php else : ?>
                        <li class="nav-item">
                            <a class="nav-link btn btn-light text-primary px-4 rounded-pill fw-bold" href="<?= BASEURL; ?>/auth/login">Masuk</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row">
            <div class="col-lg-12">
                <?php Flasher::flash(); ?>
            </div>
        </div>
