<?php
session_start();

// Memeriksa apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Menyertakan file fungsi yang diperlukan
require_once 'function.php';
require_once 'templates/header.php'; // Header umum

// Memeriksa apakah form telah disubmit
if (isset($_POST['submit_wisata'])) {
    // Memanggil fungsi tambah_wisata dan mengirimkan data
    if (tambah_wisata($_POST)) {
        echo "<script>alert('Data wisata berhasil disimpan!'); window.location='wisata.php';</script>";
    } else {
        echo "<script>alert('Terjadi kesalahan saat menyimpan data wisata.');</script>";
    }
}


// Menyertakan file fungsi yang diperlukan
require_once 'function.php';
require_once 'templates/header.php'; // Header umum

// Ambil kata kunci pencarian
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Ubah query untuk menyaring hasil berdasarkan pencarian
$query = "SELECT wisata.*, kategori.nama_kategori FROM wisata 
          JOIN kategori ON wisata.kategori_id = kategori.id";

if ($search) {
    // Jika ada kata kunci pencarian, tambahkan klausa WHERE
    $query .= " WHERE wisata.nama_tempat LIKE '%$search%' OR wisata.lokasi LIKE '%$search%'";
}

$data_wisata = query($query);

?>

<div class="layout-page mt-5">
    <div class="container mt-5">
        <!-- Title Section -->
        <div class="text-center mb-5">
            <h1 class="display-4 text-primary">Eksplorasi Tempat Wisata</h1>
            <p class="lead">Temukan berbagai tempat menarik untuk petualangan Anda berikutnya!</p>
        </div>

 
<!-- Pencarian -->
<form action="" method="GET" class="form-inline mb-4 d-flex justify-content-center w-100">
    <div class="form-group w-75">
        <input type="text" class="form-control w-100" name="search" placeholder="Cari tempat wisata..." value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
        <center>
            <button type="submit" class="btn btn-primary mt-5" style="padding: 15px 60px; ">Cari</button>
        </center>
    </div>
    

</form>



        
        <!-- Admin Actions -->
        <?php if ($_SESSION['role'] == "admin") : ?>
            <div class="text-center mb-4">
                <button type="button" class="btn btn-primary btn-lg rounded-pill shadow" data-toggle="modal" data-target="#addWisataModal">Tambah Wisata</button>
                <a href="kategori.php" class="btn btn-success btn-lg rounded-pill shadow">Lihat Kategori</a>
            </div>
        <?php endif; ?>

        <!-- Grid Tempat Wisata -->
        <div class="row">
            <?php foreach ($data_wisata as $wisata) : ?>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card shadow h-100">
                        <!-- Image Section -->
                        <img src="foto/<?= $wisata['foto']; ?>" class="card-img-top img-fluid rounded" alt="<?= $wisata['nama_tempat']; ?>">
                        <div class="card-body">
                            <!-- Title and Information Section -->
                            <h5 class="card-title text-primary"><?= $wisata['nama_tempat']; ?></h5>
                            <p class="card-text">
                                <strong>Lokasi:</strong> <?= $wisata['lokasi']; ?><br>
                                <strong>Kategori:</strong> <?= $wisata['nama_kategori']; ?><br>
                                <strong>Harga:</strong> Rp <?= number_format($wisata['harga'], 0, ',', '.'); ?><br>
                                <strong>Rating rekomendasi:</strong> <?= number_format($wisata['rating'], 1, ',', '.'); ?>
                            </p>
                            <p class="card-text text-muted"><?= substr($wisata['deskripsi'], 0, 100); ?>...</p>

                            <!-- Action Buttons -->
                            <div class="d-flex justify-content-between">
                                <a href="detail-wisata.php?id=<?= $wisata['id']; ?>" class="btn btn-info btn-sm shadow ">Lihat Detail</a>
                                <?php if ($_SESSION['role'] == "admin") : ?>
                                    <div>
                                        <a href="edit-wisata.php?id=<?= $wisata['id']; ?>" class="btn btn-success btn-sm shadow">Ubah</a>
                                        <a onclick="return confirm('Apakah anda yakin ingin menghapus data ini?')" href="hapus-wisata.php?id=<?= $wisata['id']; ?>" class="btn btn-danger btn-sm shadow">Hapus</a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Modal Tambah Wisata -->
        <!-- Modal Tambah Wisata -->
<!-- Modal Tambah Wisata -->
<div class="modal fade" id="addWisataModal" tabindex="-1" role="dialog" aria-labelledby="addWisataModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addWisataModalLabel">Tambah Tempat Wisata</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Form untuk tambah wisata -->
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="nama_tempat">Nama Tempat</label>
                        <input type="text" class="form-control" id="nama_tempat" name="nama_tempat" required>
                    </div>
                    <div class="form-group">
                        <label for="lokasi">Lokasi</label>
                        <input type="text" class="form-control" id="lokasi" name="lokasi" required>
                    </div>
                    <div class="form-group">
                        <label for="deskripsi">Deskripsi</label>
                        <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="harga">Harga</label>
                        <input type="number" class="form-control" id="harga" name="harga" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label for="rating">Rating rekomendasi  </label>
                        <input type="number" class="form-control" id="rating" name="rating" step="0.1" min="1" max="5" required>
                    </div>
                    <div class="form-group">
                        <label for="kontak">Kontak</label>
                        <input type="text" class="form-control" id="kontak" name="kontak" required>
                    </div>
                    <div class="form-group">
                        <label for="jam_operasional">Jam Operasional</label>
                        <input type="text" class="form-control" id="jam_operasional" name="jam_operasional" required>
                    </div>
                    <div class="form-group">
                        <label for="foto">Foto</label>
                        <input type="file" class="form-control" id="foto" name="foto" accept="image/*" required>
                    </div>
                    <div class="form-group">
                        <label for="kategori_id">Kategori</label>
                        <select class="form-control" id="kategori_id" name="kategori_id" required>
                            <?php
                            $kategori_data = query("SELECT * FROM kategori");
                            foreach ($kategori_data as $kategori) :
                            ?>
                                <option value="<?= $kategori['id'] ?>"><?= $kategori['nama_kategori'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" name="submit_wisata" class="btn btn-primary btn-block mt-3">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Custom CSS untuk memperbaiki penataan jarak dan desain modal -->
<style>
    .modal-dialog {
        max-width: 600px;  /* Menyesuaikan lebar modal */
    }

    .modal-header {
        background-color: #007bff;
        color: white;
        text-align: center;
    }

    .modal-title {
        font-size: 1.5rem;
    }

    .modal-body {
        padding: 2rem;  /
    }

    .form-group {
        margin-bottom: 1.25rem; 
    }

    .form-group label {
        font-weight: bold;
    }

    .form-control {
        border-radius: 0.5rem;
        padding: 0.75rem;
    }

    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
    }

    .btn-primary:hover {
        background-color: #0056b3;
        border-color: #004085;
    }

    .close {
        color: white;
    }
</style>



    </div>
</div>
 
<!-- Footer -->
<?php
include_once 'templates/footer.php'; // Footer umum
?>

<!-- Sertakan script Bootstrap dan jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
