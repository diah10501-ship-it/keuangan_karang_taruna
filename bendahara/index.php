<?php

session_start();

if (!isset($_SESSION['login'])) {
    header("Location: ../auth/login.php");
    exit;
}

include "../config/koneksi.php";

/* Ambil semua transaksi Bendahara */
$query = mysqli_query($conn, "
    SELECT *
    FROM transaksi_kas
    WHERE jenis_kas = 'Bendahara'
    ORDER BY tanggal DESC, id DESC
");

/* Hitung pemasukan */
$q_pemasukan = mysqli_query($conn, "
    SELECT SUM(jumlah) AS total
    FROM transaksi_kas
    WHERE jenis_kas = 'Bendahara'
    AND jenis_transaksi = 'Pemasukan'
");

$data_pemasukan = mysqli_fetch_assoc($q_pemasukan);
$total_pemasukan = $data_pemasukan['total'] ?? 0;


/* Hitung pengeluaran */
$q_pengeluaran = mysqli_query($conn, "
    SELECT SUM(jumlah) AS total
    FROM transaksi_kas
    WHERE jenis_kas = 'Bendahara'
    AND jenis_transaksi = 'Pengeluaran'
");

$data_pengeluaran = mysqli_fetch_assoc($q_pengeluaran);
$total_pengeluaran = $data_pengeluaran['total'] ?? 0;


/* Hitung saldo */
$saldo = $total_pemasukan - $total_pengeluaran;

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Kas Bendahara - SIKAT</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >

    <link rel="stylesheet" href="../assets/css/style.css">

</head>

<body>


<!-- SIDEBAR -->

<div class="sidebar">

    <div class="brand">

        <div style="font-size:35px;">
            💰
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


    <a href="index.php" class="active">

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


<!-- MAIN CONTENT -->

<div class="main-content">


    <!-- TOPBAR -->

    <div class="topbar d-flex justify-content-between align-items-center">

        <div>

            <h5 class="fw-bold mb-1">
                Kas Bendahara
            </h5>

            <small class="text-muted">
                Pengelolaan keuangan utama Karang Taruna
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
                💰 Kas Bendahara
            </h3>

            <p>
                Catat dan kelola pemasukan serta pengeluaran kas.
            </p>

        </div>

        <div class="welcome-icon">
            💵
        </div>

    </div>


    <!-- STATISTIK -->

    <div class="row g-4 mb-4">


        <!-- SALDO -->

        <div class="col-md-4">

            <div class="stat-card">

                <div class="d-flex justify-content-between">

                    <div>

                        <small class="text-muted">
                            Saldo Kas
                        </small>

                        <h4 class="fw-bold mt-2 text-success">

                            Rp <?= number_format($saldo, 0, ',', '.'); ?>

                        </h4>

                    </div>

                    <div class="stat-icon bg-success-subtle text-success">

                        <i class="bi bi-wallet2"></i>

                    </div>

                </div>

            </div>

        </div>


        <!-- PEMASUKAN -->

        <div class="col-md-4">

            <div class="stat-card">

                <div class="d-flex justify-content-between">

                    <div>

                        <small class="text-muted">
                            Total Pemasukan
                        </small>

                        <h4 class="fw-bold mt-2 text-primary">

                            Rp <?= number_format($total_pemasukan, 0, ',', '.'); ?>

                        </h4>

                    </div>

                    <div class="stat-icon bg-primary-subtle text-primary">

                        <i class="bi bi-arrow-down-circle-fill"></i>

                    </div>

                </div>

            </div>

        </div>


        <!-- PENGELUARAN -->

        <div class="col-md-4">

            <div class="stat-card">

                <div class="d-flex justify-content-between">

                    <div>

                        <small class="text-muted">
                            Total Pengeluaran
                        </small>

                        <h4 class="fw-bold mt-2 text-danger">

                            Rp <?= number_format($total_pengeluaran, 0, ',', '.'); ?>

                        </h4>

                    </div>

                    <div class="stat-icon bg-danger-subtle text-danger">

                        <i class="bi bi-arrow-up-circle-fill"></i>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- TABEL -->

    <div class="table-card">


        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h5 class="fw-bold mb-1">

                    <i class="bi bi-wallet2 text-success"></i>

                    Riwayat Kas Bendahara

                </h5>

                <small class="text-muted">
                    Daftar seluruh transaksi kas bendahara
                </small>

            </div>


            <a
                href="tambah.php"
                class="btn btn-success"
            >

                <i class="bi bi-plus-circle"></i>

                Tambah Transaksi

            </a>

        </div>


        <div class="table-responsive">

            <table class="table align-middle">

                <thead>

                    <tr>

                        <th>No</th>

                        <th>Tanggal</th>

                        <th>Keterangan</th>

                        <th>Jenis</th>

                        <th>Jumlah</th>

                        <th>Aksi</th>

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

                            <?= htmlspecialchars(
                                $row['keterangan']
                            ); ?>

                        </td>


                        <td>

                            <?php if (
                                $row['jenis_transaksi']
                                == 'Pemasukan'
                            ) { ?>

                                <span class="badge bg-success-subtle text-success">

                                    <i class="bi bi-arrow-down"></i>

                                    Pemasukan

                                </span>

                            <?php } else { ?>

                                <span class="badge bg-danger-subtle text-danger">

                                    <i class="bi bi-arrow-up"></i>

                                    Pengeluaran

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


                        <td>

                            <a
                                href="edit.php?id=<?= $row['id']; ?>"
                                class="btn btn-sm btn-warning"
                            >

                                <i class="bi bi-pencil"></i>

                            </a>


                            <a
                                href="hapus.php?id=<?= $row['id']; ?>"
                                class="btn btn-sm btn-danger"
                                onclick="return confirm('Yakin ingin menghapus transaksi ini?')"
                            >

                                <i class="bi bi-trash"></i>

                            </a>

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
                                class="bi bi-wallet2"
                                style="font-size:40px;"
                            ></i>

                            <br><br>

                            Belum ada transaksi kas bendahara.

                        </td>

                    </tr>

                <?php } ?>

                </tbody>

            </table>

        </div>

    </div>

</div>


</body>

</html>