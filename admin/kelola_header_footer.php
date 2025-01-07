<?php
require '../database.php';

$message = "";

// handle update Header/Footer
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_setting = $_POST['nama_setting'];
    $nama_web = $_POST['nama_web'];
    $slogan_web = $_POST['slogan_web'];
    $alamat = $_POST['alamat'] ?? null;
    $email = $_POST['email'] ?? null;

    //update
    $query = "UPDATE site_settings SET nama_web = ?, slogan_web = ?, alamat = ?, email = ? WHERE nama_setting = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssss", $nama_web, $slogan_web, $alamat, $email, $nama_setting);
    $stmt->execute();
    $stmt->close();

    // $message = ucfirst($nama_setting) . " berhasil diperbarui.";
}

// ambil data header
$headerQuery = "SELECT nama_web, slogan_web, alamat FROM site_settings WHERE nama_setting = 'header'";
$headerResult = $conn->query($headerQuery);
$headerData = $headerResult ? $headerResult->fetch_assoc() : [
    'nama_web' => 'Nama Web Default',
    'slogan_web' => 'Slogan Default',
    'alamat' => 'Alamat Default'
];

// ambil data footer
$footerQuery = "SELECT nama_web, slogan_web, email FROM site_settings WHERE nama_setting = 'footer'";
$footerResult = $conn->query($footerQuery);
$footerData = $footerResult ? $footerResult->fetch_assoc() : [
    'nama_web' => 'Nama Web Default',
    'slogan_web' => 'Slogan Default',
    'email' => 'email@default.com'
];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Header & Footer</title>
    <link href="https://fonts.googleapis.com/css2?family=Albert+Sans:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header>
    <img src="../aku.jpg" alt="Logo Web" class="logo">
    <div class="header-info">
        <h1><?= htmlspecialchars($headerData['nama_web'] ?? 'Nama Web Default', ENT_QUOTES, 'UTF-8'); ?></h1>
        <p><?= htmlspecialchars($headerData['slogan_web'] ?? 'Slogan Default', ENT_QUOTES, 'UTF-8'); ?></p>
        <p><?= htmlspecialchars($headerData['alamat'] ?? 'Alamat Default', ENT_QUOTES, 'UTF-8'); ?></p>
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
    <?php if (!empty($message)): ?>
        <div class="notification"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></div>
    <?php endif; ?>
    
    <h2>Kelola Header</h2>
    <form action="" method="POST">
        <input type="hidden" name="nama_setting" value="header">

        <label for="nama_web">Nama Web:</label>
        <input type="text" id="nama_web" name="nama_web" value="<?= htmlspecialchars($headerData['nama_web'], ENT_QUOTES, 'UTF-8'); ?>" required><br>

        <label for="slogan_web">Slogan Web:</label>
        <input type="text" id="slogan_web" name="slogan_web" value="<?= htmlspecialchars($headerData['slogan_web'], ENT_QUOTES, 'UTF-8'); ?>" required><br>

        <label for="alamat">Alamat:</label>
        <input type="text" id="alamat" name="alamat" value="<?= htmlspecialchars($headerData['alamat'], ENT_QUOTES, 'UTF-8'); ?>" required><br>

        <button type="submit">Simpan Perubahan Header</button>
    </form>

    <h2>Kelola Footer</h2>
    <form action="" method="POST">
        <input type="hidden" name="nama_setting" value="footer">

        <label for="nama_web">Nama Web:</label>
        <input type="text" id="nama_web" name="nama_web" value="<?= htmlspecialchars($footerData['nama_web'], ENT_QUOTES, 'UTF-8'); ?>" required><br>

        <label for="slogan_web">Slogan Web:</label>
        <input type="text" id="slogan_web" name="slogan_web" value="<?= htmlspecialchars($footerData['slogan_web'], ENT_QUOTES, 'UTF-8'); ?>" required><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($footerData['email'], ENT_QUOTES, 'UTF-8'); ?>" required><br>

        <button type="submit">Simpan Perubahan Footer</button>
    </form>
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
    <div class="footer-left">
        <p><?= htmlspecialchars($footerData['email'] ?? 'email@default.com', ENT_QUOTES, 'UTF-8'); ?></p>
    </div>
    <div class="footer-center">
        <p>&copy; <?= date("Y"); ?> <?= htmlspecialchars($footerData['nama_web'] ?? 'Nama Web Default', ENT_QUOTES, 'UTF-8'); ?>. All Rights Reserved.</p>
    </div>
    <div class="footer-right"> 
        <p><strong><?= htmlspecialchars($footerData['nama_web'] ?? 'Nama Web Default', ENT_QUOTES, 'UTF-8'); ?></strong></p>
        <p><?= htmlspecialchars($footerData['slogan_web'] ?? 'Slogan Default', ENT_QUOTES, 'UTF-8'); ?></p>
    </div>
</footer>
</body>
</html>
