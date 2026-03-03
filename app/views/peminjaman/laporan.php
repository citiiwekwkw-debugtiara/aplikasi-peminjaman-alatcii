<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['judul']; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: white;
            font-family: 'Times New Roman', Times, serif;
        }
        .report-header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px double #000;
            padding-bottom: 15px;
        }
        @media print {
            .no-print {
                display: none;
            }
            @page {
                size: landscape;
            }
            body {
                padding: 0;
            }
        }
    </style>
</head>
<body onload="window.print()">

<div class="container-fluid mt-4">
    <div class="no-print mb-4">
        <a href="<?= BASEURL; ?>/peminjaman" class="btn btn-secondary">Kembali</a>
        <button onclick="window.print()" class="btn btn-primary">Cetak Ulang</button>
    </div>

    <div class="report-header">
        <h2 class="text-uppercase fw-bold">Laporan Peminjaman Alat</h2>
        <h4>Aplikasi Peminjaman Alat UKK RPL</h4>
        <p class="mb-0">Tanggal Cetak: <?= date('d/m/Y H:i'); ?></p>
    </div>

    <table class="table table-bordered table-striped border-dark">
        <thead class="table-dark border-dark">
            <tr class="text-center">
                <th>No</th>
                <th>Nama Peminjam</th>
                <th>Nama Alat</th>
                <th>Jumlah</th>
                <th>Tgl Pinjam</th>
                <th>Tgl Kembali</th>
                <th>Tgl Dikembalikan</th>
                <th>Denda</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; foreach($data['laporan'] as $row) : ?>
            <tr>
                <td class="text-center"><?= $no++; ?></td>
                <td><?= $row['nama_peminjam']; ?></td>
                <td><?= $row['nama_alat']; ?></td>
                <td class="text-center"><?= $row['jumlah']; ?></td>
                <td class="text-center"><?= date('d/m/Y', strtotime($row['tanggal_pinjam'])); ?></td>
                <td class="text-center"><?= date('d/m/Y', strtotime($row['tanggal_kembali'])); ?></td>
                <td class="text-center"><?= ($row['tanggal_dikembalikan']) ? date('d/m/Y', strtotime($row['tanggal_dikembalikan'])) : '-'; ?></td>
                <td class="text-end">Rp <?= number_format($row['denda'], 0, ',', '.'); ?></td>
                <td class="text-center">
                    <span class="badge border border-dark text-dark"><?= ucfirst($row['status']); ?></span>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="row mt-5">
        <div class="col-8"></div>
        <div class="col-4 text-center">
            <p>Mengetahui,</p>
            <p>Petugas Perpustakaan</p>
            <div style="height: 80px;"></div>
            <p class="fw-bold">( <?= $_SESSION['user']['nama']; ?> )</p>
        </div>
    </div>
</div>

</body>
</html>
