<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['judul']; ?> - Peminjaman Alat</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS (CDN for simplicity in UKK) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= BASEURL; ?>/assets/css/style.css">
</head>
<body class="bg-light">
<?php if(isset($_SESSION['login'])) : ?>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm mb-4">
  <div class="container">
    <a class="navbar-brand fw-bold" href="<?= BASEURL; ?>">LENDA-TOOL</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto">
        <li class="nav-item">
          <a class="nav-link active" href="<?= BASEURL; ?>/dashboard">Dashboard</a>
        </li>
        <?php if($_SESSION['user']['role'] == 'admin') : ?>
        <li class="nav-item">
          <a class="nav-link" href="<?= BASEURL; ?>/user">Manajemen User</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?= BASEURL; ?>/kategori">Kategori</a>
        </li>
        <?php endif; ?>
        <li class="nav-item">
          <a class="nav-link" href="<?= BASEURL; ?>/alat">Alat</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?= BASEURL; ?>/peminjaman">Peminjaman</a>
        </li>
        <?php if($_SESSION['user']['role'] != 'peminjam') : ?>
        <li class="nav-item">
          <a class="nav-link" href="<?= BASEURL; ?>/peminjaman/laporan" target="_blank">Laporan</a>
        </li>
        <?php endif; ?>
      </ul>
      <div class="navbar-nav align-items-center">
        <!-- Notification Dropdown -->
        <?php 
          require_once '../app/models/Notification_model.php';
          $notifModel = new Notification_model;
          $allNotifs = $notifModel->getUnreadNotif($_SESSION['user']['id_user']);
          $notifCount = count($allNotifs);
        ?>
        <div class="nav-item dropdown me-3">
          <a class="nav-link position-relative" href="#" id="notifDropdown" role="button" data-bs-toggle="dropdown">
            <i class="bi bi-bell fs-5"></i>
            <?php if($notifCount > 0) : ?>
              <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;">
                <?= $notifCount; ?>
              </span>
            <?php endif; ?>
          </a>
          <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-4 p-2 mt-2" style="width: 300px;">
            <li class="px-2 py-1"><h6 class="dropdown-header px-0 fw-bold">Notifikasi Terbaru</h6></li>
            <li><hr class="dropdown-divider"></li>
            <?php if(empty($allNotifs)) : ?>
              <li class="px-3 py-3 text-center text-muted small">Tidak ada notifikasi baru</li>
            <?php else : ?>
              <?php foreach(array_slice($allNotifs, 0, 5) as $n) : ?>
                <li class="px-2 py-1">
                  <div class="dropdown-item rounded-3 p-2 bg-light mb-1">
                    <div class="d-flex align-items-center">
                      <div class="flex-shrink-0">
                        <i class="bi bi-info-circle text-primary me-2"></i>
                      </div>
                      <div class="flex-grow-1 overflow-hidden">
                        <small class="d-block text-truncate fw-semibold"><?= $n['pesan']; ?></small>
                        <small class="text-muted" style="font-size: 0.7rem;"><?= date('d M, H:i', strtotime($n['created_at'])); ?></small>
                      </div>
                    </div>
                  </div>
                </li>
              <?php endforeach; ?>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item text-center small text-primary fw-bold" href="<?= BASEURL; ?>/notification/markAllRead">Tandai Semua Sudah Dibaca</a></li>
            <?php endif; ?>
          </ul>
        </div>

        <span class="nav-item nav-link text-white me-3">Halo, <strong><?= $_SESSION['user']['nama']; ?></strong> (<?= ucfirst($_SESSION['user']['role']); ?>)</span>
        <a class="btn btn-outline-light btn-sm rounded-pill px-3" href="<?= BASEURL; ?>/auth/logout">Keluar</a>
      </div>
    </div>
  </div>
</nav>
<?php endif; ?>

<div class="container mt-3">
    <div class="row">
        <div class="col-sm-12">
            <?php Flasher::flash(); ?>
        </div>
    </div>
