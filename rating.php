<?php
session_start();
require_once 'function.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $wisata_id = $_POST['wisata_id'];
    $rating = $_POST['rating'];

    // Validasi rating
    if ($rating < 0 || $rating > 5) {
        echo "<script>alert('Rating harus antara 0-5'); window.history.back();</script>";
        exit;
    }

    // Simpan rating ke database
    $query = "INSERT INTO rating (user_id, wisata_id, rating)
              VALUES ('$user_id', '$wisata_id', '$rating')
              ON DUPLICATE KEY UPDATE rating = '$rating'";
    
    if (query($query)) {
        echo "<script>alert('Rating berhasil disimpan!'); window.location='detail-wisata.php?id=$wisata_id';</script>";
    } else {
        echo "<script>alert('Terjadi kesalahan saat menyimpan rating.'); window.history.back();</script>";
    }
}
?>
