<?php
// Menyertakan file fungsi yang diperlukan
require_once 'function.php';
require_once 'templates/header.php'; // Header umum, pastikan file header ada

// Memeriksa apakah form telah disubmit
if (isset($_POST['submit_wisata'])) {
    // Memanggil fungsi tambah_wisata dan mengirimkan data
    if (tambah_wisata($_POST)) {
        echo "<script>alert('Data wisata berhasil disimpan!'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Terjadi kesalahan saat menyimpan data wisata.');</script>";
    }
}
?>

<div class="layout-page">
    <!-- Container utama -->
    <div class="container mt-5">
        <h2 class="mb-4 text-center">Daftar Tempat Wisata</h2>
    
        <!-- Tombol Tambah Wisata -->
        <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#addWisataModal">Tambah Wisata</button>
    
        <!-- Tabel Daftar Wisata -->
        <table class="table table-striped table-bordered" id="dataTable" width="100%" cellspacing="0">
            <thead class="thead-dark">
                <tr>
                    <th>No</th>
                    <th>Nama Tempat</th>
                    <th>Lokasi</th>
                    <th>Deskripsi</th>
                    <th>Harga</th>
                    <th>Rating</th>
                    <th>Kategori</th>
                    <th>Kontak</th>
                    <th>Jam Operasional</th>
                    <th>Foto</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                $data_wisata = query("SELECT wisata.*, kategori.nama_kategori FROM wisata 
                JOIN kategori ON wisata.kategori_id = kategori.id");
                foreach ($data_wisata as $wisata) : ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= $wisata['nama_tempat'] ?></td>
                        <td><?= $wisata['lokasi'] ?></td>
                        <td><?= $wisata['deskripsi'] ?></td>
                        <td><?= number_format($wisata['harga'], 0, ',', '.') ?></td>
                        <td><?= number_format($wisata['rating'], 1, ',', '.') ?></td>
                        <td><?= $wisata['nama_kategori'] ?></td>
                        <td><?= $wisata['kontak'] ?></td>
                        <td><?= $wisata['jam_operasional'] ?></td>
                        <td>
                            <img src="foto/<?= $wisata['foto']; ?>" alt="Foto Tempat Wisata" class="img-thumbnail" width="100">
                        </td>
                        <td>
                            <a class="btn btn-success" href="edit-wisata.php?id=<?= $wisata['id'] ?>">Ubah</a>
                            <a onclick="return confirm('Apakah anda yakin ingin menghapus data ini?')" class="btn btn-danger" href="hapus-wisata.php?id=<?= $wisata['id'] ?>">Hapus</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    
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
                                <label for="rating">Rating</label>
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
                            <button type="submit" name="submit_wisata" class="btn btn-primary">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<?php
include_once 'templates/footer.php'; // Footer umum, pastikan file footer ada
?>

<!-- Sertakan script Bootstrap dan jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

