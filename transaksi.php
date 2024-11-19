<?php
require_once 'function.php';

// Menambahkan include untuk header
include_once 'templates/header.php';

// Mengambil data transaksi yang ada dengan join ke tabel wisata dan users
$data_transaksi = query("SELECT transaksi.*, wisata.nama_tempat, users.username 
                         FROM transaksi
                         JOIN wisata ON transaksi.wisata_id = wisata.id
                         JOIN users ON transaksi.user_id = users.id");
?>

<!-- Halaman Daftar Transaksi -->
<div class="layout-page mt-5">
    <div class="container">
        <h2 class="mb-4 text-center">Daftar Transaksi</h2>

        <!-- Tabel Daftar Transaksi -->
        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama Tempat</th>
                    <th>Username</th>
                    <th>Tanggal Pemesanan</th>
                    <th>Jumlah Tiket</th>
                    <th>Total Harga</th>
                    <th>Status</th>
                    <th>Aksi</th> <!-- Kolom Aksi untuk update status -->
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                foreach ($data_transaksi as $transaksi) : ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= $transaksi['nama_tempat'] ?></td>
                        <td><?= $transaksi['username'] ?></td>
                        <td><?= $transaksi['tanggal_pemesanan'] ?></td>
                        <td><?= $transaksi['jumlah_tiket'] ?></td>
                        <td><?= $transaksi['total_harga'] ?></td>
                        <td><?= ucfirst($transaksi['status']) ?></td>
                        <td>
                            <!-- Form untuk update status transaksi -->
                            <form action="update-status.php" method="POST" style="display:inline;">
                                <input type="hidden" name="id" value="<?= $transaksi['id']; ?>">
                                <select name="status" class="form-control" style="width:auto;">
                                    <option value="pending" <?= ($transaksi['status'] == 'pending') ? 'selected' : ''; ?>>Pending</option>
                                    <option value="confirmed" <?= ($transaksi['status'] == 'confirmed') ? 'selected' : ''; ?>>Confirmed</option>
                                    <option value="cancelled" <?= ($transaksi['status'] == 'cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                                </select>
                                <button type="submit" class="btn btn-primary btn-sm">Update</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
// Menambahkan include untuk footer
include_once 'templates/footer.php';
?>
