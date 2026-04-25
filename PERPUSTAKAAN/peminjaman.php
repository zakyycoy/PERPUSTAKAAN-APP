<?php 
include 'koneksi.php'; 
if(!isset($_SESSION['user'])) { header("Location: login.php"); exit(); }

// ==========================================
// 1. LOGIKA INPUT PEMINJAMAN (TGL MANUAL)
// ==========================================
if(isset($_POST['pinjam'])){
    $agg = $_POST['id_anggota']; 
    $bk  = $_POST['id_buku']; 
    $usr = $_SESSION['id_user'] ?? 1;
    $t1  = date('Y-m-d'); 
    $t2  = $_POST['tgl_deadline']; // AMBIL DARI INPUT MANUAL

    $sql_in = "INSERT INTO peminjaman (id_anggota, id_user, tgl_pinjam, tgl_kembali, status_pinjam) 
               VALUES ('$agg', '$usr', '$t1', '$t2', 'proses')";
    
    if(mysqli_query($conn, $sql_in)){ 
        $id_peminjaman = mysqli_insert_id($conn);
        mysqli_query($conn, "INSERT INTO detail_peminjaman (id_peminjaman, id_buku, jumlah) VALUES ('$id_peminjaman', '$bk', 1)");
        mysqli_query($conn, "UPDATE buku SET stok = stok - 1 WHERE id_buku = '$bk'");
        echo "<script>alert('Peminjaman Berhasil Dicatat!'); window.location='peminjaman.php';</script>";
    } else {
        echo "<div class='alert alert-danger'>Gagal! Error: " . mysqli_error($conn) . "</div>";
    }
}

// ==========================================
// 2. LOGIKA PROSES PENGEMBALIAN & DENDA
// ==========================================
if(isset($_GET['aksi']) && $_GET['aksi'] == 'selesaikan'){
    $id_p = $_GET['id'];
    $id_b = $_GET['buku'];
    $deadline = $_GET['deadline'];
    $tgl_skrg = date('Y-m-d');

    $denda = 0;
    $tarif_denda = 5000; // Rp 5.000 per hari telat
    
    if(strtotime($tgl_skrg) > strtotime($deadline)){
        $selisih = (strtotime($tgl_skrg) - strtotime($deadline)) / 86400;
        $denda = floor($selisih) * $tarif_denda;
    }

    $up_status = mysqli_query($conn, "UPDATE peminjaman SET status_pinjam = 'selesai' WHERE id_peminjaman = '$id_p'");
    mysqli_query($conn, "UPDATE buku SET stok = stok + 1 WHERE id_buku = '$id_b'");

    if($up_status){
        $msg = ($denda > 0) ? "Buku Kembali! Denda: Rp " . number_format($denda) : "Buku Kembali Tepat Waktu!";
        echo "<script>alert('$msg'); window.location='peminjaman.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Transaksi - Perpus Pro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-dark mb-4 shadow">
    <div class="container">
        <a class="navbar-brand fw-bold" href="dashboard.php">PERPUS PRO</a>
        <div class="navbar-nav ms-auto flex-row gap-3">
            <a class="nav-link" href="buku.php">Buku</a>
            <a class="nav-link active" href="peminjaman.php">Transaksi</a>
            <a class="nav-link text-danger" href="logout.php">Logout</a>
        </div>
    </div>
</nav>

<div class="container">
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-4">
            <h5 class="fw-bold mb-3 text-center">Input Peminjaman Baru</h5>
            <form method="POST" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label small fw-bold">Pilih Anggota</label>
                    <select name="id_anggota" class="form-select" required>
                        <option value="">-- Pilih Anggota --</option>
                        <?php 
                        $aq = mysqli_query($conn, "SELECT * FROM anggota");
                        while($a = mysqli_fetch_assoc($aq)) echo "<option value='{$a['id_anggota']}'>{$a['nama']}</option>";
                        ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold">Pilih Buku</label>
                    <select name="id_buku" class="form-select" required>
                        <option value="">-- Pilih Buku --</option>
                        <?php 
                        $bq = mysqli_query($conn, "SELECT * FROM buku WHERE stok > 0");
                        while($b = mysqli_fetch_assoc($bq)) echo "<option value='{$b['id_buku']}'>{$b['judul']}</option>";
                        ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold">Tanggal Deadline</label>
                    <input type="date" name="tgl_deadline" class="form-control" value="<?= date('Y-m-d', strtotime('+7 days')) ?>" required>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button name="pinjam" class="btn btn-warning w-100 fw-bold shadow-sm">PINJAMKAN</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle text-center">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Peminjam</th>
                        <th>Buku</th>
                        <th>Deadline</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT p.*, a.nama, b.judul, dp.id_buku FROM peminjaman p 
                            JOIN anggota a ON p.id_anggota = a.id_anggota 
                            JOIN detail_peminjaman dp ON dp.id_peminjaman = p.id_peminjaman 
                            JOIN buku b ON dp.id_buku = b.id_buku 
                            ORDER BY p.id_peminjaman DESC";
                    $res = mysqli_query($conn, $sql);
                    $no = 1;
                    while($row = mysqli_fetch_assoc($res)){
                        $st = $row['status_pinjam'];
                        $deadline = $row['tgl_kembali'];
                        $is_telat = (strtotime(date('Y-m-d')) > strtotime($deadline) && $st == 'proses');
                    ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td class="fw-bold"><?= $row['nama']; ?></td>
                        <td><?= $row['judul']; ?></td>
                        <td class="<?= $is_telat ? 'text-danger fw-bold' : '' ?>">
                            <?= date('d/m/Y', strtotime($deadline)); ?>
                        </td>
                        <td>
                            <span class="badge rounded-pill <?= ($st == 'proses') ? 'bg-warning text-dark' : 'bg-success' ?>">
                                <?= ucfirst($st); ?>
                            </span>
                        </td>
                        <td>
                            <?php if($st == 'proses'): ?>
                                <a href="peminjaman.php?aksi=selesaikan&id=<?= $row['id_peminjaman']; ?>&buku=<?= $row['id_buku']; ?>&deadline=<?= $deadline; ?>" 
                                   class="btn btn-sm btn-primary shadow-sm"
                                   onclick="return confirm('Selesaikan pengembalian?')">
                                   Selesaikan
                                </a>
                            <?php else: ?>
                                <i class="fas fa-check-circle text-success fa-2x"></i>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>