<?php
session_start();
include "koneksi.php";

$username = $_POST['username'];
$password = $_POST['password'];

$query = mysqli_query($koneksi,
    "SELECT * FROM user
     WHERE username='$username'
     AND password='$password'");

if (mysqli_num_rows($query) > 0) {
    $_SESSION['username'] = $username;
    $_SESSION['password'] = $password;
    header("Location: dashboard.php");
} else {
    echo "username dan password salah!";
}
?>