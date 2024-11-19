<?php
session_start();

// Memeriksa apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include_once 'templates/header.php';

// Mengambil data pengguna
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
$role = $_SESSION['role'];

// Data dummy untuk dashboard
$total_wisata = 10; // Total tempat wisata
$total_pelanggan = 150; // Total pelanggan
$total_keuntungan = 5000000; // Total keuntungan (dalam Rupiah)
$chart_data = [100, 200, 150, 300, 250]; // Data dummy untuk chart

?>

<!-- Dashboard Page -->
<div class="layout-page mt-5">
    <div class="container">
        <h2>Welcome, <?= $username ?>!</h2>
        <p>Role: <?= $role ?></p>
        
        <!-- Statistik -->
        <div class="row mt-4">
            <div class="col-md-4">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Jumlah Wisata</h5>
                        <p class="card-text"><?= $total_wisata ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Jumlah Pelanggan</h5>
                        <p class="card-text"><?= $total_pelanggan ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Keuntungan</h5>
                        <p class="card-text">Rp <?= number_format($total_keuntungan, 0, ',', '.') ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chart -->
        <div class="card mt-4">
            <div class="card-header">Grafik Pengunjung</div>
            <div class="card-body">
                <canvas id="chartCanvas"></canvas>
            </div>
        </div>

        <a href="logout.php" class="btn btn-danger mt-4">Logout</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Data untuk chart
    const chartData = <?= json_encode($chart_data) ?>;
    const ctx = document.getElementById('chartCanvas').getContext('2d');

    new Chart(ctx, {
        type: 'line', // Tipe grafik: line, bar, pie, dll.
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May'], // Label bulan
            datasets: [{
                label: 'Pengunjung',
                data: chartData,
                borderColor: 'rgba(75, 192, 192, 1)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
            },
        },
    });
</script>

<?php
include_once 'templates/footer.php';
?>
