<div class="row mb-3 fade-in">
    <div class="col-md-6">
        <h2 class="fw-bold">Manajemen Peminjaman</h2>
    </div>
    <div class="col-md-6 text-end">
        <?php if($_SESSION['user']['role'] != 'peminjam') : ?>
        <a href="<?= BASEURL; ?>/peminjaman/laporan" target="_blank" class="btn btn-outline-primary rounded-3 shadow-sm me-2">
            <i class="bi bi-printer"></i> Cetak Laporan
        </a>
        <?php endif; ?>

        <?php if($_SESSION['user']['role'] == 'peminjam') : ?>
        <button type="button" class="btn btn-primary rounded-3 shadow-sm tombolTambahData" data-bs-toggle="modal" data-bs-target="#pinjamModal">
            <i class="bi bi-file-earmark-plus"></i> Ajukan Peminjaman
        </button>
        <?php endif; ?>
    </div>
</div>

<div class="row mb-2 fade-in">
    <div class="col-md-12">
        <div class="alert alert-info border-0 rounded-4 shadow-sm py-2 px-3 d-flex align-items-center" role="alert">
            <i class="bi bi-info-circle-fill me-2 fs-5"></i>
            <div>
                <strong>Informasi:</strong> Denda keterlambatan adalah <strong>Rp 5.000 / hari</strong> dari tanggal seharusnya kembali.
            </div>
        </div>
    </div>
</div>

<div class="row fade-in">
    <div class="col-md-12">
        <?php Flasher::flash(); ?>
        
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama Peminjam</th>
                                <th>Tgl Pinjam</th>
                                <th>Tgl Kembali (Est)</th>
                                <th>Denda</th>
                                <th>Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; foreach($data['pinjam'] as $pjm) : ?>
                            <tr>
                                <td><?= $i++; ?></td>
                                 <td><span class="fw-bold"><?= $pjm['nama_peminjam']; ?></span></td>
                                <td><?= date('d M Y', strtotime($pjm['tanggal_pinjam'])); ?></td>
                                <td><?= date('d M Y', strtotime($pjm['tanggal_kembali'])); ?></td>
                                <td>
                                    <?php if($pjm['status'] == 'dikembalikan') : ?>
                                        <span class="text-<?= ($pjm['denda'] > 0) ? 'danger fw-bold' : 'success'; ?>">
                                            Rp <?= number_format($pjm['denda'], 0, ',', '.'); ?>
                                        </span>
                                    <?php else : ?>
                                        -
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php 
                                    $badgeClass = 'bg-secondary';
                                    if($pjm['status'] == 'menunggu') $badgeClass = 'bg-warning text-dark';
                                    if($pjm['status'] == 'disetujui') $badgeClass = 'bg-info text-white';
                                    if($pjm['status'] == 'dipinjam') $badgeClass = 'bg-primary';
                                    if($pjm['status'] == 'dikembalikan') $badgeClass = 'bg-success';
                                    ?>
                                    <span class="badge <?= $badgeClass; ?>"><?= ucfirst($pjm['status']); ?></span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group rounded-3 overflow-hidden">
                                        <?php if($pjm['status'] == 'menunggu' && $_SESSION['user']['role'] != 'peminjam') : ?>
                                            <a href="<?= BASEURL; ?>/peminjaman/setujui/<?= $pjm['id_pinjam']; ?>" class="btn btn-success btn-sm">Setujui</a>
                                        <?php endif; ?>

                                        <?php if($pjm['status'] == 'disetujui' && $_SESSION['user']['role'] != 'peminjam') : ?>
                                            <a href="<?= BASEURL; ?>/peminjaman/proses_pinjam/<?= $pjm['id_pinjam']; ?>" class="btn btn-primary btn-sm">Serahkan Barang</a>
                                        <?php endif; ?>

                                        <?php if($pjm['status'] == 'dipinjam') : ?>
                                            <a href="<?= BASEURL; ?>/peminjaman/kembalikan/<?= $pjm['id_pinjam']; ?>" class="btn btn-warning btn-sm text-dark">Kembalikan</a>
                                        <?php endif; ?>
                                        
                                         <a href="<?= BASEURL; ?>/peminjaman/detail/<?= $pjm['id_pinjam']; ?>" class="btn btn-light btn-sm">Detail</a>
                                         
                                         <?php if($pjm['status'] == 'menunggu' && ($_SESSION['user']['role'] == 'admin' || $_SESSION['user']['role'] == 'petugas')) : ?>
                                             <a href="<?= BASEURL; ?>/peminjaman/ubah/<?= $pjm['id_pinjam']; ?>" class="btn btn-warning btn-sm tampilModalUbah" data-bs-toggle="modal" data-bs-target="#pinjamModal" data-id="<?= $pjm['id_pinjam']; ?>">Ubah</a>
                                         <?php endif; ?>
                                     </div>
                                 </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Ajukan Peminjaman -->
<div class="modal fade" id="pinjamModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Form Peminjaman</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= BASEURL; ?>/peminjaman/ajukan" method="post">
                <input type="hidden" name="id_pinjam" id="id_pinjam">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label">Nama Peminjam</label>
                        <input type="text" class="form-control" name="nama_peminjam" id="nama_peminjam" required placeholder="Ketik nama peminjam...">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Pilih Alat</label>
                        <select class="form-select" name="id_alat" id="id_alat" required>
                            <option value="">-- Pilih Alat --</option>
                            <?php foreach($data['alat_tersedia'] as $alt) : ?>
                                <option value="<?= $alt['id_alat']; ?>"><?= $alt['nama_alat']; ?> (Stok: <?= $alt['stok']; ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jumlah</label>
                        <input type="number" class="form-control" name="jumlah" id="jumlah" min="1" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Pinjam</label>
                            <input type="date" class="form-control" name="tanggal_pinjam" value="<?= date('Y-m-d'); ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Kembali</label>
                            <input type="date" class="form-control" name="tanggal_kembali" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light rounded-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-3 px-4">Ajukan Peminjaman</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(function() {
    $('.tombolTambahData').on('click', function() {
        $('#pinjamModal h5').html('Form Peminjaman');
        $('.modal-footer button[type=submit]').html('Ajukan Peminjaman');
        $('.modal-content form').attr('action', '<?= BASEURL; ?>/peminjaman/ajukan');
        
        $('#id_pinjam').val('');
        $('#id_alat').val('');
        $('#jumlah').val('');
        $('#nama_peminjam').val('<?= ($_SESSION['user']['role'] == 'peminjam') ? $_SESSION['user']['nama'] : ''; ?>');
    });

    $('.tampilModalUbah').on('click', function() {
        $('#pinjamModal h5').html('Ubah Peminjaman');
        $('.modal-footer button[type=submit]').html('Ubah Peminjaman');
        $('.modal-content form').attr('action', '<?= BASEURL; ?>/peminjaman/ubah');

        const id = $(this).data('id');
        
        $.ajax({
            url: '<?= BASEURL; ?>/peminjaman/getubah',
            data: {id : id},
            method: 'post',
            dataType: 'json',
            success: function(data) {
                $('#id_pinjam').val(data.id_pinjam);
                $('#nama_peminjam').val(data.nama_peminjam);
                $('#id_alat').val(data.id_alat);
                $('#jumlah').val(data.jumlah);
                $('input[name=tanggal_pinjam]').val(data.tanggal_pinjam);
                $('input[name=tanggal_kembali]').val(data.tanggal_kembali);
            }
        });
    });
});
</script>
