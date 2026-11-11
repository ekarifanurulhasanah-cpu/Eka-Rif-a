
<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}
if (isset($_POST['aksi'])) {

    $aksi = $_POST['aksi'];
    if (($aksi == 'edit' || $aksi == 'hapus')) {
        die("Kamu tidak memiliki akses!");
    }

    $id = $_POST['idproduk'] ?? 0;
    $nama = trim($_POST['namaproduk'] ?? '');
    $harga = $_POST['harga'] ?? 0;
    $stok = $_POST['stok'] ?? 0;

    if ($aksi == 'tambah') {
        $sql = mysqli_prepare($koneksi,
            "INSERT INTO produk (namaproduk, harga, stok)
             VALUES (?, ?, ?)");
        mysqli_stmt_bind_param($sql, "sdi", $nama, $harga, $stok);
        mysqli_stmt_execute($sql);
    }

    if ($aksi == 'edit') {
        $sql = mysqli_prepare($koneksi,
            "UPDATE produk SET namaproduk=?, harga=?, stok=?
             WHERE idproduk=?");
        mysqli_stmt_bind_param($sql, "sdii", $nama, $harga, $stok, $id);
        mysqli_stmt_execute($sql);
    }

    if ($aksi == 'hapus') {
        $sql = mysqli_prepare($koneksi,
            "DELETE FROM produk WHERE idproduk=?");
        mysqli_stmt_bind_param($sql, "i", $id);
        mysqli_stmt_execute($sql);
    }

    header("Location: pendataan.php");
    exit;
}

// DATA UNTUK DIEDIT

if (isset($_GET['edit'])) {
    $id = (int) $_GET['edit'];
    $edit = mysqli_fetch_assoc(mysqli_query(
        $koneksi,
        "SELECT * FROM produk WHERE idproduk=$id"
    ));
}

// MENAMPILKAN BARANG
$data = mysqli_query($koneksi, "SELECT * FROM produk");
?>

<h2>Pendataan Barang</h2>

<h3><?php echo $edit ? 'Edit Barang' : 'Tambah Barang'; ?></h3>

<form method="POST">

    <input type="hidden" name="aksi"
        value="<?php echo $edit ? 'edit' : 'tambah'; ?>">

    <input type="hidden" name="idproduk"
        value="<?php echo $edit['idproduk'] ?? ''; ?>">

    <p>Nama Produk</p>
    <input type="text" name="namaproduk"
        value="<?php echo htmlspecialchars($edit['namaproduk'] ?? ''); ?>"
        required>

    <p>Harga</p>
    <input type="number" name="harga" min="0" step="0.01"
        value="<?php echo $edit['harga'] ?? ''; ?>" required>

    <p>Stok</p>
    <input type="number" name="stok" min="0"
        value="<?php echo $edit['stok'] ?? ''; ?>" required>

    <button type="submit">Simpan</button>

</form>

<hr>

<h3>Daftar Barang</h3>

<table border="1" cellpadding="10">
    <tr>
        <th>No</th>
        <th>Nama Produk</th>
        <th>Harga</th>
        <th>Stok</th>
        <th>Aksi</th>
    </tr>

    <?php $no = 1; ?>
    <?php while ($row = mysqli_fetch_assoc($data)) { ?>

    <tr>
        <td><?php echo $no++; ?></td>
        <td><?php echo htmlspecialchars($row['namaproduk']); ?></td>
        <td><?php echo $row['harga']; ?></td>
        <td><?php echo $row['stok']; ?></td>

        <td>
                <a href="pendataan.php?edit=<?php echo $row['idproduk']; ?>">
                    Edit
                </a>

                <form method="POST" style="display:inline;"
                    onsubmit="return confirm('Hapus barang ini?')">

                    <input type="hidden" name="aksi" value="hapus">
                    <input type="hidden" name="idproduk"
                        value="<?php echo $row['idproduk']; ?>">

                    <button type="submit">Hapus</button>
                </form>
            <?php } ?>
        </td>
    </tr>
</table>

<br>
<a href="<?php echo $admin ? 'dashboard.php' : 'dashboard_petugas.php'; ?>">
    Kembali ke Dashboard
</a>
