<?php

session_start();

if (!isset($_SESSION['login'])) {
    header("Location: ../auth/login.php");
    exit;
}

include "../config/koneksi.php";

if (isset($_POST['simpan'])) {

    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $jenis_kelamin = mysqli_real_escape_string($conn, $_POST['jenis_kelamin']);
    $jabatan = mysqli_real_escape_string($conn, $_POST['jabatan']);
    $no_hp = mysqli_real_escape_string($conn, $_POST['no_hp']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);

    /* ==============================
       TAMBAHAN UNTUK FOTO PROFIL
       ============================== */

    $foto = "";

    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {

        $nama_file = $_FILES['foto']['name'];
        $tmp_file = $_FILES['foto']['tmp_name'];
        $ukuran_file = $_FILES['foto']['size'];

        $ekstensi = strtolower(pathinfo($nama_file, PATHINFO_EXTENSION));

        $ekstensi_diperbolehkan = ['jpg', 'jpeg', 'png', 'webp'];

        if (!in_array($ekstensi, $ekstensi_diperbolehkan)) {

            echo "<script>
                    alert('Format foto harus JPG, JPEG, PNG, atau WEBP!');
                    window.history.back();
                  </script>";
            exit;

        }

        if ($ukuran_file > 2 * 1024 * 1024) {

            echo "<script>
                    alert('Ukuran foto maksimal 2 MB!');
                    window.history.back();
                  </script>";
            exit;

        }

        $nama_baru = uniqid('anggota_') . '.' . $ekstensi;

        $folder_upload = "../assets/uploads/";

        if (!is_dir($folder_upload)) {
            mkdir($folder_upload, 0777, true);
        }

        move_uploaded_file(
            $tmp_file,
            $folder_upload . $nama_baru
        );

        $foto = $nama_baru;
    }

    /* ==============================
       INSERT DATA ANGGOTA
       ============================== */

    mysqli_query($conn, "
        INSERT INTO anggota
        (nama, jenis_kelamin, jabatan, no_hp, alamat, foto)
        VALUES
        ('$nama', '$jenis_kelamin', '$jabatan', '$no_hp', '$alamat', '$foto')
    ");

    header("Location: index.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tambah Anggota - SIKAT</title>

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


<div class="main-content">


    <div class="topbar">

        <h5 class="fw-bold mb-1">
            Tambah Anggota
        </h5>

        <small class="text-muted">
            Tambahkan anggota baru Karang Taruna
        </small>

    </div>


    <div class="table-card">

        <!-- TAMBAHAN enctype UNTUK UPLOAD FOTO -->
        <form method="POST" enctype="multipart/form-data">

            <div class="row">

                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Nama Lengkap
                    </label>

                    <input
                        type="text"
                        name="nama"
                        class="form-control"
                        placeholder="Masukkan nama lengkap"
                        required
                    >

                </div>


                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Jenis Kelamin
                    </label>

                    <select
                        name="jenis_kelamin"
                        class="form-select"
                        required
                    >

                        <option value="">
                            -- Pilih --
                        </option>

                        <option value="Laki-laki">
                            Laki-laki
                        </option>

                        <option value="Perempuan">
                            Perempuan
                        </option>

                    </select>

                </div>


                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Jabatan
                    </label>

                    <select
                        name="jabatan"
                        class="form-select"
                        required
                    >

                        <option value="">
                            -- Pilih Jabatan --
                        </option>

                        <option>
                            Ketua
                        </option>

                        <option>
                            Wakil Ketua
                        </option>

                        <option>
                            Sekretaris
                        </option>

                        <option>
                            Bendahara
                        </option>

                        <option>
                            Anggota
                        </option>

                    </select>

                </div>


                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Nomor HP
                    </label>

                    <input
                        type="text"
                        name="no_hp"
                        class="form-control"
                        placeholder="08xxxxxxxxxx"
                    >

                </div>


                <div class="col-12 mb-3">

                    <label class="form-label">
                        Alamat
                    </label>

                    <textarea
                        name="alamat"
                        class="form-control"
                        rows="4"
                        placeholder="Masukkan alamat"
                    ></textarea>

                </div>


                <!-- ==============================
                     TAMBAHAN FOTO PROFIL
                     ============================== -->

                <div class="col-md-6 mb-4">

                    <label class="form-label">
                        Foto Profil
                    </label>

                    <input
                        type="file"
                        name="foto"
                        class="form-control"
                        accept="image/jpeg,image/png,image/webp"
                    >

                    <small class="text-muted">
                        Format JPG, JPEG, PNG, WEBP. Maksimal 2 MB.
                    </small>

                </div>

            </div>


            <div class="d-flex gap-2">

                <button
                    type="submit"
                    name="simpan"
                    class="btn btn-success"
                >

                    <i class="bi bi-save"></i>

                    Simpan

                </button>


                <a
                    href="index.php"
                    class="btn btn-secondary"
                >

                    <i class="bi bi-arrow-left"></i>

                    Kembali

                </a>

            </div>

        </form>

    </div>

</div>


</body>
</html>