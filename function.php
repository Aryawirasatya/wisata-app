<?php
require_once('koneksi.php');

function query($query) {
    global $koneksi;
    $result = mysqli_query($koneksi, $query);
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
   return $rows;
}

// Fungsi untuk menambah data wisata
function tambah_wisata($data)
{
    global $koneksi;

    // Mengambil data dari form
    $nama_tempat   = htmlspecialchars($data["nama_tempat"] ?? '');
    $lokasi        = htmlspecialchars($data["lokasi"] ?? '');
    $deskripsi     = htmlspecialchars($data["deskripsi"] ?? '');
    $harga         = htmlspecialchars($data["harga"] ?? '');
    $rating        = htmlspecialchars($data["rating"] ?? '');
    $kontak        = htmlspecialchars($data["kontak"] ?? '');
    $jam_operasional = htmlspecialchars($data["jam_operasional"] ?? '');
    $kategori_id   = htmlspecialchars($data["kategori_id"] ?? '');

    // Proses upload gambar
    $foto = uploadGambar(); // Memanggil fungsi upload gambar
    if (!$foto) {  // Jika upload gagal
        return false;  // Kembalikan false jika foto gagal diupload
    }

    // Query untuk insert data wisata ke database
    $query = "INSERT INTO wisata (nama_tempat, lokasi, deskripsi, harga, rating, kontak, jam_operasional, foto, kategori_id)
              VALUES ('$nama_tempat', '$lokasi', '$deskripsi', '$harga', '$rating', '$kontak', '$jam_operasional', '$foto', '$kategori_id')";

    // Menjalankan query
    mysqli_query($koneksi, $query) or die(mysqli_error($koneksi));

    // Mengecek apakah ada perubahan data di database
    return mysqli_affected_rows($koneksi);
}

// Fungsi untuk menangani upload gambar
function uploadGambar()
{
    // Cek apakah ada file gambar yang diupload
    if (isset($_FILES['foto'])) {
        $file_name = $_FILES['foto']['name'];
        $file_tmp  = $_FILES['foto']['tmp_name'];
        $file_size = $_FILES['foto']['size'];
        $file_error = $_FILES['foto']['error'];

        // Validasi apakah gambar berhasil diupload
        if ($file_error === 0) {
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];  // Format file yang diizinkan

            if (in_array($file_ext, $allowed_ext)) {
                $new_file_name = uniqid() . '.' . $file_ext;  // Membuat nama file unik
                $upload_dir = 'foto/';  // Folder upload

                // Memindahkan file gambar ke folder tujuan
                if (move_uploaded_file($file_tmp, $upload_dir . $new_file_name)) {
                    return $new_file_name;  // Mengembalikan nama file jika berhasil
                } else {
                    echo "<script>alert('Gagal mengupload gambar.');</script>";
                    return false;  // Mengembalikan false jika gagal upload
                }
            } else {
                echo "<script>alert('Format gambar tidak didukung.');</script>";
                return false;
            }
        } else {
            echo "<script>alert('Terjadi kesalahan saat mengupload gambar.');</script>";
            return false;
        }
    }
    return false;  // Mengembalikan false jika tidak ada gambar yang diupload
}



function hapus_wisata($id) {
    global $koneksi;

    $query = "DELETE FROM wisata WHERE id = '$id'";

    mysqli_query($koneksi, $query);

    return mysqli_affected_rows($koneksi);
}


function edit_wisata($data) {
    global $koneksi;

    $id = $data['id'];
    $nama_tempat = htmlspecialchars($data['nama_tempat']);
    $lokasi = htmlspecialchars($data['lokasi']);
    $deskripsi = htmlspecialchars($data['deskripsi']);
    $harga = htmlspecialchars($data['harga']);
    $rating = htmlspecialchars($data['rating']);
    $kontak = htmlspecialchars($data['kontak']);
    $jam_operasional = htmlspecialchars($data['jam_operasional']);
    $kategori_id = htmlspecialchars($data['kategori_id']);

    // Cek apakah ada foto yang diupload
    if ($_FILES['foto']['error'] == 0) {
        // Jika ada, upload foto baru
        $foto = uploadGambar();  // Fungsi upload gambar yang sudah ada
    } else {
        // Jika tidak ada, gunakan foto lama yang dikirim dari form
        $foto = $data['foto_lama'];
    }

    // Update query
    $query = "UPDATE wisata SET 
                nama_tempat = '$nama_tempat', 
                lokasi = '$lokasi', 
                deskripsi = '$deskripsi', 
                harga = '$harga', 
                rating = '$rating', 
                kontak = '$kontak', 
                jam_operasional = '$jam_operasional', 
                foto = '$foto', 
                kategori_id = '$kategori_id' 
              WHERE id = '$id'";

    mysqli_query($koneksi, $query);

    return mysqli_affected_rows($koneksi);
}


function tambah_kategori($data) {
    global $koneksi;

    $nama_kategori = htmlspecialchars($data["nama_kategori"]);

    // Query untuk menambahkan data kategori
    $query = "INSERT INTO kategori (nama_kategori) VALUES ('$nama_kategori')";

    mysqli_query($koneksi, $query);

    return mysqli_affected_rows($koneksi);  // Mengembalikan jumlah baris yang terpengaruh
}


function edit_kategori($data) {
    global $koneksi;

    $id = $data["id"];
    $nama_kategori = htmlspecialchars($data["nama_kategori"]);

    // Query untuk mengupdate data kategori berdasarkan id
    $query = "UPDATE kategori SET nama_kategori = '$nama_kategori' WHERE id = '$id'";

    mysqli_query($koneksi, $query);

    return mysqli_affected_rows($koneksi);  // Mengembalikan jumlah baris yang terpengaruh
}


function hapus_kategori($id) {
    global $koneksi;

    // Query untuk menghapus kategori berdasarkan id
    $query = "DELETE FROM kategori WHERE id = '$id'";

    mysqli_query($koneksi, $query);

    return mysqli_affected_rows($koneksi);  // Mengembalikan jumlah baris yang terpengaruh
}


// Fungsi untuk mengupdate status transaksi
function update_status($id, $status) {
    global $koneksi;

    // Pastikan status yang dimasukkan valid (pending, confirmed, cancelled)
    $valid_statuses = ['pending', 'confirmed', 'cancelled'];
    if (!in_array($status, $valid_statuses)) {
        return false; // Jika status tidak valid, return false
    }

    // Query untuk mengupdate status transaksi berdasarkan ID
    $query = "UPDATE transaksi SET status = '$status' WHERE id = '$id'";

    // Menjalankan query
    mysqli_query($koneksi, $query);

    // Mengembalikan jumlah baris yang terpengaruh untuk memastikan update berhasil
    return mysqli_affected_rows($koneksi);
}

// Fungsi untuk menambah transaksi
function tambah_transaksi($data) {
    global $koneksi;

    // Cek apakah session user_id ada, jika tidak redirect ke halaman login
    if (!isset($_SESSION['user_id'])) {
        echo "<script>alert('Anda harus login terlebih dahulu.'); window.location='login.php';</script>";
        exit;
    }

    // Ambil data dari form
    $wisata_id = htmlspecialchars($data["wisata_id"]);
    $tanggal_pemesanan = htmlspecialchars($data["tanggal_pemesanan"]);
    $jumlah_tiket = htmlspecialchars($data["jumlah_tiket"]);
    $total_harga = htmlspecialchars($data["total_harga"]);
    $status = 'pending'; // Status default transaksi

    // Ambil user_id dari session yang sudah login
    $user_id = $_SESSION['user_id']; // Mengambil user_id dari session

    // Query untuk memasukkan data transaksi
    $query = "INSERT INTO transaksi (wisata_id, tanggal_pemesanan, jumlah_tiket, total_harga, status, user_id)
              VALUES ('$wisata_id', '$tanggal_pemesanan', '$jumlah_tiket', '$total_harga', '$status', '$user_id')";

    // Eksekusi query
    mysqli_query($koneksi, $query);

    return mysqli_affected_rows($koneksi);
}



function hapus_users($id) {
    global $koneksi;

    // Query untuk menghapus kategori berdasarkan id
    $query = "DELETE FROM users WHERE id = '$id'";

    mysqli_query($koneksi, $query);

    return mysqli_affected_rows($koneksi);  // Mengembalikan jumlah baris yang terpengaruh
}

function tambah_user($data) {
    global $koneksi;

    // Ambil data dari form
    $username = htmlspecialchars($data["username"]);
    $password = htmlspecialchars($data["password"]);
    $nama_lengkap = htmlspecialchars($data["nama_lengkap"]);
    $email = htmlspecialchars($data["email"]);
    $kontak = htmlspecialchars($data["kontak"]);

    // Hash password sebelum disimpan
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Set role secara otomatis menjadi 'user'
    $role = 'user';

    // Query untuk memasukkan data user
    $query = "INSERT INTO users (username, password, role, nama_lengkap, email, kontak) 
              VALUES ('$username', '$hashed_password', '$role', '$nama_lengkap', '$email', '$kontak')";

    mysqli_query($koneksi, $query);

    return mysqli_affected_rows($koneksi);
}


function edit_user($data) {
    global $koneksi;

    // Ambil data dari form
    $id = $data["id"];
    $username = htmlspecialchars($data["username"]);
    $password = htmlspecialchars($data["password"]);
    $nama_lengkap = htmlspecialchars($data["nama_lengkap"]);
    $email = htmlspecialchars($data["email"]);
    $kontak = htmlspecialchars($data["kontak"]);

    // Jika password diubah, hash password baru
    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $query = "UPDATE users SET username = '$username', password = '$hashed_password', 
                  nama_lengkap = '$nama_lengkap', email = '$email', kontak = '$kontak' 
                  WHERE id = '$id'";
    } else {
        // Jika password kosong, jangan ubah password
        $query = "UPDATE users SET username = '$username', nama_lengkap = '$nama_lengkap', 
                  email = '$email', kontak = '$kontak' WHERE id = '$id'";
    }

    mysqli_query($koneksi, $query);

    return mysqli_affected_rows($koneksi);
}


?>
