<div class="row mb-3 fade-in">
    <div class="col-md-6">
        <h2 class="fw-bold">Manajemen Kategori Alat</h2>
    </div>
    <div class="col-md-6 text-end">
        <button type="button" class="btn btn-primary rounded-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#kategoriModal">
            <i class="bi bi-tag-fill"></i> Tambah Kategori Baru
        </button>
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
                                <th width="10%">#</th>
                                <th>Nama Kategori</th>
                                <th class="text-center" width="20%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($data['kategori'])) : ?>
                                <tr>
                                    <td colspan="3" class="text-center py-4 text-muted">Belum ada data kategori.</td>
                                </tr>
                            <?php else: ?>
                                <?php $i = 1; foreach($data['kategori'] as $kat) : ?>
                                <tr>
                                    <td><?= $i++; ?></td>
                                    <td><span class="fw-bold"><?= $kat['nama_kategori']; ?></span></td>
                                    <td class="text-center">
                                        <div class="btn-group rounded-3 overflow-hidden">
                                            <a href="<?= BASEURL; ?>/kategori/hapus/<?= $kat['id_kategori']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus kategori ini?')">Hapus</a>
                                        </div>
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
</div>

<!-- Modal Tambah Kategori -->
<div class="modal fade" id="kategoriModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Tambah Kategori Alat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= BASEURL; ?>/kategori/tambah" method="post">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="nama_kategori" class="form-label">Nama Kategori</label>
                        <input type="text" class="form-control" id="nama_kategori" name="nama_kategori" placeholder="Contoh: Elektronik, Kayu, Mesin" required>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light rounded-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-3 px-4">Simpan Kategori</button>
                </div>
            </form>
        </div>
    </div>
</div>
