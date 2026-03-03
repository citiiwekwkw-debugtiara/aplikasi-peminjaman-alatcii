<div class="row fade-in">
    <div class="col-md-12 mb-4">
        <h2 class="fw-bold">Ringkasan Statistik</h2>
        <p class="text-muted">Selamat datang kembali, <strong><?= $_SESSION['user']['nama']; ?></strong>.</p>
    </div>
</div>

<div class="row mb-5 fade-in">
    <div class="col-md-3 mb-3">
        <div class="card shadow-sm border-0 rounded-4 stats-card h-100">
            <div class="card-body">
                <h6 class="text-muted text-uppercase mb-2">Total Alat</h6>
                <h3 class="fw-bold text-primary mb-0"><?= $data['total_alat']; ?></h3>
            </div>
        </div>
    </div>
    
    <?php if($_SESSION['user']['role'] == 'admin') : ?>
    <div class="col-md-3 mb-3">
        <div class="card shadow-sm border-0 rounded-4 stats-card h-100">
            <div class="card-body">
                <h6 class="text-muted text-uppercase mb-2">Total User</h6>
                <h3 class="fw-bold text-info mb-0"><?= $data['total_users']; ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card shadow-sm border-0 rounded-4 stats-card h-100">
            <div class="card-body">
                <h6 class="text-muted text-uppercase mb-2">Total Kategori</h6>
                <h3 class="fw-bold text-success mb-0"><?= $data['total_kategori']; ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card shadow-sm border-0 rounded-4 stats-card h-100 bg-primary text-white">
            <div class="card-body d-flex flex-column justify-content-center align-items-center text-center">
                <h6 class="text-white-50 text-uppercase mb-2">Menu Laporan</h6>
                <a href="<?= BASEURL; ?>/peminjaman/laporan" target="_blank" class="btn btn-light btn-sm rounded-3 fw-bold">
                    <i class="bi bi-printer"></i> Cetak Sekarang
                </a>
            </div>
        </div>
    </div>
    <?php else : ?>
    <div class="col-md-3 mb-3">
        <div class="card shadow-sm border-0 rounded-4 stats-card h-100">
            <div class="card-body">
                <h6 class="text-muted text-uppercase mb-2">Peminjaman Aktif</h6>
                <h3 class="fw-bold text-warning mb-0"><?= $data['total_dipinjam']; ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card shadow-sm border-0 rounded-4 stats-card h-100">
            <div class="card-body">
                <h6 class="text-muted text-uppercase mb-2">Menunggu Persetujuan</h6>
                <h3 class="fw-bold text-success mb-0"><?= $data['total_antrean']; ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card shadow-sm border-0 rounded-4 stats-card h-100">
            <div class="card-body">
                <h6 class="text-muted text-uppercase mb-2">Total Denda</h6>
                <h3 class="fw-bold text-danger mb-0">Rp <?= number_format($data['total_denda'], 0, ',', '.'); ?></h3>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<div class="row fade-in">
    <?php if($_SESSION['user']['role'] != 'admin') : ?>
    <div class="col-md-8 mb-4">
        <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
            <div class="card-header bg-white py-3 border-0">
                <h5 class="mb-0 fw-bold">Peminjaman Terbaru</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Peminjam</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($data['recent_borrowings'])) : ?>
                            <tr>
                                <td colspan="4" class="text-center py-4">Belum ada data peminjaman.</td>
                            </tr>
                            <?php else : ?>
                                <?php foreach($data['recent_borrowings'] as $pjm) : ?>
                                <tr>
                                    <td>#<?= $pjm['id_pinjam']; ?></td>
                                    <td class="fw-bold"><?= $pjm['nama_peminjam']; ?></td>
                                    <td><?= date('d/m/Y', strtotime($pjm['tanggal_pinjam'])); ?></td>
                                    <td>
                                        <?php 
                                        $badge = 'bg-secondary';
                                        if($pjm['status'] == 'menunggu') $badge = 'bg-warning text-dark';
                                        if($pjm['status'] == 'dipinjam') $badge = 'bg-primary';
                                        if($pjm['status'] == 'dikembalikan') $badge = 'bg-success';
                                        ?>
                                        <span class="badge <?= $badge; ?>"><?= ucfirst($pjm['status']); ?></span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div class="<?= ($_SESSION['user']['role'] == 'admin') ? 'col-md-12' : 'col-md-4'; ?> mb-4">
        <div class="card shadow-sm border-0 rounded-4 h-100">
            <div class="card-header bg-white py-3 border-0">
                <h5 class="mb-0 fw-bold">Log Aktivitas</h5>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    <?php if(empty($data['recent_logs'])) : ?>
                        <li class="list-group-item text-center py-4 text-muted">Belum ada aktivitas.</li>
                    <?php else: ?>
                        <?php foreach($data['recent_logs'] as $log) : ?>
                        <li class="list-group-item py-3 px-4">
                            <div class="d-flex justify-content-between mb-1">
                                <small class="fw-bold text-primary"><?= $log['nama']; ?></small>
                                <small class="text-muted"><?= date('H:i', strtotime($log['tanggal'])); ?></small>
                            </div>
                            <p class="mb-0 small"><?= $log['aktivitas']; ?></p>
                        </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
            </div>
            <?php if(!empty($data['recent_logs'])) : ?>
            <div class="card-footer bg-white border-0 text-center py-3">
                <a href="#" class="btn btn-link btn-sm text-decoration-none">Lihat Semua Log</a>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
