<?php
require_once 'function.php';

// Menambahkan include untuk header
include_once 'templates/header.php';

// Memanggil fungsi tampilkan_users untuk mengambil data user
$users = query("SELECT * FROM users");
?>

<!-- Halaman Daftar Users -->
<div class="layout-page mt-5">
    <div class="container">
        <h2 class="mb-4 text-center">Daftar Pengguna</h2>

        <!-- Tabel Daftar Users -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Nama Lengkap</th>
                    <th>Email</th>
                    <th>Kontak</th>
                    <th>Tanggal Bergabung</th>
                    <th>Aksi</th> <!-- Kolom Aksi -->
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= $user['id'] ?></td>
                        <td><?= $user['username'] ?></td>
                        <td><?= $user['role'] ?></td>
                        <td><?= $user['nama_lengkap'] ?></td>
                        <td><?= $user['email'] ?></td>
                        <td><?= $user['kontak'] ?></td>
                        <td><?= $user['created_at'] ?></td>
                        <td>
                            <a href="hapus-users.php?id=<?= $user['id'] ?>"
                               class="btn btn-danger"
                               onclick="return confirm('Apakah Anda yakin ingin menghapus user ini?')">Hapus</a>
                            <a class="btn btn-success" href="edit-users.php?id=<?= $user['id'] ?>">Ubah</a>
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
