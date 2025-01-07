<?php
require '../database.php';

$message = "";

// handle tambah/rdit Prasarana
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_prasarana = $_POST['id_prasarana'];
    $nama_prasarana = $_POST['nama_prasarana'];
    $jenis_prasarana = $_POST['jenis_prasarana'];
    $deskripsi = $_POST['deskripsi'];
    $status = $_POST['status'];

    if (empty($id_prasarana)) {
        // tambah
        $query = "INSERT INTO prasarana (nama_prasarana, jenis_prasarana, deskripsi, status) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssss", $nama_prasarana, $jenis_prasarana, $deskripsi, $status);
        $stmt->execute();
        $stmt->close();
        $message = "Prasarana berhasil ditambahkan.";
    } else {
        // edit
        $query = "UPDATE prasarana SET nama_prasarana = ?, jenis_prasarana = ?, deskripsi = ?, status = ? WHERE id_prasarana = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssssi", $nama_prasarana, $jenis_prasarana, $deskripsi, $status, $id_prasarana);
        $stmt->execute();
        $stmt->close();
        $message = "Prasarana berhasil diperbarui.";
    }
}

// handle apus
if (isset($_GET['delete'])) {
    $id_prasarana = $_GET['delete'];
    $query = "DELETE FROM prasarana WHERE id_prasarana = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_prasarana);
    $stmt->execute();
    $stmt->close();
    $message = "Prasarana berhasil dihapus.";
}

// ambil data utk tabel prasarana
$prasaranaQuery = "SELECT * FROM prasarana";
$prasaranaResult = $conn->query($prasaranaQuery);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Prasarana</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Albert+Sans:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">    
</head>
<body>
    <header>
        <?php
        require '../database.php';

        // Query untuk mengambil data site_settings dengan nama_setting "header"
        $headerQuery = "SELECT nama_web, slogan_web, alamat FROM site_settings WHERE nama_setting = 'header'";
        $headerResult = $conn->query($headerQuery);

        // Validasi jika query berhasil
        if ($headerResult && $headerResult->num_rows > 0) {
            $headerData = $headerResult->fetch_assoc();
        } else {
            $headerData = [
                'nama_web' => 'Nama Web Default',
                'slogan_web' => 'Slogan Web Default',
                'alamat' => 'Alamat Default'
            ];
        }
        ?>

        <img src="../aku.jpg" alt="Logo Web" class="logo">
        <div class="header-info">
            <h1><?= htmlspecialchars($headerData['nama_web'], ENT_QUOTES, 'UTF-8'); ?></h1>
            <p style="font-weight: bold"><?= htmlspecialchars($headerData['slogan_web'], ENT_QUOTES, 'UTF-8'); ?></p>
            <p><?= htmlspecialchars($headerData['alamat'], ENT_QUOTES, 'UTF-8'); ?></p>
        </div>    
    </header>

    <nav>
        <div class="icon">
            <a href="index.php">
                <h4>Layanan Prasarana</h4>
            </a>
        </div>
        <div class="icon">
            <a href="kelola_prasarana.php">
                <h4>Kelola Prasarana</h4>
            </a>
        </div>
        <div class="icon">
            <a href="kelola_header_footer.php">
                <h4>Kelola Header & Footer</h4>
            </a>
        </div>
        <div class="icon">
            <h4 style="color: gray;">Layanan Alat</h4>
        </div>
        <div class="icon">
            <h4 style="color: gray;">Layanan Pengajuan Dokumen</h4>
        </div>
    </nav>

    <article>
        <h2>Form Kelola Prasarana</h2>
        <form action="" method="POST">
            <input type="hidden" id="id_prasarana" name="id_prasarana">

            <label for="nama_prasarana">Nama Prasarana:</label>
            <input type="text" id="nama_prasarana" name="nama_prasarana" placeholder="Masukan Nama Prasarana" required><br><br>

            <label for="jenis_prasarana">Jenis Prasarana:</label>
            <input type="text" id="jenis_prasarana" name="jenis_prasarana" placeholder="Masukan Jenis Prasarana" required><br><br>

            <label for="deskripsi">Deskripsi:</label>
            <textarea id="deskripsi" name="deskripsi" rows="4" required></textarea><br><br>

            <label for="status">Status:</label>
            <select id="status" name="status" required>
                <option value="aktif">Aktif</option>
                <option value="nonaktif">Nonaktif</option>
            </select><br><br>

            <button type="submit">Simpan</button>
        </form>

        <h2>Tabel Data Prasarana</h2>
        <table border="1" cellspacing="0" cellpadding="10">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Prasarana</th>
                <th>Jenis Prasarana</th>
                <th>Deskripsi</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($prasaranaResult->num_rows > 0): ?>
            <?php $no = 1; while ($row = $prasaranaResult->fetch_assoc()): ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= $row['nama_prasarana']; ?></td>
                    <td><?= $row['jenis_prasarana']; ?></td>
                    <td><?= $row['deskripsi']; ?></td>
                    <td><?= $row['status']; ?></td>
                    <td>
                        <div class="aksi-buttons">
                            <!-- Tombol Lihat -->
                            <a href="javascript:void(0)" 
                                onclick="lihatDetail(<?= $row['id_prasarana']; ?>)" 
                                title="Lihat">
                                <i class="fas fa-eye"></i>
                            </a>

                            <!-- Tombol Edit -->
                            <a href="javascript:void(0)" 
                                onclick="isiForm(
                                    <?= $row['id_prasarana']; ?>,
                                    '<?= htmlspecialchars($row['nama_prasarana'], ENT_QUOTES, 'UTF-8'); ?>',
                                    '<?= htmlspecialchars($row['jenis_prasarana'], ENT_QUOTES, 'UTF-8'); ?>',
                                    '<?= htmlspecialchars($row['deskripsi'], ENT_QUOTES, 'UTF-8'); ?>',
                                    '<?= htmlspecialchars($row['status'], ENT_QUOTES, 'UTF-8'); ?>'
                                )" 
                                title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>

                            <!-- Tombol Hapus -->
                            <a href="javascript:void(0)" 
                                onclick="hapusPrasarana(<?= $row['id_prasarana']; ?>)" 
                                title="Hapus">
                                    <i class="fas fa-trash"></i>
                            </a>                           
                        </div>
                    </td>
                </tr>
            <?php endwhile; ?>
            <?php else: ?>
            <tr>
                <td colspan="6">Tidak ada data prasarana</td>
            </tr>
            <?php endif; ?>
        </tbody>
        </table>
    </article>

    <aside>
        <h2>Notifikasi Peminjaman</h2>
        <?php
        $notifikasiQuery = "SELECT p.nama_prasarana, pm.status_peminjaman 
                            FROM peminjaman pm 
                            JOIN prasarana p ON pm.id_prasarana = p.id_prasarana
                            WHERE pm.status_peminjaman = 'Menunggu Verifikasi'";
        $notifikasiResult = $conn->query($notifikasiQuery);

        if ($notifikasiResult) {
            if ($notifikasiResult->num_rows > 0) {
                while ($row = $notifikasiResult->fetch_assoc()) {
                    echo "<p>{$row['nama_prasarana']}, {$row['status_peminjaman']}</p>";
                }
            } else {
                echo "<p>Tidak ada notifikasi.</p>";
            }
        } else {
            echo "<p>Error pada query notifikasi: " . $conn->error . "</p>";
        }
        ?>
    </aside>

    <footer>
        <?php
        require '../database.php';

        // Query untuk mengambil data site_settings dengan nama_setting "footer"
        $footerQuery = "SELECT email, nama_web, slogan_web FROM site_settings WHERE nama_setting = 'footer'";
        $footerResult = $conn->query($footerQuery);

        // Validasi jika query berhasil dan ada data
        if ($footerResult && $footerResult->num_rows > 0) {
            $footerData = $footerResult->fetch_assoc();
        } else {
            $footerData = [
                'email' => 'email@default.com',
                'nama_web' => 'Nama Web Default',
                'slogan_web' => 'Slogan Web Default'
            ];
        }   
        ?>
        <div class="footer-left">
            <p><?= htmlspecialchars($footerData['email'], ENT_QUOTES, 'UTF-8'); ?></p>
        </div>
        <div class="footer-center">
            <p>&copy; <?= date("Y"); ?> <?= htmlspecialchars($footerData['nama_web'], ENT_QUOTES, 'UTF-8'); ?>. All Rights Reserved.</p>
        </div>
        <div class="footer-right"> 
            <p><strong><?= htmlspecialchars($footerData['nama_web'], ENT_QUOTES, 'UTF-8'); ?></strong></p>
            <p><?= htmlspecialchars($footerData['slogan_web'], ENT_QUOTES, 'UTF-8'); ?></p>
        </div>
    </footer>

    <script>
        function lihatDetail(id) {
            fetch(`get_detail_prasarana.php?id=${id}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.error) {
                    alert(data.error); // Menampilkan error jika ada
                } else {
                    const popupContent = `
                        <h3>Detail Prasarana</h3>
                        <p><strong>Nama Prasarana:</strong> ${data.nama_prasarana}</p>
                        <p><strong>Jenis Prasarana:</strong> ${data.jenis_prasarana}</p>
                        <p><strong>Deskripsi:</strong> ${data.deskripsi}</p>
                        <p><strong>Status:</strong> ${data.status}</p>
                        <button onclick="closePopup()">Tutup</button>
                    `;
                    document.getElementById('popup').innerHTML = popupContent;
                    document.getElementById('popup-container').style.display = 'block';
                }
            })
            .catch(error => {
                alert("Terjadi kesalahan saat memuat data.");
                console.error("Fetch error:", error);
            });
        }

        function closePopup() {
            document.getElementById('popup-container').style.display = 'none'; // Menyembunyikan popup
        }

        function isiForm(id, nama, jenis, deskripsi, status) {
            document.getElementById('id_prasarana').value = id;
            document.getElementById('nama_prasarana').value = nama;
            document.getElementById('jenis_prasarana').value = jenis;
            document.getElementById('deskripsi').value = deskripsi;
            document.getElementById('status').value = status;
        }

        function hapusPrasarana(id) {
            if (confirm("Apakah Anda yakin ingin menghapus data ini?")) {
                window.location.href = `kelola_prasarana.php?delete=${id}`;
            }
        }
        
    </script>
    <div id="popup-container">
    <div id="popup">
        <!-- Konten popup akan diisi oleh JavaScript -->
    </div>
    </div>        
</body>
</html>
