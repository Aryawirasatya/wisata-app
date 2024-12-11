<?php
session_start();

// Memeriksa apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
require_once 'function.php';

// Menambahkan include untuk header
include_once 'templates/header.php';

// Ambil data transaksi hanya untuk user yang login
$user_id = $_SESSION['user_id'];
$data_transaksi = query("SELECT transaksi.*, wisata.nama_tempat 
                         FROM transaksi
                         JOIN wisata ON transaksi.wisata_id = wisata.id
                         WHERE transaksi.user_id = $user_id");
?>

<!-- Halaman Status Transaksi -->
<div class="layout-page mt-5">
    <div class="container">
        <h2 class="mb-4 text-center">Status Transaksi Anda</h2>

        <?php if (count($data_transaksi) > 0) : ?>
            <!-- Tabel Daftar Transaksi -->
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama Tempat</th>
                        <th>Tanggal Pemesanan</th>
                        <th>Jumlah Tiket</th>
                        <th>Total Harga</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    foreach ($data_transaksi as $transaksi) : ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= $transaksi['nama_tempat'] ?></td>
                            <td><?= $transaksi['tanggal_pemesanan'] ?></td>
                            <td><?= $transaksi['jumlah_tiket'] ?></td>
                            <td><?= $transaksi['total_harga'] ?></td>
                            <td><?= ucfirst($transaksi['status']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else : ?>
            <p class="text-center">Anda belum memiliki transaksi.</p>
        <?php endif; ?>
    </div>
</div>

<?php
// Menambahkan include untuk footer
include_once 'templates/footer.php';
?>
