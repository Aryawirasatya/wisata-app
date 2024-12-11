<?php
session_start();

// Memeriksa apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
 


// Menyertakan file fungsi
require_once 'function.php';
require_once 'templates/header.php'; // Header umum

// Mendapatkan ID wisata dari URL
if (!isset($_GET['id'])) {
    echo "<script>alert('ID wisata tidak ditemukan!'); window.location='index.php';</script>";
    exit;
}

$id_wisata = $_GET['id'];

// Mengambil data wisata berdasarkan ID
$wisata = query("SELECT wisata.*, kategori.nama_kategori FROM wisata 
                 JOIN kategori ON wisata.kategori_id = kategori.id 
                 WHERE wisata.id = $id_wisata");

if (!$wisata) {
    echo "<script>alert('Data wisata tidak ditemukan!'); window.location='index.php';</script>";
    exit;
}

// Data wisata hanya satu, ambil indeks pertama
$wisata = $wisata[0];
?>

<div class="layout-page mt-5">
    <div class="container mt-5">
        <div class="row">
            <!-- Gambar Wisata -->
            <div class="col-md-6 mb-4">
                <img src="foto/<?= $wisata['foto']; ?>" alt="<?= $wisata['nama_tempat']; ?>" class="img-fluid rounded shadow-lg">
            </div>

            <!-- Detail Wisata -->
            <div class="col-md-6">
                <h1 class="display-4 text-primary"><?= $wisata['nama_tempat']; ?></h1>
                <p class="text-muted mb-4"><?= $wisata['lokasi']; ?></p>
                
                <div class="lead mb-4">
                    <p><strong>Kategori:</strong> <?= $wisata['nama_kategori']; ?></p>
                    <p><strong>Harga:</strong> Rp <?= number_format($wisata['harga'], 0, ',', '.'); ?></p>
                    <p><strong>Kontak:</strong> <?= $wisata['kontak']; ?></p>
                    <p><strong>Jam Operasional:</strong> <?= $wisata['jam_operasional']; ?></p>
                </div>
                
                <p class="mb-4"><?= nl2br($wisata['deskripsi']); ?></p>

                <!-- Tombol Kembali -->
                <a href="wisata.php" class="btn btn-secondary btn-lg rounded-pill shadow-lg">Kembali</a>
                <?php if ($_SESSION['role'] == "user"): ?>
                    <a href="create-transaksi.php" class="btn btn-primary btn-lg rounded-pill shadow-lg">Pesan</a>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<?php include_once 'templates/footer.php'; ?>

<!-- Sertakan script Bootstrap dan jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
