
<?php
session_start();
include "koneksi.php";

$pesan = "";

if (isset($_POST['daftar'])) {

    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if ($username == "" || $password == "") {
        $pesan = "Semua data harus diisi!";
    } else {
        $cek = $koneksi->prepare(
            "SELECT id FROM user WHERE username = ?"
        );
        $cek->bind_param("s", $username);
        $cek->execute();
        $cek->store_result();
        if ($cek->num_rows > 0) {
            $pesan = "Username sudah digunakan!";
        } else {
            $password_hash = password_hash(
                $password,
                PASSWORD_DEFAULT
            );
            $role = "petugas";
            $simpan = $koneksi->prepare(
                "INSERT INTO user (username, password, role)
                 VALUES (?, ?, ?)"
            );
            $simpan->bind_param(
                "sss",
                $username,
                $password_hash,
                $role
            );

            if ($simpan->execute()) {
                $pesan = "Registrasi berhasil!";
            } else {
                $pesan = "Registrasi gagal!";
            }

            $simpan->close();
        }

        $cek->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registrasi Petugas</title>
</head>

<body>

<div class="container">

    <form method="POST">
        <button type="submit" name="tambah">
            Tambah
        </button>
        <br><br>
        <label>Username</label>
        <input type="text" name="username"
               placeholder="Masukkan username" required>
        <br><br>
        <label>Password</label>
        <input type="text" name="password"
               placeholder="Masukkan password" required>
        <br><br>
        <button type="submit" name="daftar">
            Daftar
        </button>

    </form>

    <div class="pesan">
        <?php echo htmlspecialchars($pesan); ?>
    </div>

    <a href="dashboard.php">Kembali ke Dashboard</a>

</div>

</body>
</html>
