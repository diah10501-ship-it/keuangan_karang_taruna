<?php

session_start();

if (!isset($_SESSION['login'])) {
    header("Location: ../auth/login.php");
    exit;
}

include "../config/koneksi.php";


if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}


$id = intval($_GET['id']);


mysqli_query($conn, "
    DELETE FROM transaksi_kas
    WHERE id = $id
    AND jenis_kas = 'Bendahara'
");


header("Location: index.php");

exit;

?>