
<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}
if (isset($_POST['update'])) {

    $idproduk = $_POST['idproduk'];
    $stok = $_POST['stok'];

    if ($stok >= 0 && is_numeric($stok)) {

        $query = mysqli_prepare(
            $koneksi,
            "UPDATE produk SET stok = ? WHERE idproduk = ?"
        );

        mysqli_stmt_bind_param(
            $query,
            "ii",
            $stok,
            $idproduk
        );

        mysqli_stmt_execute($query);
        mysqli_stmt_close($query);

        header("Location: stok.php?pesan=berhasil");
        exit;

    } else {
        $pesan = "Jumlah stok tidak boleh kurang dari 0!";
    }
}

$data = mysqli_query(
    $koneksi,
    "SELECT * FROM produk ORDER BY namaproduk ASC"
);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Stok Barang</title>
</head>

<body>

<div class="container">

    <h2>Data Stok Barang</h2>

    <p>
        Selamat datang,
        <?php echo htmlspecialchars($_SESSION['username']); ?>
    </p>

    <?php
    if (isset($_GET['pesan'])) {
        echo "<p style='color:green;'>Stok berhasil diperbarui!</p>";
    }

    if (isset($pesan)) {
        echo "<p style='color:red;'>$pesan</p>";
    }
    ?>

    <table>
        <tr>
            <th>No</th>
            <th>Nama Barang</th>
            <th>Stok Saat Ini</th>
            <th>Ubah Stok</th>
            <th>Aksi</th>
        </tr>

        <?php
        $no = 1;

        while ($row = mysqli_fetch_assoc($data)) {
        ?>

        <tr>
            <td><?php echo $no++; ?></td>

            <td>
                <?php echo htmlspecialchars($row['namaproduk']); ?>
            </td>

            <td>
                <?php echo $row['stok']; ?>
            </td>

            <form method="POST" action="stok.php">
                <td>
                    <input
                        type="number"
                        name="stok"
                        value="<?php echo $row['stok']; ?>"
                        min="0"
                        required
                    >
                </td>

                <td>
                    <input
                        type="hidden"
                        name="idproduk"
                        value="<?php echo $row['idproduk']; ?>"
                    >

                    <button type="submit" name="update">
                        Update
                    </button>
                </td>
            </form>
        </tr>

        <?php } ?>

    </table>

    <br>

    <a href="dashboard.php">Kembali ke Dashboard Admin</a>

</div>

</body>
</html>
