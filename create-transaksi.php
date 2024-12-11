<?php
session_start(); // Memulai sesi

require_once 'function.php';

// Memeriksa apakah form telah disubmit
if (isset($_POST['submit_transaksi'])) {
    // Memanggil fungsi tambah_transaksi dan mengirimkan data
    if (tambah_transaksi($_POST)) {
        echo "<script>alert('Transaksi berhasil disimpan!'); window.location='status-transaksi.php';</script>";
    } else {
        echo "<script>alert('Terjadi kesalahan saat menyimpan transaksi.');</script>";
    }
}

if ($_SESSION['role'] != "user") { header("Location: index.php"); exit; }


// Ambil data wisata untuk dropdown
$data_wisata = query("SELECT * FROM wisata");

// Menambahkan include untuk header
include_once 'templates/header.php';
?>

<!-- Halaman Tambah Transaksi -->
<div class="layout-page mt-5">
    <div class="container">
        <h2 class="mb-4 text-center">buat pesanan</h2>

        <!-- Form Tambah Transaksi -->
        <form action="" method="POST">
            <!-- Dropdown untuk memilih Wisata -->
            <div class="form-group">
                <label for="wisata_id">Pilih Wisata</label>
                <select class="form-control" id="wisata_id" name="wisata_id" required onchange="updateHarga()">
                    <option value="">Pilih Wisata</option>
                    <?php foreach ($data_wisata as $wisata) : ?>
                        <option value="<?= $wisata['id'] ?>" data-harga="<?= $wisata['harga'] ?>"><?= $wisata['nama_tempat'] ?> (<?= $wisata['lokasi'] ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
<br>
            <!-- Input untuk jumlah tiket -->
            <div class="form-group">
                <label for="jumlah_tiket">Jumlah Tiket</label>
                <input type="number" class="form-control" id="jumlah_tiket" name="jumlah_tiket" required min="1" oninput="updateHarga()">
            </div>
            <br>

            <!-- Input untuk tanggal pemesanan -->
   
            <div class="form-group">
                <label for="tanggal_pemesanan">Tanggal Pemesanan</label>
                <input type="date" class="form-control" id="tanggal_pemesanan" name="tanggal_pemesanan" value="<?php echo date('Y-m-d'); ?>" readonly>
            </div>

            <br>

            <!-- Input untuk total harga -->
            <div class="form-group">
                <label for="total_harga">Total Harga</label>
                <input type="number" class="form-control" id="total_harga" name="total_harga" required step="0.01" readonly>
            </div>
            <br>

            <!-- Submit button -->
            <button type="submit" name="submit_transaksi" class="btn btn-primary btn-block">Simpan Transaksi</button>
        </form>
    </div>
</div>

<script>
    // Fungsi untuk update harga total saat memilih wisata atau jumlah tiket
    function updateHarga() {
        var wisataSelect = document.getElementById('wisata_id');
        var jumlahTiket = document.getElementById('jumlah_tiket').value;
        var totalHargaField = document.getElementById('total_harga');
        
        // Ambil harga dari opsi wisata yang dipilih
        var hargaPerTiket = wisataSelect.options[wisataSelect.selectedIndex].getAttribute('data-harga');
        
        // Jika harga dan jumlah tiket ada, hitung total harga
        if (hargaPerTiket && jumlahTiket) {
            var totalHarga = hargaPerTiket * jumlahTiket;
            totalHargaField.value = totalHarga.toFixed(2); // Format 2 angka di belakang koma
        } else {
            totalHargaField.value = 0; // Set total harga ke 0 jika input tidak lengkap
        }
    }
</script>

<?php
// Menambahkan include untuk footer
include_once 'templates/footer.php';
?>
