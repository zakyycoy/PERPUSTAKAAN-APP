<?php 
include 'koneksi.php'; 

// Cek login
if(!isset($_SESSION['user'])){ header("Location: login.php"); exit(); }

// Logika Tambah Anggota
if(isset($_POST['tambah'])){
    $n = $_POST['n']; $e = $_POST['e']; $t = $_POST['t']; $a = $_POST['a'];
    mysqli_query($conn, "INSERT INTO anggota (nama, email, no_telp, alamat) VALUES ('$n','$e','$t','$a')");
    header("Location: anggota.php");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Anggota - Perpus Pro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<!-- Navigasi Tambahan -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container">
        <a class="navbar-brand" href="dashboard.php">PERPUS PRO</a>
        <div class="navbar-nav ms-auto">
            <a class="nav-link active" href="anggota.php">Anggota</a>
            <a class="nav-link" href="buku.php">Buku</a>
            <a class="nav-link" href="peminjaman.php">Peminjaman</a>
            <a class="nav-link text-danger" href="logout.php">Logout</a>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <!-- Tombol Kembali ke Dashboard -->
    <div class="mb-3">
        <a href="dashboard.php" class="btn btn-secondary btn-sm">← Kembali ke Dashboard</a>
    </div>

    <div class="row">
        <!-- Form Tambah -->
        <div class="col-md-4">
            <div class="card p-3 shadow-sm border-0">
                <h5 class="mb-3">Tambah Anggota</h5>
                <form method="POST">
                    <input type="text" name="n" class="form-control mb-2" placeholder="Nama" required>
                    <input type="email" name="e" class="form-control mb-2" placeholder="Email" required>
                    <input type="text" name="t" class="form-control mb-2" placeholder="No Telp" required>
                    <textarea name="a" class="form-control mb-2" placeholder="Alamat"></textarea>
                    <button name="tambah" class="btn btn-success w-100">Simpan Anggota</button>
                </form>
            </div>
        </div>

        <!-- Tabel Data -->
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <table class="table mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>No Telp</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
                    $q = mysqli_query($conn,"SELECT * FROM anggota ORDER BY id_anggota DESC"); 
                    while($r = mysqli_fetch_assoc($q)){
                        echo "<tr>
                                <td>{$r['nama']}</td>
                                <td>{$r['email']}</td>
                                <td>{$r['no_telp']}</td>
                              </tr>"; 
                    } 
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</body>
</html>