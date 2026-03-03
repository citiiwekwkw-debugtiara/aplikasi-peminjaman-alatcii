<div class="row mb-3 fade-in">
    <div class="col-md-6">
        <h2 class="fw-bold">Manajemen Alat</h2>
    </div>
    <div class="col-md-6 text-end">
        <?php if($_SESSION['user']['role'] != 'peminjam') : ?>
        <button type="button" class="btn btn-primary rounded-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#formModal">
            <i class="bi bi-plus-lg"></i> Tambah Alat Baru
        </button>
        <?php endif; ?>
    </div>
</div>

<div class="row fade-in">
    <div class="col-md-12">
        <?php Flasher::flash(); ?>
        
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle" id="tableAlat">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Gambar</th>
                                <th>Nama Alat</th>
                                <th>Kategori</th>
                                <th>Stok</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; foreach($data['alat'] as $alt) : ?>
                            <tr>
                                <td><?= $i++; ?></td>
                                <td>
                                    <img src="<?= BASEURL; ?>/assets/img/alat/<?= $alt['gambar'] ?? 'default.jpg'; ?>" alt="<?= $alt['nama_alat']; ?>" class="rounded shadow-sm" style="width: 50px; height: 50px; object-fit: cover;">
                                </td>
                                <td><span class="fw-bold"><?= $alt['nama_alat']; ?></span></td>
                                <td><span class="badge bg-light text-dark"><?= $alt['nama_kategori'] ?? 'Tanpa Kategori'; ?></span></td>
                                <td>
                                    <?php if($alt['stok'] > 5) : ?>
                                        <span class="text-success fw-bold"><?= $alt['stok']; ?></span>
                                    <?php else: ?>
                                        <span class="text-danger fw-bold"><?= $alt['stok']; ?> (Hampir Habis)</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group shadow-sm rounded-3 overflow-hidden">
                                        <a href="<?= BASEURL; ?>/alat/detail/<?= $alt['id_alat']; ?>" class="btn btn-info btn-sm text-white">Detail</a>
                                        <?php if($_SESSION['user']['role'] != 'peminjam') : ?>
                                        <a href="<?= BASEURL; ?>/alat/ubah/<?= $alt['id_alat']; ?>" class="btn btn-warning btn-sm text-white">Ubah</a>
                                        <a href="<?= BASEURL; ?>/alat/hapus/<?= $alt['id_alat']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
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

<!-- Modal Tambah Alat -->
<div class="modal fade" id="formModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="formModalLabel">Tambah Alat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= BASEURL; ?>/alat/tambah" method="post" enctype="multipart/form-data">
                <div class="modal-body p-4">
                    <div class="mb-3 text-center">
                        <img src="<?= BASEURL; ?>/assets/img/alat/default.jpg" id="imgPreview" class="img-thumbnail rounded-3 mb-3" style="max-height: 150px;">
                        <input type="file" class="form-control" id="gambar" name="gambar" onchange="previewImage()">
                    </div>
                    <div class="mb-3">
                        <label for="nama_alat" class="form-label">Nama Alat</label>
                        <input type="text" class="form-control" id="nama_alat" name="nama_alat" required>
                    </div>
                    <div class="mb-3">
                        <label for="id_kategori" class="form-label">Kategori</label>
                        <select class="form-select" id="id_kategori" name="id_kategori">
                            <option value="">Pilih Kategori</option>
                            <?php foreach($data['kategori'] as $kat) : ?>
                                <option value="<?= $kat['id_kategori']; ?>"><?= $kat['nama_kategori']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="stok" class="form-label">Stok</label>
                        <input type="number" class="form-control" id="stok" name="stok" min="0" required>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light rounded-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-3 px-4">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
