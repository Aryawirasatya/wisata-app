<?php
require_once "function.php";

// Mengambil data kategori
$data_kategori = query("SELECT * FROM kategori");

// Memeriksa apakah form tambah kategori disubmit
if (isset($_POST['submit_kategori'])) {
    if (tambah_kategori($_POST) > 0) {
        echo "<script>alert('Kategori berhasil ditambahkan!'); window.location='kategori.php';</script>";
    } else {
        echo "<script>alert('Gagal menambahkan kategori.');</script>";
    }
}
?>

<!-- Form untuk Menambah Kategori -->
<div class="card mb-3">
    <div class="card-header">
        <h5>Tambah Kategori</h5>
    </div>
    <div class="card-body">
        <form action="" method="POST">
            <div class="form-group">
                <label for="nama_kategori">Nama Kategori</label>
                <input type="text" class="form-control" id="nama_kategori" name="nama_kategori" required>
            </div>
            <button type="submit" name="submit_kategori" class="btn btn-primary">Tambah Kategori</button>
        </form>
    </div>
</div>

<!-- Tabel Menampilkan Data Kategori -->
<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nama Kategori</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($data_kategori as $kategori) : ?>
            <tr>
                <td><?= $kategori['id']; ?></td>
                <td><?= $kategori['nama_kategori']; ?></td>
                <td>
                    <a class="btn btn-success" href="edit-kategori.php?id=<?= $kategori['id']; ?>">Ubah</a>
                    <a onclick="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')" class="btn btn-danger" href="hapus-kategori.php?id=<?= $kategori['id']; ?>">Hapus</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
