<?php

session_start();

if (!isset($_SESSION['login'])) {
    header("Location: ../auth/login.php");
    exit;
}

include "../config/koneksi.php";

$id = $_GET['id'];

$query = mysqli_query($conn, "SELECT * FROM anggota WHERE id='$id'");

$data = mysqli_fetch_assoc($query);

if (!$data) {
    header("Location: index.php");
    exit;
}


if (isset($_POST['update'])) {

    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $jenis_kelamin = mysqli_real_escape_string($conn, $_POST['jenis_kelamin']);
    $jabatan = mysqli_real_escape_string($conn, $_POST['jabatan']);
    $no_hp = mysqli_real_escape_string($conn, $_POST['no_hp']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);


    /* =====================================
       FOTO PROFIL
       ===================================== */

    $foto_lama = $data['foto'];
    $foto = $foto_lama;


    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {

        $nama_file = $_FILES['foto']['name'];
        $tmp_file = $_FILES['foto']['tmp_name'];
        $ukuran_file = $_FILES['foto']['size'];

        $ekstensi = strtolower(
            pathinfo($nama_file, PATHINFO_EXTENSION)
        );

        $ekstensi_diperbolehkan = [
            'jpg',
            'jpeg',
            'png',
            'webp'
        ];


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

            mkdir(
                $folder_upload,
                0777,
                true
            );
        }


        /* Upload foto baru */

        move_uploaded_file(
            $tmp_file,
            $folder_upload . $nama_baru
        );


        /* Hapus foto lama */

        if (
            !empty($foto_lama) &&
            file_exists($folder_upload . $foto_lama)
        ) {

            unlink(
                $folder_upload . $foto_lama
            );
        }


        $foto = $nama_baru;
    }


    /* =====================================
       UPDATE DATA
       ===================================== */

    mysqli_query($conn, "
        UPDATE anggota SET

        nama='$nama',
        jenis_kelamin='$jenis_kelamin',
        jabatan='$jabatan',
        no_hp='$no_hp',
        alamat='$alamat',
        foto='$foto'

        WHERE id='$id'
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

    <title>Edit Anggota - SIKAT</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >

    <link rel="stylesheet" href="../assets/css/style.css">

    <style>

        .preview-foto {

            width: 100px;
            height: 100px;

            object-fit: cover;

            border-radius: 50%;

            border: 4px solid #e9f7ef;

            box-shadow:
                0 3px 10px rgba(0,0,0,0.15);

        }

    </style>

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

        <h5 class="fw-bold">
            Edit Data Anggota
        </h5>

    </div>


    <div class="table-card">


        <form
            method="POST"
            enctype="multipart/form-data"
        >

            <div class="row">


                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Nama Lengkap
                    </label>

                    <input
                        type="text"
                        name="nama"
                        class="form-control"
                        value="<?= htmlspecialchars($data['nama']); ?>"
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

                        <option
                            value="Laki-laki"
                            <?= $data['jenis_kelamin'] == 'Laki-laki' ? 'selected' : ''; ?>
                        >
                            Laki-laki
                        </option>


                        <option
                            value="Perempuan"
                            <?= $data['jenis_kelamin'] == 'Perempuan' ? 'selected' : ''; ?>
                        >
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

                        <?php

                        $jabatan = [
                            "Ketua",
                            "Wakil Ketua",
                            "Sekretaris",
                            "Bendahara",
                            "Anggota"
                        ];

                        foreach ($jabatan as $j) {

                            $selected =
                                ($data['jabatan'] == $j)
                                ? "selected"
                                : "";

                            echo "<option $selected>$j</option>";

                        }

                        ?>

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
                        value="<?= htmlspecialchars($data['no_hp']); ?>"
                    >

                </div>


                <div class="col-12 mb-4">

                    <label class="form-label">
                        Alamat
                    </label>

                    <textarea
                        name="alamat"
                        class="form-control"
                        rows="4"
                    ><?= htmlspecialchars($data['alamat']); ?></textarea>

                </div>


                <!-- =====================================
                     FOTO PROFIL
                     ===================================== -->

                <div class="col-12 mb-4">

                    <label class="form-label fw-bold">

                        <i class="bi bi-person-circle"></i>

                        Foto Profil

                    </label>


                    <div class="mb-3">

                        <?php if (!empty($data['foto'])) { ?>

                            <img
                                src="../assets/uploads/<?= htmlspecialchars($data['foto']); ?>"
                                class="preview-foto"
                                alt="Foto <?= htmlspecialchars($data['nama']); ?>"
                            >

                            <div class="mt-2 text-muted">

                                Foto saat ini

                            </div>

                        <?php } else { ?>

                            <div
                                class="text-muted"
                                style="font-size:14px;"
                            >

                                <i class="bi bi-person-circle"></i>

                                Belum ada foto profil.

                            </div>

                        <?php } ?>

                    </div>


                    <input
                        type="file"
                        name="foto"
                        class="form-control"
                        accept="image/jpeg,image/png,image/webp"
                    >


                    <small class="text-muted">

                        Pilih foto baru jika ingin menambahkan
                        atau mengganti foto.

                        <br>

                        Format JPG, JPEG, PNG, WEBP.
                        Maksimal 2 MB.

                    </small>

                </div>

            </div>


            <button
                type="submit"
                name="update"
                class="btn btn-success"
            >

                <i class="bi bi-save"></i>

                Simpan Perubahan

            </button>


            <a
                href="index.php"
                class="btn btn-secondary"
            >

                Kembali

            </a>

        </form>

    </div>

</div>


</body>
</html>