<?php include 'koneksi.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Login Perpustakaan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-primary">
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card border-0 shadow-lg mt-5">
                <div class="card-body p-4">
                    <h2 class="text-center mb-4">LOGIN</h2>
                    <form method="POST">
                        <div class="mb-3"><input type="text" name="user" class="form-control" placeholder="Username" required></div>
                        <div class="mb-3"><input type="password" name="pass" class="form-control" placeholder="Password" required></div>
                        <button type="submit" name="login" class="btn btn-primary w-100">MASUK</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
<?php
if(isset($_POST['login'])){
    $u = $_POST['user']; $p = $_POST['pass'];
    $query = mysqli_query($conn, "SELECT * FROM user WHERE username='$u' AND password='$p'");
    if(mysqli_num_rows($query) > 0){
        $data = mysqli_fetch_assoc($query);
        $_SESSION['user'] = $data['username'];
        $_SESSION['id_user'] = $data['id_user'];
        header("Location: dashboard.php");
    } else { echo "<script>alert('User/Pass Salah!');</script>"; }
}
?>