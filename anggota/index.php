<?php

session_start();

if (!isset($_SESSION['login'])) {
    header("Location: ../auth/login.php");
    exit;
}

include "../config/koneksi.php";

$query = mysqli_query($conn, "SELECT * FROM anggota ORDER BY id DESC");

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Data Anggota - SIKAT</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >

    <link rel="stylesheet" href="../assets/css/style.css">

    <!-- CSS FOTO PROFIL -->
    <style>

        .foto-anggota {
            width: 55px;
            height: 55px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid #e9f7ef;
            box-shadow: 0 2px 6px rgba(0,0,0,0.15);
        }

        .foto-default {
            width: 55px;
            height: 55px;
            border-radius: 50%;
            background: #e9f7ef;
            color: #198754;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
            border: 3px solid #d1e7dd;
        }

    </style>

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


    <a href="../bendahara/index.php">

        <i class="bi bi-wallet2"></i>

        Kas Bendahara

    </a>


    <a href="../sosial/index.php">

        <i class="bi bi-heart-fill"></i>

        Kas Sosial

    </a>


    <a href="index.php" class="active">

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



<!-- MAIN -->

<div class="main-content">


    <!-- TOPBAR -->

    <div class="topbar d-flex justify-content-between align-items-center">

        <div>

            <h5 class="fw-bold mb-1">
                Data Anggota
            </h5>

            <small class="text-muted">
                Data anggota Karang Taruna
            </small>

        </div>


        <div>

            <strong>
                <?= htmlspecialchars($_SESSION['nama']); ?>
            </strong>

            <br>

            <small class="text-muted">
                Administrator
            </small>

        </div>

    </div>



    <!-- CONTENT -->

    <div class="table-card">


        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h4 class="fw-bold mb-1">

                    <i class="bi bi-people-fill text-success"></i>

                    Daftar Anggota

                </h4>

                <small class="text-muted">

                    Kelola data anggota Karang Taruna

                </small>

            </div>


            <a
                href="tambah.php"
                class="btn btn-success"
            >

                <i class="bi bi-plus-circle"></i>

                Tambah Anggota

            </a>

        </div>



        <div class="table-responsive">

            <table class="table align-middle">

                <thead>

                    <tr>

                        <th>No</th>

                        <!-- TAMBAHAN FOTO -->
                        <th>Foto</th>

                        <th>Nama</th>

                        <th>Jenis Kelamin</th>

                        <th>Jabatan</th>

                        <th>No. HP</th>

                        <th>Alamat</th>

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


                        <!-- FOTO ANGGOTA -->

                        <td>

                            <?php if (!empty($row['foto'])) { ?>

                                <img
                                    src="../assets/uploads/<?= htmlspecialchars($row['foto']); ?>"
                                    alt="Foto <?= htmlspecialchars($row['nama']); ?>"
                                    class="foto-anggota"
                                >

                            <?php } else { ?>

                                <div class="foto-default">

                                    <i class="bi bi-person-fill"></i>

                                </div>

                            <?php } ?>

                        </td>


                        <td>

                            <strong>
                                <?= htmlspecialchars($row['nama']); ?>
                            </strong>

                        </td>

                        <td>

                            <?= htmlspecialchars($row['jenis_kelamin']); ?>

                        </td>

                        <td>

                            <span class="badge bg-success-subtle text-success">

                                <?= htmlspecialchars($row['jabatan']); ?>

                            </span>

                        </td>

                        <td>

                            <?= htmlspecialchars($row['no_hp']); ?>

                        </td>

                        <td>

                            <?= htmlspecialchars($row['alamat']); ?>

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
                                onclick="return confirm('Yakin ingin menghapus anggota ini?')"
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
                            colspan="8"
                            class="text-center text-muted py-5"
                        >

                            <i
                                class="bi bi-people"
                                style="font-size:40px;"
                            ></i>

                            <br><br>

                            Belum ada data anggota.

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