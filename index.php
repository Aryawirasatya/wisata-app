<?php
session_start();

// Memeriksa apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Menyertakan file fungsi yang diperlukan
require_once 'function.php';
require_once 'templates/header.php';

// Mengambil peran pengguna dari sesi
$role = $_SESSION['role'];
$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Pengguna';

// Mengambil data untuk dashboard admin
if ($role == "admin") {
    // Menghitung total tiket terjual dan total keuntungan
    $total_tiket = query("SELECT SUM(jumlah_tiket) as total_tiket FROM transaksi")[0]['total_tiket'];
    $total_keuntungan = query("SELECT SUM(total_harga) as total_keuntungan FROM transaksi")[0]['total_keuntungan'];

    // Mengambil data tiket yang paling sering dibeli
    $tiket_populer = query("SELECT wisata.nama_tempat, SUM(transaksi.jumlah_tiket) as jumlah_terjual 
                            FROM transaksi 
                            JOIN wisata ON transaksi.wisata_id = wisata.id 
                            GROUP BY wisata_id 
                            ORDER BY jumlah_terjual DESC 
                            LIMIT 1")[0];

    // Mengambil data penjualan untuk grafik
    $data_penjualan = query("SELECT DATE(tanggal_pemesanan) as tanggal, SUM(total_harga) as total 
                             FROM transaksi 
                             GROUP BY DATE(tanggal_pemesanan)");
}


?>

<div class="container-fluid mt-5 wrapper">
    <div class="row">
        <!-- Sidebar -->
        <nav id="sidebar" class="col-md-1 col-lg-1 bg-light">
            <div class="position-sticky">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php">
                            <span data-feather="home"></span>
                            Dashboard
                        </a>
                    </li>
                    <!-- Tambahkan link lainnya di sini -->
                </ul>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="col-md-10 ms-sm-auto col-lg-10 px-md-4">


            <!-- Admin Dashboard -->
            <?php if ($role == "admin"): ?>
                <div class="row g-4">
                    <!-- Total Tiket Terjual -->
                    <div class="col-md-4">
                        <div class="card shadow h-100">
                            <div class="card-body text-center">
                                <h5 class="card-title">Total Tiket Terjual</h5>
                                <p class="display-4"><?= $total_tiket ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- Total Keuntungan -->
                    <div class="col-md-4">
                        <div class="card shadow h-100">
                            <div class="card-body text-center">
                                <h5 class="card-title">Total Keuntungan</h5>
                                <p class="display-4">Rp <?= number_format($total_keuntungan, 0, ',', '.') ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- Tiket Paling Populer -->
                    <div class="col-md-4">
                        <div class="card shadow h-100">
                            <div class="card-body text-center">
                                <h5 class="card-title">Tiket Paling Populer</h5>
                                <p class="lead"><?= $tiket_populer['nama_tempat'] ?></p>
                                <p>Terjual: <?= $tiket_populer['jumlah_terjual'] ?> tiket</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Grafik Penjualan -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="card shadow h-100">
                            <div class="card-body">
                                <h5 class="card-title text-center">Grafik Penjualan</h5>
                                <canvas id="grafikPenjualan"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- User Dashboard -->
            <?php if ($role == "user"): ?>
    <div class="container-fluid py-5">
        <div class="text-center mb-5">
            <!-- Sambutan -->
        <div class="text-center mb-5 border-bottom">
            <h1 class="h2">Selamat Datang, <?= htmlspecialchars($username); ?>!</h1>
            <p class="lead">Kami senang melihat Anda kembali.</p>
        </div>

            <h1 class="display-4 text-primary">Selamat Datang di Aplikasi Pemesanan Tiket Wisata!</h1>
            <p class="lead">Temukan petualangan baru dan buat kenangan tak terlupakan bersama kami.</p>
        </div>


    </div>
            <?php endif; ?>

        </main>
    </div>
</div>


<!-- Footer -->
<?php
include_once 'templates/footer.php';
?>

<!-- Sertakan script Bootstrap dan jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<?php if ($role == "admin"): ?>
<!-- Sertakan script Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Data untuk grafik penjualan
    var ctx = document.getElementById('grafikPenjualan').getContext('2d');
    var chartData = {
        labels: [<?= implode(',', array_map(function($d) { return "'" . $d['tanggal'] . "'"; }, $data_penjualan)) ?>],
        datasets: [{
            label: 'Penjualan Harian',
            data: [<?= implode(',', array_map(function($d) { return $d['total']; }, $data_penjualan)) ?>],
            backgroundColor: 'rgba(54, 162, 235, 0.2)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }]
    };

    var myChart = new Chart(ctx, {
        type: 'line',
        data: chartData,
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>

<?php endif; ?>
