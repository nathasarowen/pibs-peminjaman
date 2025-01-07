<?php
require '../database.php';

// Ambil data dari form
$id_prasarana = $_POST['id_prasarana'] ?? null;
$nama_prasarana = $_POST['nama_prasarana'] ?? null;
$jumlah_peserta = $_POST['jumlah_peserta'] ?? null;
$tanggal_peminjaman = $_POST['tanggal_peminjaman'] ?? null;
$waktu_mulai = $_POST['waktu_mulai'] ?? null;
$waktu_selesai = $_POST['waktu_selesai'] ?? null;
$deskripsi = $_POST['deskripsi'] ?? null;
$status_pengaju = $_POST['status_pengaju'] ?? null;
$nim_nik = $_POST['nim_nik'] ?? null;
$no_whatsapp = $_POST['no_whatsapp'] ?? null;

// Validasi data wajib
if (!$id_prasarana || !$nama_prasarana || !$jumlah_peserta || !$tanggal_peminjaman || !$waktu_mulai || !$waktu_selesai || !$deskripsi || !$status_pengaju || !$nim_nik || !$no_whatsapp) {
    die("Semua data wajib diisi!");
}

// Query untuk menyimpan data ke tabel peminjaman
$query = "INSERT INTO peminjaman 
          (id_prasarana, nama_prasarana, jumlah_peserta, tanggal_peminjaman, waktu_mulai, waktu_selesai, deskripsi, status_pengaju, nim_nik, no_whatsapp, status_peminjaman) 
          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Menunggu Verifikasi')";

$stmt = $conn->prepare($query);
$stmt->bind_param("isisssssss", $id_prasarana, $nama_prasarana, $jumlah_peserta, $tanggal_peminjaman, $waktu_mulai, $waktu_selesai, $deskripsi, $status_pengaju, $nim_nik, $no_whatsapp);

if ($stmt->execute()) {
    echo "Data berhasil disimpan!";
    header("Location: index.php");
} else {
    echo "Terjadi kesalahan: " . $conn->error;
}

$stmt->close();
$conn->close();
?>
