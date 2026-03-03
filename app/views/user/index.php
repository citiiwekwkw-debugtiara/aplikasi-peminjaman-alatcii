<div class="row mb-3 fade-in">
    <div class="col-md-6">
        <h2 class="fw-bold">Manajemen User</h2>
    </div>
    <div class="col-md-6 text-end">
        <button type="button" class="btn btn-primary rounded-3 shadow-sm tombolTambahData" data-bs-toggle="modal" data-bs-target="#userModal">
            <i class="bi bi-person-plus"></i> Tambah User Baru
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
                                <th>#</th>
                                <th>Nama</th>
                                <th>Username</th>
                                <th>Role</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; foreach($data['users'] as $usr) : ?>
                            <tr>
                                <td><?= $i++; ?></td>
                                <td><span class="fw-bold"><?= $usr['nama']; ?></span></td>
                                <td><?= $usr['username']; ?></td>
                                <td><span class="badge bg-info text-white"><?= ucfirst($usr['role']); ?></span></td>
                                <td class="text-center">
                                    <div class="btn-group rounded-3 overflow-hidden">
                                        <a href="<?= BASEURL; ?>/user/ubah/<?= $usr['id_user']; ?>" class="btn btn-warning btn-sm tampilModalUbah" data-bs-toggle="modal" data-bs-target="#userModal" data-id="<?= $usr['id_user']; ?>">Ubah</a>
                                        <a href="<?= BASEURL; ?>/user/hapus/<?= $usr['id_user']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
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

<!-- Modal Tambah/Ubah User -->
<div class="modal fade" id="userModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="userModalLabel">Tambah User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= BASEURL; ?>/user/tambah" method="post">
                <input type="hidden" name="id_user" id="id_user">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label" for="nama">Nama Lengkap</label>
                        <input type="text" class="form-control" name="nama" id="nama" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="username">Username</label>
                        <input type="text" class="form-control" name="username" id="username" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="password">Password</label>
                        <input type="password" class="form-control" name="password" id="password">
                        <small class="text-muted" id="passwordHelp">Kosongkan jika tidak ingin merubah password</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="role">Role</label>
                        <select class="form-select" name="role" id="role" required>
                            <option value="peminjam">Peminjam</option>
                            <option value="petugas">Petugas</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light rounded-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-3 px-4">Simpan User</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(function() {
    $('.tombolTambahData').on('click', function() {
        $('#userModalLabel').html('Tambah Data User');
        $('.modal-footer button[type=submit]').html('Tambah Data');
        $('#nama').val('');
        $('#username').val('');
        $('#password').val('');
        $('#role').val('peminjam');
        $('#id_user').val('');
        $('#passwordHelp').hide();
        $('#password').attr('required', true);
        $('.modal-content form').attr('action', '<?= BASEURL; ?>/user/tambah');
    });

    $('.tampilModalUbah').on('click', function() {
        $('#userModalLabel').html('Ubah Data User');
        $('.modal-footer button[type=submit]').html('Ubah Data');
        $('.modal-content form').attr('action', '<?= BASEURL; ?>/user/ubah');
        $('#passwordHelp').show();
        $('#password').attr('required', false);

        const id = $(this).data('id');
        
        $.ajax({
            url: '<?= BASEURL; ?>/user/getubah',
            data: {id : id},
            method: 'post',
            dataType: 'json',
            success: function(data) {
                $('#nama').val(data.nama);
                $('#username').val(data.username);
                $('#role').val(data.role);
                $('#id_user').val(data.id_user);
                $('#password').val('');
            }
        });
    });
});
</script>
