<?php

session_start();

if (!isset($_SESSION['login'])) {
    header("Location: ../auth/login.php");
    exit;
}

include "../config/koneksi.php";

/* TOTAL PEMASUKAN BENDAHARA */
$q1 = mysqli_query($conn, "
    SELECT SUM(jumlah) AS total
    FROM transaksi_kas
    WHERE jenis_kas = 'Bendahara'
    AND jenis_transaksi = 'Pemasukan'
");

$d1 = mysqli_fetch_assoc($q1);
$pemasukan_bendahara = $d1['total'] ?? 0;


/* TOTAL PENGELUARAN BENDAHARA */
$q2 = mysqli_query($conn, "
    SELECT SUM(jumlah) AS total
    FROM transaksi_kas
    WHERE jenis_kas = 'Bendahara'
    AND jenis_transaksi = 'Pengeluaran'
");

$d2 = mysqli_fetch_assoc($q2);
$pengeluaran_bendahara = $d2['total'] ?? 0;


/* TOTAL PEMASUKAN SOSIAL */
$q3 = mysqli_query($conn, "
    SELECT SUM(jumlah) AS total
    FROM transaksi_kas
    WHERE jenis_kas = 'Sosial'
    AND jenis_transaksi = 'Pemasukan'
");

$d3 = mysqli_fetch_assoc($q3);
$pemasukan_sosial = $d3['total'] ?? 0;


/* TOTAL PENGELUARAN SOSIAL */
$q4 = mysqli_query($conn, "
    SELECT SUM(jumlah) AS total
    FROM transaksi_kas
    WHERE jenis_kas = 'Sosial'
    AND jenis_transaksi = 'Pengeluaran'
");

$d4 = mysqli_fetch_assoc($q4);
$pengeluaran_sosial = $d4['total'] ?? 0;


/* TOTAL KESELURUHAN */

$total_pemasukan =
    $pemasukan_bendahara +
    $pemasukan_sosial;

$total_pengeluaran =
    $pengeluaran_bendahara +
    $pengeluaran_sosial;

$total_saldo =
    $total_pemasukan -
    $total_pengeluaran;


/* AMBIL SEMUA TRANSAKSI */

$query = mysqli_query($conn, "
    SELECT *
    FROM transaksi_kas
    ORDER BY tanggal DESC, id DESC
");

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Laporan Keuangan - SIKAT</title>


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


<!-- SIDEBAR -->

<div class="sidebar">

    <div class="brand">

        <div style="font-size:35px;">
            📊
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


    <a href="../sosial/index.php">

        <i class="bi bi-heart-fill"></i>

        Kas Sosial

    </a>


    <a href="../anggota/index.php">

        <i class="bi bi-people-fill"></i>

        Data Anggota

    </a>


    <a
        href="index.php"
        class="active"
    >

        <i class="bi bi-bar-chart-fill"></i>

        Laporan Keuangan

    </a>


    <div style="margin-top:40px;"></div>


    <a href="../auth/logout.php">

        <i class="bi bi-box-arrow-right"></i>

        Logout

    </a>

</div>


<!-- MAIN -->

<div class="main-content">


    <!-- TOPBAR -->

    <div class="topbar d-flex justify-content-between align-items-center">


        <div>

            <h5 class="fw-bold mb-1">

                Laporan Keuangan

            </h5>


            <small class="text-muted">

                Ringkasan keuangan Karang Taruna

            </small>

        </div>


        <div class="text-end">

            <strong>

                <?= htmlspecialchars($_SESSION['nama']); ?>

            </strong>


            <br>


            <small class="text-muted">

                Administrator

            </small>

        </div>


    </div>


    <!-- JUDUL -->

    <div class="welcome-card mb-4">


        <div>

            <h3>

                📊 Laporan Keuangan

            </h3>


            <p>

                Ringkasan pemasukan dan pengeluaran
                Kas Bendahara dan Kas Sosial.

            </p>

        </div>


        <div class="welcome-icon">

            📈

        </div>


    </div>


    <!-- RINGKASAN -->

    <div class="row g-4 mb-4">


        <!-- TOTAL PEMASUKAN -->

        <div class="col-md-4">


            <div class="stat-card">


                <small class="text-muted">

                    Total Pemasukan

                </small>


                <h4 class="fw-bold text-success mt-2">

                    Rp <?= number_format(
                        $total_pemasukan,
                        0,
                        ',',
                        '.'
                    ); ?>

                </h4>


            </div>


        </div>


        <!-- TOTAL PENGELUARAN -->

        <div class="col-md-4">


            <div class="stat-card">


                <small class="text-muted">

                    Total Pengeluaran

                </small>


                <h4 class="fw-bold text-danger mt-2">

                    Rp <?= number_format(
                        $total_pengeluaran,
                        0,
                        ',',
                        '.'
                    ); ?>

                </h4>


            </div>


        </div>


        <!-- SALDO -->

        <div class="col-md-4">


            <div class="stat-card">


                <small class="text-muted">

                    Saldo Keseluruhan

                </small>


                <h4 class="fw-bold text-primary mt-2">

                    Rp <?= number_format(
                        $total_saldo,
                        0,
                        ',',
                        '.'
                    ); ?>

                </h4>


            </div>


        </div>


    </div>


    <!-- RINCIAN KAS -->

    <div class="row g-4 mb-4">


        <div class="col-md-6">


            <div class="table-card">


                <h5 class="fw-bold">

                    <i class="bi bi-wallet2 text-success"></i>

                    Kas Bendahara

                </h5>


                <hr>


                <div class="d-flex justify-content-between mb-2">

                    <span>
                        Pemasukan
                    </span>

                    <strong class="text-success">

                        Rp <?= number_format(
                            $pemasukan_bendahara,
                            0,
                            ',',
                            '.'
                        ); ?>

                    </strong>

                </div>


                <div class="d-flex justify-content-between mb-2">

                    <span>
                        Pengeluaran
                    </span>

                    <strong class="text-danger">

                        Rp <?= number_format(
                            $pengeluaran_bendahara,
                            0,
                            ',',
                            '.'
                        ); ?>

                    </strong>

                </div>


                <hr>


                <div class="d-flex justify-content-between">

                    <strong>
                        Saldo
                    </strong>

                    <strong class="text-primary">

                        Rp <?= number_format(
                            $pemasukan_bendahara -
                            $pengeluaran_bendahara,
                            0,
                            ',',
                            '.'
                        ); ?>

                    </strong>

                </div>


            </div>

        </div>


        <div class="col-md-6">


            <div class="table-card">


                <h5 class="fw-bold">

                    <i class="bi bi-heart-fill text-danger"></i>

                    Kas Sosial

                </h5>


                <hr>


                <div class="d-flex justify-content-between mb-2">

                    <span>
                        Pemasukan
                    </span>

                    <strong class="text-success">

                        Rp <?= number_format(
                            $pemasukan_sosial,
                            0,
                            ',',
                            '.'
                        ); ?>

                    </strong>

                </div>


                <div class="d-flex justify-content-between mb-2">

                    <span>
                        Pengeluaran
                    </span>

                    <strong class="text-danger">

                        Rp <?= number_format(
                            $pengeluaran_sosial,
                            0,
                            ',',
                            '.'
                        ); ?>

                    </strong>

                </div>


                <hr>


                <div class="d-flex justify-content-between">

                    <strong>
                        Saldo
                    </strong>

                    <strong class="text-primary">

                        Rp <?= number_format(
                            $pemasukan_sosial -
                            $pengeluaran_sosial,
                            0,
                            ',',
                            '.'
                        ); ?>

                    </strong>

                </div>


            </div>

        </div>


    </div>


    <!-- TABEL TRANSAKSI -->

    <div class="table-card">


        <div class="d-flex justify-content-between align-items-center mb-4">


            <div>

                <h5 class="fw-bold mb-1">

                    <i class="bi bi-list-ul"></i>

                    Semua Transaksi

                </h5>


                <small class="text-muted">

                    Riwayat seluruh transaksi keuangan

                </small>

            </div>


        </div>


        <div class="table-responsive">


            <table class="table align-middle">


                <thead>

                    <tr>

                        <th>No</th>

                        <th>Tanggal</th>

                        <th>Kas</th>

                        <th>Keterangan</th>

                        <th>Jenis</th>

                        <th>Jumlah</th>

                    </tr>

                </thead>


                <tbody>


                <?php

                $no = 1;


                if (mysqli_num_rows($query) > 0) {


                    while ($row = mysqli_fetch_assoc($query)) {


                ?>


                    <tr>


                        <td>

                            <?= $no++; ?>

                        </td>


                        <td>

                            <?= date(
                                'd/m/Y',
                                strtotime($row['tanggal'])
                            ); ?>

                        </td>


                        <td>


                            <?php

                            if (
                                $row['jenis_kas']
                                == 'Bendahara'
                            ) {

                            ?>

                                <span
                                    class="badge bg-success-subtle text-success"
                                >

                                    Bendahara

                                </span>

                            <?php

                            } else {

                            ?>

                                <span
                                    class="badge bg-danger-subtle text-danger"
                                >

                                    Sosial

                                </span>

                            <?php

                            }

                            ?>

                        </td>


                        <td>

                            <?= htmlspecialchars(
                                $row['keterangan']
                            ); ?>

                        </td>


                        <td>


                            <?php

                            if (
                                $row['jenis_transaksi']
                                == 'Pemasukan'
                            ) {

                            ?>

                                <span class="text-success fw-bold">

                                    + Pemasukan

                                </span>

                            <?php

                            } else {

                            ?>

                                <span class="text-danger fw-bold">

                                    - Pengeluaran

                                </span>

                            <?php

                            }

                            ?>


                        </td>


                        <td class="fw-bold">

                            Rp <?= number_format(
                                $row['jumlah'],
                                0,
                                ',',
                                '.'
                            ); ?>

                        </td>


                    </tr>


                <?php

                    }


                } else {

                ?>


                    <tr>

                        <td
                            colspan="6"
                            class="text-center text-muted py-5"
                        >

                            Belum ada transaksi.

                        </td>

                    </tr>


                <?php

                }

                ?>


                </tbody>


            </table>


        </div>


    </div>


</div>


</body>

</html>