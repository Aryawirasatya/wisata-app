<?php
require_once 'function.php';

// Memeriksa apakah ada parameter id yang diterima
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fungsi untuk menghapus user
    if (hapus_users($id) > 0) {
        // Jika user berhasil dihapus
        echo "<script>alert('User berhasil dihapus!'); window.location.href='users.php';</script>";
    } else {
        // Jika gagal menghapus user
        echo "<script>alert('User gagal dihapus!'); window.location.href='users.php';</script>";
    }
} else {
    echo "<script>alert('ID user tidak ditemukan!'); window.location.href='users.php';</script>";
}