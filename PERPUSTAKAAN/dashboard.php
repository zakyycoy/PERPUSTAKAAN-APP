<?php 
include 'koneksi.php'; 
if(!isset($_SESSION['user'])) { header("Location: login.php"); exit(); }

// Ambil data untuk statistik
$hitung_buku = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM buku"))['total'];
$hitung_anggota = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM anggota"))['total'];
$hitung_pinjam = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM peminjaman WHERE status_pinjam='proses'"))['total'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - PERPUS PRO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-dark: #212529;
            --accent: #ffc107;
        }
        body { background-color: #f4f7f6; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        
        /* Hero Section */
        .hero-section {
            background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('https://images.unsplash.com/photo-1507842217343-583bb7270b66?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 100px 0;
            border-radius: 0 0 50px 50px;
            margin-bottom: -50px;
        }

        /* Stat Cards */
        .stat-card {
            border: none;
            border-radius: 15px;
            transition: transform 0.3s ease;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }
        .stat-card:hover { transform: translateY(-10px); }
        .icon-box {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 15px;
        }

        /* Footer Quote */
        .quote-section {
            background: white;
            border-left: 5px solid var(--accent);
            padding: 30px;
            border-radius: 10px;
            margin-top: 50px;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#"><i class="fas fa-book-open me-2 text-warning"></i>PERPUS PRO</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link active" href="dashboard.php">Beranda</a></li>
                <li class="nav-item"><a class="nav-link" href="buku.php">Koleksi Buku</a></li>
                <li class="nav-item"><a class="nav-link" href="peminjaman.php">Transaksi</a></li>
                <li class="nav-item"><a class="nav-link text-danger fw-bold" href="logout.php">Keluar</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<header class="hero-section text-center">
    <div class="container">
        <h1 class="display-4 fw-bold">Selamat Datang, <?= $_SESSION['user']; ?>!</h1>
        <p class="lead">"Buku adalah jendela dunia. Mari kelola pengetahuan dengan lebih cerdas."</p>
        <div class="mt-4">
            <a href="peminjaman.php" class="btn btn-warning btn-lg px-4 fw-bold shadow">Mulai Transaksi</a>
        </div>
    </div>
</header>

<!-- Main Content -->
<main class="container mb-5">
    <div class="row g-4 text-center">
        <!-- Stat 1 -->
        <div class="col-md-4">
            <div class="card stat-card p-4">
                <div class="icon-box bg-primary bg-opacity-10 text-primary mx-auto">
                    <i class="fas fa-book"></i>
                </div>
                <h3 class="fw-bold"><?= $hitung_buku; ?></h3>
                <p class="text-muted mb-0">Total Koleksi Buku</p>
            </div>
        </div>
        <!-- Stat 2 -->
        <div class="col-md-4">
            <div class="card stat-card p-4">
                <div class="icon-box bg-success bg-opacity-10 text-success mx-auto">
                    <i class="fas fa-users"></i>
                </div>
                <h3 class="fw-bold"><?= $hitung_anggota; ?></h3>
                <p class="text-muted mb-0">Anggota Terdaftar</p>
            </div>
        </div>
        <!-- Stat 3 -->
        <div class="col-md-4">
            <div class="card stat-card p-4">
                <div class="icon-box bg-warning bg-opacity-10 text-warning mx-auto">
                    <i class="fas fa-exchange-alt"></i>
                </div>
                <h3 class="fw-bold"><?= $hitung_pinjam; ?></h3>
                <p class="text-muted mb-0">Buku Sedang Dipinjam</p>
            </div>
        </div>
    </div>

    <!-- Quote Perpustakaan -->
    <div class="quote-section shadow-sm">
        <div class="row align-items-center">
            <div class="col-md-1 text-center d-none d-md-block">
                <i class="fas fa-quote-left fa-3x text-warning opacity-50"></i>
            </div>
            <div class="col-md-11">
                <h5 class="fw-bold">Tentang Perpustakaan Kami</h5>
                <p class="text-muted mb-0">
                    Perpus Pro bukan sekadar tempat menyimpan buku, melainkan ruang untuk bertumbuh. 
                    Kami percaya bahwa akses literasi yang mudah adalah kunci kemajuan bangsa. 
                    Setiap halaman yang dibaca adalah langkah menuju masa depan yang lebih cerah.
                </p>
                <footer class="blockquote-footer mt-2">Literasi adalah jembatan menuju kebebasan.</footer>
            </div>
        </div>
    </div>
</main>

<!-- Footer -->
<footer class="text-center py-4 text-muted border-top bg-white">
    <div class="container">
        <p class="mb-0">&copy; <?= date('Y'); ?> <strong>PERPUS PRO</strong>. Sistem Manajemen Perpustakaan Modern.</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>