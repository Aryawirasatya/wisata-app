<?php
require_once 'function.php';

// Menambahkan include untuk header
include_once 'templates/header.php';

// Mendapatkan ID dari URL
$id = $_GET['id'] ?? null;

// Jika ID ada, kita ambil data wisata yang sesuai
if ($id) {
    $wisata = query("SELECT * FROM wisata WHERE id = '$id'")[0]; // Mengambil data wisata berdasarkan ID
    $kategori_data = query("SELECT * FROM kategori"); // Mengambil semua kategori
}

// Jika form disubmit
if (isset($_POST['submit_edit'])) {
    if (edit_wisata($_POST)) {
        echo "<script>alert('Data wisata berhasil diperbarui!'); window.location='wisata.php';</script>";
    } else {
        echo "<script>alert('Terjadi kesalahan saat memperbarui data wisata.');</script>";
    }
}
?>

<!-- Halaman Edit Wisata -->
<div class="layout-page mt-5">
    <div class="container">
        <h2 class="mb-4 text-center">Edit Tempat Wisata</h2>

        <!-- Form Edit Wisata -->
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="nama_tempat">Nama Tempat</label>
                <input type="text" class="form-control" id="nama_tempat" name="nama_tempat" value="<?= $wisata['nama_tempat'] ?>" required>
            </div>
            <div class="form-group">
                <label for="lokasi">Lokasi</label>
                <input type="text" class="form-control" id="lokasi" name="lokasi" value="<?= $wisata['lokasi'] ?>" required>
            </div>
            <div class="form-group">
                <label for="deskripsi">Deskripsi</label>
                <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" required><?= $wisata['deskripsi'] ?></textarea>
            </div>
            <div class="form-group">
                <label for="harga">Harga</label>
                <input type="number" class="form-control" id="harga" name="harga" step="0.01" value="<?= $wisata['harga'] ?>" required>
            </div>
            <div class="form-group">
                <label for="rating">Rating</label>
                <input type="number" class="form-control" id="rating" name="rating" step="0.1" min="1" max="5" value="<?= $wisata['rating'] ?>" required>
            </div>
            <div class="form-group">
                <label for="kontak">Kontak</label>
                <input type="text" class="form-control" id="kontak" name="kontak" value="<?= $wisata['kontak'] ?>" required>
            </div>
            <div class="form-group">
                <label for="jam_operasional">Jam Operasional</label>
                <input type="text" class="form-control" id="jam_operasional" name="jam_operasional" value="<?= $wisata['jam_operasional'] ?>" required>
            </div>
            <div class="form-group">
                <label for="foto">Foto</label>
                <input type="file" class="form-control" id="foto" name="foto" accept="image/*">
                <img src="foto/<?= $wisata['foto']; ?>" width="100" alt="Foto Lama" class="mt-2">
            </div>
            <div class="form-group">
                <label for="kategori_id">Kategori</label>
                <select class="form-control" id="kategori_id" name="kategori_id">
                    <?php foreach ($kategori_data as $kategori) : ?>
                        <option value="<?= $kategori['id'] ?>" <?= $wisata['kategori_id'] == $kategori['id'] ? 'selected' : '' ?>>
                            <?= $kategori['nama_kategori'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <!-- Menambahkan foto_lama sebagai input tersembunyi -->
            <input type="hidden" name="foto_lama" value="<?= $wisata['foto'] ?>"> <!-- Menyimpan foto lama untuk digunakan saat update -->
            <input type="hidden" name="id" value="<?= $wisata['id'] ?>"> <!-- Menyimpan ID untuk update -->
            <button type="submit" name="submit_edit" class="btn btn-primary btn-block">Simpan</button>
        </form>

    </div>
</div>

<?php
// Menambahkan include untuk footer
include_once 'templates/footer.php';
?>