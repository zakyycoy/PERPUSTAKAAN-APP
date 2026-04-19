<?php include 'koneksi.php'; 
if(isset($_POST['tambah'])){
    $j = $_POST['j']; $k = $_POST['k']; $p = $_POST['p']; $pn = $_POST['pn']; $s = $_POST['s'];
    mysqli_query($conn, "INSERT INTO buku (id_kategori, id_penerbit, judul, penulis, stok) VALUES ('$k','$p','$j','$pn','$s')");
    header("Location: buku.php");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Buku</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-4">
    <div class="row">
        <div class="col-md-4">
            <div class="card p-3 shadow-sm border-0">
                <h5>Tambah Buku</h5>
                <form method="POST">
                    <input type="text" name="j" class="form-control mb-2" placeholder="Judul" required>
                    <input type="number" name="k" class="form-control mb-2" placeholder="ID Kat (1-3)" required>
                    <input type="number" name="p" class="form-control mb-2" placeholder="ID Pen (1-2)" required>
                    <input type="text" name="pn" class="form-control mb-2" placeholder="Penulis" required>
                    <input type="number" name="s" class="form-control mb-2" placeholder="Stok" required>
                    <button name="tambah" class="btn btn-primary w-100">Simpan</button>
                    <a href="dashboard.php" class="btn btn-link w-100">Kembali</a>
                </form>
            </div>
        </div>
        <div class="col-md-8">
            <table class="table bg-white shadow-sm rounded">
                <thead class="table-dark"><tr><th>Judul</th><th>Penulis</th><th>Stok</th></tr></thead>
                <tbody>
                <?php $q=mysqli_query($conn,"SELECT * FROM buku"); while($r=mysqli_fetch_assoc($q)){
                    echo "<tr><td>{$r['judul']}</td><td>{$r['penulis']}</td><td><span class='badge bg-info text-dark'>{$r['stok']}</span></td></tr>"; } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>