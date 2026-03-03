<?php $this->view('templates/header', $data); ?>

<div class="row justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="col-md-5">
        <div class="card shadow-lg border-0 rounded-4">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <h2 class="fw-bold text-primary">Selamat Datang</h2>
                    <p class="text-muted">Aplikasi Peminjaman Alat</p>
                </div>


                <form action="<?= BASEURL; ?>/auth/login" method="post">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control form-control-lg rounded-3" id="username" name="username" placeholder="Masukkan username" required>
                    </div>
                    <div class="mb-4">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control form-control-lg rounded-3" id="password" name="password" placeholder="Masukkan password" required>
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg rounded-3 fw-semibold">Masuk Sekarang</button>
                    </div>
                </form>

                <div class="mt-4 text-center">
                    <small class="text-muted">Demo: admin / admin123</small>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->view('templates/footer'); ?>
