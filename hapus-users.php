<?php
require_once 'function.php';
if ($_SESSION['role'] != "admin") { header("Location: index.php"); exit; }

if (isset($_GET['id'])) {
    $id = $_GET['id'];

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