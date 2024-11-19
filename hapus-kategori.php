<?php
require_once "function.php";

// Jika ada id kategori yang ingin dihapus
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    if (hapus_kategori($id) > 0) {
        echo "<script>alert('Kategori berhasil dihapus!'); window.location='kategori.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus kategori.');</script>";
    }
}
