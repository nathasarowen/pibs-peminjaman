<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
    <script>
        function updateNamaPrasarana() {
            const select = document.getElementById('prasarana');
            const namaPrasarana = select.options[select.selectedIndex].getAttribute('data-nama');
            document.getElementById('nama_prasarana').value = namaPrasarana;
        }
    </script>
</head>
<body>
    <?php
        require '../database.php';
        
        // buat ambil data dropdown prasarana
        $prasaranaQuery = "SELECT id_prasarana, nama_prasarana FROM prasarana WHERE status = 'aktif'";
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
            <h4 style="color: gray;">Layanan Alat</h4>
        </div>
        <div class="icon">
            <h4 style="color: gray;">Layanan Pengajuan Dokumen</h4>
        </div>
    </nav>
    <article>
        <h2>Form Pengajuan Peminjaman</h2>
        <form action="submit_form.php" method="post" enctype="multipart/form-data">
            <!-- Data Pengajuan Peminjaman -->
            <fieldset>
                <legend>Data Pengajuan Peminjaman</legend>
                <label for="prasarana">Prasarana:</label>
                <select id="prasarana" name="id_prasarana" required onchange="updateNamaPrasarana()">
                    <option value="" data-nama="">-- Pilih Prasarana --</option>
                    <?php
                    if ($prasaranaResult && $prasaranaResult->num_rows > 0) {
                        while ($row = $prasaranaResult->fetch_assoc()) {
                            echo "<option value=\"{$row['id_prasarana']}\" data-nama=\"{$row['nama_prasarana']}\">{$row['nama_prasarana']}</option>";
                        }
                    } else {
                        echo "<option value=\"\">Tidak ada data</option>";
                    }
                    ?>
                </select>
                <input type="hidden" id="nama_prasarana" name="nama_prasarana">

                <div class="form-row">
                    <div>
                        <label for="jumlah_peserta">Jumlah Peserta:</label>
                        <input type="number" id="jumlah_peserta" name="jumlah_peserta" placeholder="Masukkan jumlah peserta" min="1" required>
                    </div>
                    <div>
                        <label for="tanggal_peminjaman">Tanggal:</label>
                        <input type="date" id="tanggal_peminjaman" name="tanggal_peminjaman" required>
                    </div>
                </div>

                <div class="form-row">
                    <div>
                        <label for="waktu_mulai">Waktu Mulai:</label>
                        <input type="time" id="waktu_mulai" name="waktu_mulai" required>
                    </div>
                    <div>
                        <label for="waktu_selesai">Waktu Selesai:</label>
                        <input type="time" id="waktu_selesai" name="waktu_selesai" required>
                    </div>
                </div>

                <label for="deskripsi">Deskripsi:</label>
                <textarea id="deskripsi" name="deskripsi" placeholder="Masukkan Deskripsi..." rows="4" required></textarea>
            </fieldset>

            <!-- Data Pengaju -->
            <fieldset>
                <legend>Data Pengaju</legend>
                <div class="form-row">
                    <div>
                        <label for="status_pengaju">Status Pengaju:</label>
                        <select id="status_pengaju" name="status_pengaju" required>
                            <option value="">-- Pilih Status --</option>
                            <option value="Mahasiswa">Mahasiswa</option>
                            <option value="Pegawai">Pegawai</option>
                        </select>
                    </div>
                    <div>
                        <label for="nim_nik">NIM/NIK:</label>
                        <input type="text" id="nim_nik" name="nim_nik" placeholder="Masukkan NIM/NIK Anda" required>
                    </div>
                </div>

                <label for="no_whatsapp">Nomor WhatsApp:</label>
                <input type="text" id="no_whatsapp" name="no_whatsapp" placeholder="Masukkan nomor WhatsApp Anda" required>
            </fieldset>

            <button type="submit">Kirim</button>
        </form>
    </article>
    <aside>
    <h2>Status Peminjaman</h2>
        <?php
            $peminjamanQuery = "SELECT nama_prasarana, status_peminjaman FROM peminjaman";
            $peminjamanResult = $conn->query($peminjamanQuery);

            if ($peminjamanResult && $peminjamanResult->num_rows > 0) {
                while ($row = $peminjamanResult->fetch_assoc()) {
                    echo "<p>{$row['nama_prasarana']}, {$row['status_peminjaman']}</p>";
                }
            } else {
                echo "<p>Belum ada data peminjaman.</p>";
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
    <script></script>
</body>
</html>