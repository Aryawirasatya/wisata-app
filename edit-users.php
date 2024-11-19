<?php
require_once 'function.php';

// Menambahkan include untuk header
include_once 'templates/header.php';

// Mengambil ID dari URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    // Ambil data user berdasarkan ID
    $user = query("SELECT * FROM users WHERE id = '$id'")[0];
}

if (isset($_POST['submit_edit_user'])) {
    // Memanggil fungsi edit_user dan mengirimkan data
    if (edit_user($_POST)) {
        echo "<script>alert('User berhasil diperbarui!'); window.location='users.php';</script>";
    } else {
        echo "<script>alert('Terjadi kesalahan saat memperbarui user.');</script>";
    }
}
?>

<!-- Halaman Edit User -->
<div class="layout-page mt-5">
    <div class="container">
        <h2 class="mb-4 text-center">Edit User</h2>

        <!-- Form untuk edit user -->
        <form action="" method="POST">
            <input type="hidden" name="id" value="<?= $user['id']; ?>">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" name="username" value="<?= $user['username']; ?>" required>
            </div>
            <div class="form-group">
                <label for="password">Password (Kosongkan jika tidak ingin diubah)</label>
                <input type="password" class="form-control" id="password" name="password">
            </div>
            <div class="form-group">
                <label for="nama_lengkap">Nama Lengkap</label>
                <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" value="<?= $user['nama_lengkap']; ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?= $user['email']; ?>">
            </div>
            <div class="form-group">
                <label for="kontak">Kontak</label>
                <input type="text" class="form-control" id="kontak" name="kontak" value="<?= $user['kontak']; ?>">
            </div>
            <button type="submit" name="submit_edit_user" class="btn btn-primary btn-block">Simpan Perubahan</button>
        </form>
    </div>
</div>

<?php
// Menambahkan include untuk footer
include_once 'templates/footer.php';
?>
