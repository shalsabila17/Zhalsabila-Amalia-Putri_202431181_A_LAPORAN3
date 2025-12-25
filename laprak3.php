<?php

// Menampilkan error PHP agar mudah saat pengembangan
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);                                           

// Variabel penanda apakah hasil penilaian sudah diproses
$hasil = false;

// Variabel untuk menyimpan pesan error validasi
$error = "";


// CEK APAKAH FORM DISUBMIT (POST)

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Mengambil data dari form dan menghilangkan spasi di awal/akhir
    $nama       = trim($_POST['nama'] ?? '');
    $nim        = trim($_POST['nim'] ?? '');
    $kehadiran  = $_POST['kehadiran'] ?? '';
    $tugas      = $_POST['tugas'] ?? '';
    $uts        = $_POST['uts'] ?? '';
    $uas        = $_POST['uas'] ?? '';

   
    // VALIDASI INPUT KOSONG

    // Jika SEMUA kolom kosong
    if (
        $nama === '' && $nim === '' &&
        $kehadiran === '' && $tugas === '' &&
        $uts === '' && $uas === ''
    ) {
        $error = "Semua kolom harus terisi!";
    }

    // Jika hanya Nama dan NIM yang kosong (nilai sudah diisi)
    elseif (
        $nama === '' && $nim === '' &&
        $kehadiran !== '' && $tugas !== '' &&
        $uts !== '' && $uas !== ''
    ) {
        $error = "Kolom Nama dan NIM harus terisi!";
    }

    // Jika ada salah satu kolom yang masih kosong
    elseif (
        $nama === '' || $nim === '' ||
        $kehadiran === '' || $tugas === '' ||
        $uts === '' || $uas === ''
    ) {
        $error = "Semua kolom harus terisi!";
    }
  

    // JIKA VALIDASI LOLOS
    else {

        // Mengubah nilai ke tipe angka (float)
        $kehadiran = (float)$kehadiran;
        $tugas     = (float)$tugas;
        $uts       = (float)$uts;
        $uas       = (float)$uas;

        
        // PERHITUNGAN NILAI AKHIR
        // Bobot: Kehadiran 10%, Tugas 20%, UTS 30%, UAS 40%

        $nilai_akhir =
            ($kehadiran * 0.10) +
            ($tugas * 0.20) +
            ($uts * 0.30) +
            ($uas * 0.40);

    
        // PENENTUAN GRADE
        
        if ($nilai_akhir >= 85) {
            $grade = "A";
        } elseif ($nilai_akhir >= 70) {
            $grade = "B";
        } elseif ($nilai_akhir >= 55) {
            $grade = "C";
        } elseif ($nilai_akhir >= 40) {
            $grade = "D";
        } else {
            $grade = "E";
        }

        
        // PENENTUAN KELULUSAN
        // Syarat lulus:
        // - Nilai akhir >= 60
        // - Kehadiran > 70
        // - Nilai tugas, UTS, dan UAS >= 40

        if (
            $nilai_akhir >= 60 &&
            $kehadiran > 70 &&
            $tugas >= 40 &&
            $uts >= 40 &&
            $uas >= 40
        ) {
            $status = "LULUS";
            $warna  = "success";
        } else {
            $status = "TIDAK LULUS";
            $warna  = "danger";
        }

        // Menandai bahwa hasil sudah siap ditampilkan
        $hasil = true;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Penilaian Mahasiswa</title>

<!-- Menggunakan Bootstrap CSS dan JS via CDN -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body class="bg-light">

<div class="container mt-5 mb-5">
<div class="row justify-content-center">
<div class="col-md-8 col-lg-7">

<!-- Card utama -->
<div class="card shadow">
<div class="card-header bg-primary text-white text-center fw-bold">
Form Penilaian Mahasiswa
</div>

<div class="card-body">

<!--FORM INPUT DATA MAHASISWA -->
<form method="post">

    <!-- Input Nama -->
    <div class="mb-3">
        <label class="form-label fw-bold">Masukkan Nama</label>
        <input type="text" name="nama" class="form-control" placeholder="Salsa">
    </div>

    <!-- Input NIM -->
    <div class="mb-3">
        <label class="form-label fw-bold">Masukkan NIM</label>
        <input type="text" name="nim" class="form-control" placeholder="2024xxxx">
    </div>

    <!-- Input Kehadiran -->
    <div class="mb-3">
        <label class="form-label fw-bold">Nilai Kehadiran (10%)</label>
        <input type="number" name="kehadiran" class="form-control" min="0" max="100" placeholder="0 - 100">
    </div>

    <!-- Input Tugas -->
    <div class="mb-3">
        <label class="form-label fw-bold">Nilai Tugas (20%)</label>
        <input type="number" name="tugas" class="form-control" min="0" max="100" placeholder="0 - 100">
    </div>

    <!-- Input UTS -->
    <div class="mb-3">
        <label class="form-label fw-bold">Nilai UTS (30%)</label>
        <input type="number" name="uts" class="form-control" min="0" max="100" placeholder="0 - 100">
    </div>

    <!-- Input UAS -->
    <div class="mb-4">
        <label class="form-label fw-bold">Nilai UAS (40%)</label>
        <input type="number" name="uas" class="form-control" min="0" max="100" placeholder="0 - 100">
    </div>

    <!-- Tombol Proses -->
    <button type="submit" class="btn btn-primary w-100">
        Proses
    </button>
</form>

<!--PESAN ERROR VALIDASI -->
<?php if ($error): ?>
<div class="alert alert-danger mt-4">
    <?= $error ?>
</div>
<?php endif; ?>

<!--HASIL PENILAIAN -->
<?php if ($hasil): ?>
<div class="card border-<?= $warna ?> mt-4">
    <div class="card-header bg-<?= $warna ?> text-white fw-bold">
        Hasil Penilaian
    </div>
    <div class="card-body">

        <div class="row mb-3">
            <div class="col-md-6">
                <strong>Nama:</strong> <?= htmlspecialchars($nama) ?>
            </div>
            <div class="col-md-6 text-md-end">
                <strong>NIM:</strong> <?= htmlspecialchars($nim) ?>
            </div>
        </div>

        <hr>

        <p><strong>Nilai Kehadiran:</strong> <?= $kehadiran ?>%</p>
        <p><strong>Nilai Tugas:</strong> <?= $tugas ?></p>
        <p><strong>Nilai UTS:</strong> <?= $uts ?></p>
        <p><strong>Nilai UAS:</strong> <?= $uas ?></p>
        <p><strong>Nilai Akhir:</strong> <?= number_format($nilai_akhir,2) ?></p>
        <p><strong>Grade:</strong> <?= $grade ?></p>
        <p><strong>Status:</strong> <?= $status ?></p>

        <!-- Tombol kembali ke form -->
        <form method="get">
            <button class="btn btn-<?= $warna ?> w-100 mt-3">
                Selesai
            </button>
        </form>

    </div>
</div>
<?php endif; ?>

</div>
</div>

</div>
</div>
</div>

</body>
</html>
