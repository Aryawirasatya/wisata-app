<?php
require_once "function.php";

// Jika form edit kategori disubmit
if (isset($_POST['submit_edit_kategori'])) {
    if (edit_kategori($_POST) > 0) {
        echo "<script>alert('Kategori berhasil diupdate!'); window.location='kategori.php';</script>";
    } else {
        echo "<script>alert('Gagal mengupdate kategori.');</script>";
    }
}
// Mendapatkan data kategori berdasarkan ID yang ada di URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $kategori = query("SELECT * FROM kategori WHERE id = '$id'")[0];  // Mengambil kategori berdasarkan ID
}
?>

<form action="" method="POST">
    <input type="hidden" name="id" value="<?= $kategori['id']; ?>">  <!-- Menyimpan ID kategori -->
    <div class="form-group">
        <label for="nama_kategori">Nama Kategori</label>
        <input type="text" class="form-control" id="nama_kategori" name="nama_kategori" value="<?= $kategori['nama_kategori']; ?>" required>
    </div>
    <button type="submit" name="submit_edit_kategori" class="btn btn-warning">Update Kategori</button>
</form>