<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body>
    <?php
        require '../database.php';

        $message = "";

        //edit
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id_peminjaman = $_POST['id_peminjaman'];
            $id_prasarana = $_POST['id_prasarana'];
            $tanggal_peminjaman = $_POST['tanggal_peminjaman'];
            $waktu_mulai = $_POST['waktu_mulai'];
            $waktu_selesai = $_POST['waktu_selesai'];
            $status_peminjaman = $_POST['status_peminjaman'];

            if (!empty($id_peminjaman)) {            
                $query = "UPDATE peminjaman SET id_prasarana = ?, tanggal_peminjaman = ?, waktu_mulai = ?, waktu_selesai = ?, status_peminjaman = ? WHERE id_peminjaman = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("issssi", $id_prasarana, $tanggal_peminjaman, $waktu_mulai, $waktu_selesai, $status_peminjaman, $id_peminjaman);
                $stmt->execute();
                $stmt->close();
                $message = "Perubahan berhasil disimpan.";
            } else {
                $message = "ID peminjaman tidak valid!";
            }
        }

        //apus
        if (isset($_GET['delete'])) {
            $id_peminjaman = $_GET['delete'];
            $query = "DELETE FROM peminjaman WHERE id_peminjaman = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $id_peminjaman);
            $stmt->execute();
            $stmt->close();
            $message = "Data berhasil dihapus.";
        }

        //ambil data buat tabel peminjaman
        $peminjamanQuery = "SELECT pm.id_peminjaman, pm.tanggal_peminjaman, pm.waktu_mulai, pm.waktu_selesai, pm.status_peminjaman, p.nama_prasarana, p.id_prasarana 
                            FROM peminjaman pm 
                            JOIN prasarana p ON pm.id_prasarana = p.id_prasarana";
        $peminjamanResult = $conn->query($peminjamanQuery);

        //ambil data buat dropdown prasarana (edit)
        $prasaranaQuery = "SELECT * FROM prasarana WHERE status = 'aktif'";
        $prasaranaResult = $conn->query($prasaranaQuery);
        ?>
    <header>
        <?php
        require '../database.php';

        //query buat ambil data site_settings dgn nama_setting "header"
        $headerQuery = "SELECT nama_web, slogan_web, alamat FROM site_settings WHERE nama_setting = 'header'";
        $headerResult = $conn->query($headerQuery);

        //validasi jika query berhasil
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
        <!-- Form Edit -->
        <h2>Form Edit Peminjaman</h2>
        <form action="" method="POST" id="editForm">
            <input type="hidden" id="id_peminjaman" name="id_peminjaman">
        
            <label for="id_prasarana">Prasarana:</label>
            <select id="id_prasarana" name="id_prasarana" disabled>
                <option value="">-- Pilih Prasarana --</option>
                <?php while ($prasarana = $prasaranaResult->fetch_assoc()): ?>
                    <option value="<?= $prasarana['id_prasarana']; ?>">
                        <?= $prasarana['nama_prasarana']; ?>
                    </option>
                <?php endwhile; ?>
            </select><br>

            <div class="form-row">
                <div>
                    <label for="tanggal_peminjaman">Tanggal:</label>
                    <input type="date" id="tanggal_peminjaman" name="tanggal_peminjaman" disabled>
                </div>
                <div>
                    <label for="waktu_mulai">Waktu Mulai:</label>
                    <input type="time" id="waktu_mulai" name="waktu_mulai" disabled>
                </div>
                <div>
                    <label for="waktu_selesai">Waktu Selesai:</label>
                    <input type="time" id="waktu_selesai" name="waktu_selesai" disabled>
                </div>
            </div>

            <label for="status_peminjaman">Status Peminjaman:</label>
            <select id="status_peminjaman" name="status_peminjaman" disabled>
                <option value="Menunggu Verifikasi">Menunggu Verifikasi</option>
                <option value="Dipesan">Dipesan</option>
                <option value="Ditolak">Ditolak</option>
            </select><br>

            <button type="submit" id="submitButton" disabled>Simpan Perubahan</button>
        </form>

        <!-- Tabel Data Peminjaman -->
        <h2>Tabel Data Peminjaman</h2>
        <table border="1" cellspacing="0" cellpadding="10">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Prasarana</th>
                    <th>Tanggal</th>
                    <th>Waktu</th>
                    <th>Status Peminjaman</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($peminjamanResult->num_rows > 0): ?>
                <?php $no = 1; while ($row = $peminjamanResult->fetch_assoc()): ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= $row['nama_prasarana']; ?></td>
                    <td><?= $row['tanggal_peminjaman']; ?></td>
                    <td><?= $row['waktu_mulai'] . ' - ' . $row['waktu_selesai']; ?></td>
                    <td><?= $row['status_peminjaman']; ?></td>
                    <td>
                        <div class="aksi-buttons">
                        <!-- Tombol Lihat -->
                        <a href="javascript:void(0)" onclick="lihatData(<?= $row['id_peminjaman']; ?>)" title="Lihat">
                            <i class="fas fa-eye"></i>
                        </a>        
                            <!-- Tombol Edit -->
                            <a href="javascript:void(0)" onclick="isiForm(
                                <?= $row['id_peminjaman']; ?>,
                                <?= $row['id_prasarana']; ?>,
                                '<?= $row['tanggal_peminjaman']; ?>',
                                '<?= $row['waktu_mulai']; ?>',
                                '<?= $row['waktu_selesai']; ?>',
                                '<?= $row['status_peminjaman']; ?>'
                            )" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            
                            <!-- Tombol Hapus -->
                            <a href="javascript:void(0)" onclick="hapusData(<?= $row['id_peminjaman']; ?>)" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endwhile; ?>
                <?php else: ?>
                <tr>
                    <td colspan="6">Tidak ada data peminjaman</td>
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

            //query buat ambil data site_settings dgn nama_setting "footer"
            $footerQuery = "SELECT email, nama_web, slogan_web FROM site_settings WHERE nama_setting = 'footer'";
            $footerResult = $conn->query($footerQuery);

            //validasi jika query berhasil dan ada data
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
        function lihatData(id) {
            fetch(`get_detail.php?id=${id}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.error) {
                        alert(data.error);
                    } else {
                        const popupContent = `
                            <h3>Detail Peminjaman</h3>
                            <p><strong>Nama Prasarana:</strong> ${data.nama_prasarana}</p>
                            <p><strong>Jumlah Peserta:</strong> ${data.jumlah_peserta}</p>
                            <p><strong>Tanggal:</strong> ${data.tanggal_peminjaman}</p>
                            <p><strong>Waktu:</strong> ${data.waktu_mulai} - ${data.waktu_selesai}</p>
                            <p><strong>Status Pengaju:</strong> ${data.status_pengaju}</p>
                            <p><strong>Status Peminjaman:</strong> ${data.status_peminjaman}</p>
                            <p><strong>Deskripsi:</strong> ${data.deskripsi}</p>
                        `;
                        document.getElementById('popup').innerHTML = popupContent + `<button onclick="closePopup()" style="margin-top: 20px;">Tutup</button>`;
                        document.getElementById('popup-container').style.display = 'block';
                    }
                })
                .catch(error => {
                    alert("Terjadi kesalahan saat memuat data.");
                    console.error("Fetch error:", error);
                });
        }

        function closePopup() {
            document.getElementById('popup-container').style.display = 'none';
        }

        function isiForm(id, prasarana, tanggal, mulai, selesai, status) {
            document.getElementById('id_peminjaman').value = id;
            document.getElementById('id_prasarana').value = prasarana;
            document.getElementById('tanggal_peminjaman').value = tanggal;
            document.getElementById('waktu_mulai').value = mulai;
            document.getElementById('waktu_selesai').value = selesai;
            document.getElementById('status_peminjaman').value = status;

            document.getElementById('id_prasarana').disabled = false;
            document.getElementById('tanggal_peminjaman').disabled = false;
            document.getElementById('waktu_mulai').disabled = false;
            document.getElementById('waktu_selesai').disabled = false;
            document.getElementById('status_peminjaman').disabled = false;
            document.getElementById('submitButton').disabled = false;

            document.getElementById('editForm').scrollIntoView({ behavior: "smooth" });
        }

        function hapusData(id) {
            if (confirm("Apakah Anda yakin ingin menghapus data ini?")) {
                window.location.href = `index.php?delete=${id}`;
            }
        }
    </script>
    <div id="popup-container" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000;">
    <div id="popup" style="background: #fff; padding: 20px; margin: 100px auto; width: 50%; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.5);">        
        <button onclick="closePopup()" style="float: right;">Tutup</button>
    </div>
</body>
</html>