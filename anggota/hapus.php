<?php

session_start();

if (!isset($_SESSION['login'])) {
    header("Location: ../auth/login.php");
    exit;
}

include "../config/koneksi.php";

if (isset($_GET['id'])) {

    $id = $_GET['id'];

    mysqli_query(
        $conn,
        "DELETE FROM anggota WHERE id='$id'"
    );
}

header("Location: index.php");

exit;

?>