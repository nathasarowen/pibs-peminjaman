<?php
// Pastikan file ini hanya di-include sekali
if (!defined('DATABASE_INCLUDED')) {
    define('DATABASE_INCLUDED', true);

    // Informasi Koneksi Database
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "peminjaman_pibs";

    // Membuat Koneksi
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Validasi Koneksi
    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error);
    }

    // Fungsi untuk Mendapatkan Data Site Settings
    if (!function_exists('getSiteSettings')) {
        function getSiteSettings($settingName) {
            global $conn;
            $query = "SELECT * FROM site_settings WHERE nama_setting = ? LIMIT 1";
            $stmt = $conn->prepare($query);
            if ($stmt) {
                $stmt->bind_param("s", $settingName);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result->num_rows > 0) {
                    return $result->fetch_assoc();
                }
            } else {
                echo "Kesalahan Query: " . $conn->error;
            }
            return null;
        }
    }

    // Fungsi untuk Mendapatkan Data Peminjaman
    if (!function_exists('getPeminjaman')) {
        function getPeminjaman() {
            global $conn;
            $query = "SELECT p.nama_prasarana, pm.status_peminjaman 
                      FROM peminjaman pm 
                      JOIN prasarana p ON pm.id_prasarana = p.id_prasarana";
            $result = $conn->query($query);
            if ($result) {
                $data = [];
                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }
                return $data;
            } else {
                echo "Kesalahan Query: " . $conn->error;
            }
            return [];
        }
    }
}
?>
