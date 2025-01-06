# Host: localhost  (Version 5.5.5-10.4.32-MariaDB)
# Date: 2024-12-30 18:48:04
# Generator: MySQL-Front 6.0  (Build 2.20)


#
# Structure for table "prasarana"
#

DROP TABLE IF EXISTS `prasarana`;
CREATE TABLE `prasarana` (
  `id_prasarana` int(11) NOT NULL AUTO_INCREMENT,
  `nama_prasarana` varchar(100) NOT NULL,
  `jenis_prasarana` varchar(50) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `status` enum('aktif','nonaktif') DEFAULT 'aktif',
  PRIMARY KEY (`id_prasarana`),
  KEY `idx_nama_prasarana` (`nama_prasarana`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

#
# Data for table "prasarana"
#

INSERT INTO `prasarana` VALUES (1,'Gedung B Lantai 3 - Aula','Aula','Aula serbaguna untuk acara formal dan informal.','aktif'),(2,'Gedung A Lantai 6 - A606 Ruang Teori','Kelas','Ruang teori dengan kapasitas besar dan fasilitas lengkap.','aktif'),(3,'Gedung A Lantai 4 - Laboratorium Broadcasting','LAB FHB','Laboratorium untuk kegiatan penyiaran dan produksi media.','aktif'),(4,'Gedung B Lantai 5 - LAB SIF 504','LAB FTD','Laboratorium untuk desain teknik dan fabrikasi.','aktif'),(5,'Gedung B Lantai 8 - Lab Komputer B803','LAB Komputer','Laboratorium komputer dengan koneksi internet cepat.','aktif'),(6,'Gedung B Lantai 4 - Ruang Informasi','Perpustakaan','Ruang perpustakaan untuk belajar mandiri dan kelompok.','aktif'),(7,'Gedung B Lantai 1 - Ruang Kemahasiswaan 1 B101','Ruang Kemahasiswaan','Ruang untuk kegiatan organisasi mahasiswa.','aktif'),(8,'Gedung A Lantai 2 - Mezzanine 1','Ruang Rapat','Ruang rapat dengan fasilitas proyektor dan AC.','aktif'),(9,'Gedung A Lantai 9 - Rooftop','Ruang Terbuka','Rooftop untuk kegiatan santai dan informal.','aktif'),(10,'Gedung A Lantai 4 - Teater 1','Teater','Teater untuk kegiatan seni dan pertunjukan.','aktif'),(11,'Gedung B Lantai 6 - Student Lounge','Ruang Rapat','Ruang Rapat berkapasitas besar dengan fasilitas AC','aktif');

#
# Structure for table "peminjaman"
#

DROP TABLE IF EXISTS `peminjaman`;
CREATE TABLE `peminjaman` (
  `id_peminjaman` int(11) NOT NULL AUTO_INCREMENT,
  `id_prasarana` int(11) NOT NULL,
  `nama_prasarana` varchar(255) DEFAULT NULL,
  `nim_nik` varchar(50) NOT NULL,
  `jumlah_peserta` int(11) NOT NULL,
  `tanggal_peminjaman` date NOT NULL,
  `waktu_mulai` time NOT NULL,
  `waktu_selesai` time NOT NULL,
  `deskripsi` text NOT NULL,
  `status_peminjaman` enum('Menunggu Verifikasi','Dipesan','Ditolak','Selesai') NOT NULL DEFAULT 'Menunggu Verifikasi',
  `status_pengaju` enum('Mahasiswa','Pegawai') NOT NULL,
  `no_whatsapp` varchar(15) NOT NULL,
  PRIMARY KEY (`id_peminjaman`),
  KEY `id_prasarana` (`id_prasarana`),
  KEY `idx_tanggal_peminjaman` (`tanggal_peminjaman`),
  KEY `idx_nim_nik` (`nim_nik`),
  KEY `idx_status_peminjaman` (`status_peminjaman`),
  CONSTRAINT `peminjaman_ibfk_1` FOREIGN KEY (`id_prasarana`) REFERENCES `prasarana` (`id_prasarana`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

#
# Data for table "peminjaman"
#

INSERT INTO `peminjaman` VALUES (11,2,'Gedung A Lantai 6 - A606 Ruang Teori','2023081056',20,'2024-12-27','12:10:00','15:10:00','mau buat ngecek aja','Dipesan','Mahasiswa','08871729071'),(12,7,'Gedung B Lantai 1 - Ruang Kemahasiswaan 1 B101','2023081011',15,'2024-12-27','05:25:00','06:26:00','cek lagi yaa','Menunggu Verifikasi','Pegawai','08871729072'),(14,8,'Gedung A Lantai 2 - Mezzanine 1','2023081077',11,'2024-12-28','08:15:00','10:15:00','mau cek lagi ya','Menunggu Verifikasi','Mahasiswa','08871729072'),(15,4,'Gedung B Lantai 5 - LAB SIF 504','2023081014',10,'2024-12-30','14:52:00','15:52:00','ya sama aja buat cek juga','Ditolak','Mahasiswa','08871729075'),(16,10,'Gedung A Lantai 4 - Teater 1','2023081011',50,'2024-12-30','18:40:00','19:40:00','yayaya apalah','Ditolak','Mahasiswa','08871729071');

#
# Structure for table "site_settings"
#

DROP TABLE IF EXISTS `site_settings`;
CREATE TABLE `site_settings` (
  `id_setting` int(11) NOT NULL AUTO_INCREMENT,
  `nama_setting` enum('Header','Footer') NOT NULL,
  `nama_web` varchar(100) DEFAULT NULL,
  `slogan_web` varchar(255) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_setting`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

#
# Data for table "site_settings"
#

INSERT INTO `site_settings` VALUES (1,'Header','Aplikasi Peminjaman','Ini slogan hanya untuk cek saja','Jl. Cendrawasih Raya Bintaro Jaya, Sawah Baru, Kec. Ciputat, Kota Tangerang Selatan, Banten','aplikasi.peminjaman@gmail.com'),(2,'Footer','Aplikasi Peminjaman','Ini slogan hanya untuk cek saja','Jl. Cendrawasih Raya Bintaro Jaya, Sawah Baru, Kec. Ciputat, Kota Tangerang Selatan, Banten','aplikasi.peminjaman@gmail.com');

#
# Structure for table "users"
#

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `nim_nik` varchar(50) NOT NULL,
  `nama_user` varchar(100) NOT NULL,
  `no_whatsapp` varchar(15) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  PRIMARY KEY (`nim_nik`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

#
# Data for table "users"
#

INSERT INTO `users` VALUES ('20231001','Budi Santoso','081234567890','budi.santoso@example.com','482c811da5d5b4bc6d497ffa98491e38',''),('20231002','Ani Setiawan','082134567891','ani.setiawan@example.com','482c811da5d5b4bc6d497ffa98491e38',''),('20231003','Citra Dewi','083134567892','citra.dewi@example.com','482c811da5d5b4bc6d497ffa98491e38',''),('20231004','Dedi Pratama','084134567893','dedi.pratama@example.com','482c811da5d5b4bc6d497ffa98491e38',''),('20231005','Eka Rahmat','085134567894','eka.rahmat@example.com','482c811da5d5b4bc6d497ffa98491e38','admin');
