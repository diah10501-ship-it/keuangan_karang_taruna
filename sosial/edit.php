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


$query = mysqli_query($conn, "
    SELECT *
    FROM transaksi_kas
    WHERE id = $id
    AND jenis_kas = 'Sosial'
");


$data = mysqli_fetch_assoc($query);


if (!$data) {
    header("Location: index.php");
    exit;
}


if (isset($_POST['update'])) {

    $tanggal = mysqli_real_escape_string(
        $conn,
        $_POST['tanggal']
    );

    $jenis_transaksi = mysqli_real_escape_string(
        $conn,
        $_POST['jenis_transaksi']
    );

    $keterangan = mysqli_real_escape_string(
        $conn,
        $_POST['keterangan']
    );

    $jumlah = mysqli_real_escape_string(
        $conn,
        $_POST['jumlah']
    );


    mysqli_query($conn, "
        UPDATE transaksi_kas
        SET
            tanggal = '$tanggal',
            jenis_transaksi = '$jenis_transaksi',
            keterangan = '$keterangan',
            jumlah = '$jumlah'
        WHERE id = $id
        AND jenis_kas = 'Sosial'
    ");


    header("Location: index.php");

    exit;
}

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Edit Kas Sosial - SIKAT</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >

    <link
        rel="stylesheet"
        href="../assets/css/style.css"
    >

</head>


<body>


<div class="sidebar">


    <div class="brand">

        <div style="font-size:35px;">
            ❤️
        </div>

        <h4>SIKAT</h4>

        <small>
            Sistem Informasi Keuangan
        </small>

        <br>

        <small>
            Karang Taruna
        </small>

    </div>


    <a href="../dashboard.php">

        <i class="bi bi-grid-fill"></i>

        Dashboard

    </a>


    <a href="../bendahara/index.php">

        <i class="bi bi-wallet2"></i>

        Kas Bendahara

    </a>


    <a href="index.php" class="active">

        <i class="bi bi-heart-fill"></i>

        Kas Sosial

    </a>


    <a href="../anggota/index.php">

        <i class="bi bi-people-fill"></i>

        Data Anggota

    </a>


    <a href="../laporan/index.php">

        <i class="bi bi-bar-chart-fill"></i>

        Laporan Keuangan

    </a>


    <div style="margin-top:40px;"></div>


    <a href="../auth/logout.php">

        <i class="bi bi-box-arrow-right"></i>

        Logout

    </a>


</div>


<div class="main-content">


    <div class="topbar">

        <h5 class="fw-bold mb-1">

            Edit Transaksi Sosial

        </h5>

        <small class="text-muted">

            Kas Sosial

        </small>

    </div>


    <div class="table-card">


        <h4 class="fw-bold mb-4">

            <i class="bi bi-pencil-square text-warning"></i>

            Edit Transaksi Kas Sosial

        </h4>


        <form method="POST">


            <div class="row">


                <div class="col-md-6 mb-3">

                    <label class="form-label">

                        Tanggal

                    </label>


                    <input
                        type="date"
                        name="tanggal"
                        class="form-control"
                        value="<?= htmlspecialchars($data['tanggal']); ?>"
                        required
                    >

                </div>


                <div class="col-md-6 mb-3">

                    <label class="form-label">

                        Jenis Transaksi

                    </label>


                    <select
                        name="jenis_transaksi"
                        class="form-select"
                        required
                    >

                        <option
                            value="Pemasukan"
                            <?= $data['jenis_transaksi'] == 'Pemasukan'
                                ? 'selected'
                                : ''; ?>
                        >

                            Pemasukan

                        </option>


                        <option
                            value="Pengeluaran"
                            <?= $data['jenis_transaksi'] == 'Pengeluaran'
                                ? 'selected'
                                : ''; ?>
                        >

                            Pengeluaran

                        </option>


                    </select>

                </div>


                <div class="col-12 mb-3">

                    <label class="form-label">

                        Keterangan

                    </label>


                    <input
                        type="text"
                        name="keterangan"
                        class="form-control"
                        value="<?= htmlspecialchars($data['keterangan']); ?>"
                        required
                    >

                </div>


                <div class="col-md-6 mb-4">

                    <label class="form-label">

                        Jumlah

                    </label>


                    <div class="input-group">

                        <span class="input-group-text">

                            Rp

                        </span>


                        <input
                            type="number"
                            name="jumlah"
                            class="form-control"
                            value="<?= htmlspecialchars($data['jumlah']); ?>"
                            min="1"
                            required
                        >

                    </div>

                </div>


            </div>


            <button
                type="submit"
                name="update"
                class="btn btn-warning"
            >

                <i class="bi bi-save"></i>

                Simpan Perubahan

            </button>


            <a
                href="index.php"
                class="btn btn-secondary"
            >

                <i class="bi bi-arrow-left"></i>

                Kembali

            </a>


        </form>


    </div>


</div>


</body>

</html>