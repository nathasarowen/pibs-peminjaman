<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php
        require '../database.php';
        
        //buat crud nya
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
    <nav></nav>
    <article></article>
    <aside></aside>
    <footer></footer>
    <script></script>
</body>
</html>