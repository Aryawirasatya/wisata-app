<?php
session_start();
require_once 'koneksi.php';
require_once 'function.php';

// Memeriksa apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include_once 'templates/header.php';

// Mengambil data pengguna
if (isset($_POST['tampilkan'])) {  
    $p_awal = $_POST['p_awal'];  
    $p_akhir = $_POST['p_akhir'];  
    $link = "export-excel.php?cari=true&p_awal=$p_awal&p_akhir=$p_akhir";  

    // Query untuk filter berdasarkan tanggal dan gabungkan dengan tabel wisata
    $wisata = query("
        SELECT t.*, w.nama_tempat 
        FROM transaksi t
        JOIN wisata w ON t.wisata_id = w.id
        WHERE t.tanggal_pemesanan BETWEEN '$p_awal' AND '$p_akhir'
        ORDER BY t.tanggal_pemesanan DESC
    ");
} else {  
    // Query untuk menampilkan semua data dengan gabungan tabel wisata
    $wisata = query("
        SELECT t.*, w.nama_tempat 
        FROM transaksi t
        JOIN wisata w ON t.wisata_id = w.id
        ORDER BY t.tanggal_pemesanan DESC
    ");
}  
?>

<!-- Dashboard Page -->
<div class="layout-page mt-5">
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-4 text-gray-800">Laporan </h1>
        <div class="row mx-auto d-flex justify-content-center">
            <!-- Periode Awal -->
            <div class="col-xl-5 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <form method="post" action="">
                                        <div class="form-row align-items-center">
                                            <div class="col-auto">
                                                <div class="font-weight-bold text-primary text-uppercase mb-1">
                                                    Periode
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <input type="date" class="form-control mb-2" id="p_awal" name="p_awal" required>
                                            </div>
                                            <div class="col-auto">
                                                <div class="font-weight-bold text-primary mb-1">
                                                    s.d
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <input type="date" class="form-control mb-2" id="p_akhir" name="p_akhir" required>
                                            </div>
                                            <div class="col-auto">
                                                <button type="submit" name="tampilkan" class="btn btn-primary mb-2">Tampilkan</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Export Laporan Button -->
        <div class='card shadow mb-4'>
            <div class='card-header py-3'>
                <a href='<?php echo isset($_POST['tampilkan']) ? $link : 'export-laporan.php'; ?>' target='_blank' class='btn btn-success btn-icon-split'>
                    <span class='icon text-white-50'>
                        <i class='fas fa-file-excel'></i>
                    </span>
                    <span class='text'>Export Laporan</span>
                </a>
            </div>
        </div>

        <!-- Data Table -->
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>tanggal pemesanan</th>
                            <th> jumlah tiket</th>
                            <th>total harga</th>
                            <th>status</th>
                            <th>tanggal dibuat</th>



                        </tr>
                    <tbody>
                        <?php
                        // Penomoran auto-increment  
                        $no = 1;

                        foreach ($wisata as $data) : ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?= $data['tanggal_pemesanan'] ?></td>
                                <td><?= $data['jumlah_tiket'] ?></td>
                                <td><?= $data['total_harga'] ?></td>
                                <td><?= $data['status'] ?></td>
                                <td><?= $data['created_at'] ?></td>
 <!-- Menampilkan nama tempat -->
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
include_once 'templates/footer.php';
?>
