<?php

session_start();

if (!isset($_SESSION['login'])) {
    header("Location: auth/login.php");
    exit;
}

include "config/koneksi.php";


/* =========================
   JUMLAH ANGGOTA
========================= */

$q_anggota = mysqli_query($conn, "
    SELECT COUNT(*) AS total
    FROM anggota
");

$d_anggota = mysqli_fetch_assoc($q_anggota);

$total_anggota = $d_anggota['total'] ?? 0;


/* =========================
   PEMASUKAN
========================= */

$q_pemasukan = mysqli_query($conn, "
    SELECT SUM(jumlah) AS total
    FROM transaksi_kas
    WHERE jenis_transaksi = 'Pemasukan'
");

$d_pemasukan = mysqli_fetch_assoc($q_pemasukan);

$total_pemasukan = $d_pemasukan['total'] ?? 0;


/* =========================
   PENGELUARAN
========================= */

$q_pengeluaran = mysqli_query($conn, "
    SELECT SUM(jumlah) AS total
    FROM transaksi_kas
    WHERE jenis_transaksi = 'Pengeluaran'
");

$d_pengeluaran = mysqli_fetch_assoc($q_pengeluaran);

$total_pengeluaran = $d_pengeluaran['total'] ?? 0;


/* =========================
   SALDO KESELURUHAN
========================= */

$total_saldo =
    $total_pemasukan -
    $total_pengeluaran;


/* =========================
   SALDO BENDAHARA
========================= */

$q_bendahara = mysqli_query($conn, "
    SELECT
        SUM(
            CASE
                WHEN jenis_transaksi = 'Pemasukan'
                THEN jumlah
                ELSE 0
            END
        ) -
        SUM(
            CASE
                WHEN jenis_transaksi = 'Pengeluaran'
                THEN jumlah
                ELSE 0
            END
        ) AS saldo
    FROM transaksi_kas
    WHERE jenis_kas = 'Bendahara'
");

$d_bendahara = mysqli_fetch_assoc($q_bendahara);

$saldo_bendahara = $d_bendahara['saldo'] ?? 0;


/* =========================
   SALDO SOSIAL
========================= */

$q_sosial = mysqli_query($conn, "
    SELECT
        SUM(
            CASE
                WHEN jenis_transaksi = 'Pemasukan'
                THEN jumlah
                ELSE 0
            END
        ) -
        SUM(
            CASE
                WHEN jenis_transaksi = 'Pengeluaran'
                THEN jumlah
                ELSE 0
            END
        ) AS saldo
    FROM transaksi_kas
    WHERE jenis_kas = 'Sosial'
");

$d_sosial = mysqli_fetch_assoc($q_sosial);

$saldo_sosial = $d_sosial['saldo'] ?? 0;


/* =========================
   TRANSAKSI TERBARU
========================= */

$q_transaksi = mysqli_query($conn, "
    SELECT *
    FROM transaksi_kas
    ORDER BY tanggal DESC, id DESC
    LIMIT 5
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

    <title>Dashboard - SIKAT</title>


    <!-- BOOTSTRAP -->

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >


    <!-- ICON -->

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >


    <!-- CSS -->

    <link
        rel="stylesheet"
        href="assets/css/style.css"
    >

</head>


<body>


<!-- =========================
     SIDEBAR
========================= -->

<div class="sidebar">


    <div class="brand">

        <div style="font-size:38px;">
            💰
        </div>


        <h4>

            SIKAT

        </h4>


        <small>

            Sistem Informasi Keuangan

        </small>


        <br>


        <small>

            Karang Taruna

        </small>

    </div>


    <!-- DASHBOARD -->

    <a
        href="dashboard.php"
        class="active"
    >

        <i class="bi bi-grid-fill"></i>

        Dashboard

    </a>


    <!-- BENDAHARA -->

    <a href="bendahara/index.php">

        <i class="bi bi-wallet2"></i>

        Kas Bendahara

    </a>


    <!-- SOSIAL -->

    <a href="sosial/index.php">

        <i class="bi bi-heart-fill"></i>

        Kas Sosial

    </a>


    <!-- ANGGOTA -->

    <a href="anggota/index.php">

        <i class="bi bi-people-fill"></i>

        Data Anggota

    </a>


    <!-- LAPORAN -->

    <a href="laporan/index.php">

        <i class="bi bi-bar-chart-fill"></i>

        Laporan Keuangan

    </a>


    <div style="margin-top:40px;"></div>


    <!-- LOGOUT -->

    <a href="auth/logout.php">

        <i class="bi bi-box-arrow-right"></i>

        Logout

    </a>


</div>


<!-- =========================
     MAIN CONTENT
========================= -->

<div class="main-content">


    <!-- TOPBAR -->

    <div class="topbar d-flex justify-content-between align-items-center">


        <div>

            <h5 class="fw-bold mb-1">

                Dashboard

            </h5>


            <small class="text-muted">

                Sistem Informasi Keuangan Karang Taruna

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


    <!-- WELCOME -->

    <div class="welcome-card mb-4">


        <div>


            <h3>

                Selamat Datang 👋

            </h3>


            <p>

                Kelola keuangan Karang Taruna
                dengan lebih mudah dan terorganisir.

            </p>


        </div>


        <div class="welcome-icon">

            💰

        </div>


    </div>


    <!-- =========================
         STATISTIK
    ========================= -->

    <div class="row g-4 mb-4">


        <!-- ANGGOTA -->

        <div class="col-md-3">


            <div class="stat-card">


                <div class="d-flex justify-content-between">


                    <div>


                        <small class="text-muted">

                            Total Anggota

                        </small>


                        <h3 class="fw-bold mt-2">

                            <?= $total_anggota; ?>

                        </h3>


                    </div>


                    <div
                        class="stat-icon bg-primary-subtle text-primary"
                    >

                        <i class="bi bi-people-fill"></i>

                    </div>


                </div>


            </div>


        </div>


        <!-- BENDAHARA -->

        <div class="col-md-3">


            <div class="stat-card">


                <div class="d-flex justify-content-between">


                    <div>


                        <small class="text-muted">

                            Kas Bendahara

                        </small>


                        <h5 class="fw-bold mt-2 text-success">

                            Rp <?= number_format(
                                $saldo_bendahara,
                                0,
                                ',',
                                '.'
                            ); ?>

                        </h5>


                    </div>


                    <div
                        class="stat-icon bg-success-subtle text-success"
                    >

                        <i class="bi bi-wallet-fill"></i>

                    </div>


                </div>


            </div>


        </div>


        <!-- SOSIAL -->

        <div class="col-md-3">


            <div class="stat-card">


                <div class="d-flex justify-content-between">


                    <div>


                        <small class="text-muted">

                            Kas Sosial

                        </small>


                        <h5 class="fw-bold mt-2 text-danger">

                            Rp <?= number_format(
                                $saldo_sosial,
                                0,
                                ',',
                                '.'
                            ); ?>

                        </h5>


                    </div>


                    <div
                        class="stat-icon bg-danger-subtle text-danger"
                    >

                        <i class="bi bi-heart-fill"></i>

                    </div>


                </div>


            </div>


        </div>


        <!-- TOTAL SALDO -->

        <div class="col-md-3">


            <div class="stat-card">


                <div class="d-flex justify-content-between">


                    <div>


                        <small class="text-muted">

                            Total Saldo

                        </small>


                        <h5 class="fw-bold mt-2 text-primary">

                            Rp <?= number_format(
                                $total_saldo,
                                0,
                                ',',
                                '.'
                            ); ?>

                        </h5>


                    </div>


                    <div
                        class="stat-icon bg-primary-subtle text-primary"
                    >

                        <i class="bi bi-cash-stack"></i>

                    </div>


                </div>


            </div>


        </div>


    </div>


    <!-- =========================
         PEMASUKAN / PENGELUARAN
    ========================= -->

    <div class="row g-4 mb-4">


        <div class="col-md-6">


            <div class="table-card">


                <div class="d-flex justify-content-between">


                    <div>


                        <small class="text-muted">

                            Total Pemasukan

                        </small>


                        <h3 class="fw-bold text-success">

                            Rp <?= number_format(
                                $total_pemasukan,
                                0,
                                ',',
                                '.'
                            ); ?>

                        </h3>


                    </div>


                    <div
                        class="stat-icon bg-success-subtle text-success"
                    >

                        <i class="bi bi-arrow-down-circle-fill"></i>

                    </div>


                </div>


            </div>


        </div>


        <div class="col-md-6">


            <div class="table-card">


                <div class="d-flex justify-content-between">


                    <div>


                        <small class="text-muted">

                            Total Pengeluaran

                        </small>


                        <h3 class="fw-bold text-danger">

                            Rp <?= number_format(
                                $total_pengeluaran,
                                0,
                                ',',
                                '.'
                            ); ?>

                        </h3>


                    </div>


                    <div
                        class="stat-icon bg-danger-subtle text-danger"
                    >

                        <i class="bi bi-arrow-up-circle-fill"></i>

                    </div>


                </div>


            </div>


        </div>


    </div>


    <!-- =========================
         TRANSAKSI TERBARU
    ========================= -->

    <div class="table-card">


        <div class="d-flex justify-content-between align-items-center mb-4">


            <div>

                <h5 class="fw-bold mb-1">

                    <i class="bi bi-clock-history"></i>

                    Transaksi Terbaru

                </h5>


                <small class="text-muted">

                    5 transaksi terakhir

                </small>

            </div>


            <a
                href="laporan/index.php"
                class="btn btn-primary"
            >

                Lihat Semua

            </a>


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


                if (mysqli_num_rows($q_transaksi) > 0) {


                    while ($row = mysqli_fetch_assoc($q_transaksi)) {

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


                            <?php if (
                                $row['jenis_kas']
                                == 'Bendahara'
                            ) { ?>

                                <span
                                    class="badge bg-success-subtle text-success"
                                >

                                    Bendahara

                                </span>


                            <?php } else { ?>

                                <span
                                    class="badge bg-danger-subtle text-danger"
                                >

                                    Sosial

                                </span>


                            <?php } ?>


                        </td>


                        <td>

                            <?= htmlspecialchars(
                                $row['keterangan']
                            ); ?>

                        </td>


                        <td>


                            <?php if (
                                $row['jenis_transaksi']
                                == 'Pemasukan'
                            ) { ?>

                                <span
                                    class="text-success fw-bold"
                                >

                                    + Pemasukan

                                </span>


                            <?php } else { ?>

                                <span
                                    class="text-danger fw-bold"
                                >

                                    - Pengeluaran

                                </span>


                            <?php } ?>


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

                            <i
                                class="bi bi-inbox"
                                style="font-size:40px;"
                            ></i>


                            <br><br>


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