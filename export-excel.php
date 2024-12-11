<?php
if ($_SESSION['role'] != "admin") { header("Location: index.php"); exit; }
include 'koneksi.php';
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$sheet->setCellValue('A1', 'No');
$sheet->setCellValue('B1', 'TANGGAL PEMESANAN');
$sheet->setCellValue('C1', 'JUMLAH TIKET');
$sheet->setCellValue('D1', 'TOTAL HARGA');
$sheet->setCellValue('E1', 'STATUS');
$sheet->setCellValue('F1', 'TANGGAL DIBUAT');

if (isset($_GET['cari'])) {
    $p_awal = $_GET['p_awal'];
    $p_akhir = $_GET['p_akhir'];
    $data = mysqli_query($koneksi, "
        SELECT t.*, w.nama_tempat 
        FROM transaksi t
        JOIN wisata w ON t.wisata_id = w.id
        WHERE t.tanggal_pemesanan BETWEEN '$p_awal' AND '$p_akhir'
        ORDER BY t.tanggal_pemesanan DESC
    ");
} else {
    $data = mysqli_query($koneksi, "
        SELECT t.*, w.nama_tempat 
        FROM transaksi t
        JOIN wisata w ON t.wisata_id = w.id
        ORDER BY t.tanggal_pemesanan DESC
    ");
}

$i = 2;
$no = 1;
while ($d = mysqli_fetch_array($data)) {
    $sheet->setCellValue('A' . $i, $no++);
    $sheet->setCellValue('B' . $i, $d['tanggal_pemesanan']);
    $sheet->setCellValue('C' . $i, $d['jumlah_tiket']);
    $sheet->setCellValue('D' . $i, $d['total_harga']);
    $sheet->setCellValue('E' . $i, $d['status']);
    $sheet->setCellValue('F' . $i, $d['created_at']);
    $i++;
}

$writer = new Xlsx($spreadsheet);
$writer->save('Laporan wisata.xlsx');
echo "<script>window.location = 'Laporan wisata.xlsx'</script>";
