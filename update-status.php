<?php
require_once 'function.php';

// Mengecek apakah form sudah disubmit
if (isset($_POST['status']) && isset($_POST['id'])) {
    $id = $_POST['id'];
    $status = $_POST['status'];

    // Memanggil fungsi untuk update status
    if (update_status($id, $status)) {
        // Jika update berhasil
        echo "<script>alert('Status transaksi berhasil diubah!'); window.location='view_transaksi.php';</script>";
    } else {
        // Jika gagal mengupdate status
        echo "<script>alert('Gagal mengubah status transaksi.'); window.location='view_transaksi.php';</script>";
    }
}
?>
