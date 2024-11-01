<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css">

    <style>
        .testimony-text {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            min-height: 130px;
        }

        .nav-lt-tab .nav-item .nav-link.active {
            border-top: 2.5px solid #624bff;
        }

        .nav {
            display: inline-block;
            overflow: auto;
            overflow-y: hidden;
            max-width: 100%;
            white-space: nowrap;
        }

        .nav li {
            display: inline-block;
            vertical-align: top;
        }

        .nav-item {
            margin-bottom: 0 !important;
        }

        ::-webkit-scrollbar-thumb:vertical {
            background: #888 !important;
        }

        ::-webkit-scrollbar {
            width: 0.5rem;
        }

        #map {
            height: 550px;
        }

        /* Layout for screens 768px and below */
        @media (max-width: 768px) {
            .content .row {
                flex-direction: column-reverse;
            }
        }
    </style>
</head>

<body>
    <?php include('../components/slider_berita.php'); ?>
    <?php
    // Sertakan file config untuk koneksi database
    include '../../config.php';

    // Pastikan id diterima melalui GET atau POST
    $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

    if ($id > 0) {
        // Siapkan query untuk mengambil data berita berdasarkan id
        $sql = "SELECT * FROM berita WHERE id = ?";

        // Siapkan statement untuk keamanan
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);

        // Eksekusi statement
        $stmt->execute();

        // Ambil hasil
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            ?>
            <div class="container mt-2">
                <div class="row">
                    <div class="col-lg-8">
                        <h2 class="mt-4"><?= $row['judul']; ?></h2>
                        <p class="mb-4">Dipublikasi pada <?= $row['created_at']; ?></p>
                        <hr>
                        <img class="mt-4 mb-4" style="border-radius: 20px; width: 100%; height: 490px; object-fit: cover;"
                            src="../../pelaporan_peta/asset/gambar_berita/<?= $row['gambar']; ?>" alt="">
                        <p style="line-height: 2rem;"><?= nl2br($row['konten']); ?></p>
                        <h2 class="mt-4 mb-4">Iframe</h2>
                        <hr>
                        <?php
                        // Ambil iframe dari database
                        $iframe = $row['iframe'];

                        // Tambahkan width dan height menggunakan str_replace
                        $iframe = str_replace('<iframe', '<iframe width="100%" height="500px"', $iframe);

                        // Tampilkan iframe
                        echo $iframe;
                        ?>
                        <br><br>
                    </div>
                    <div class="col-lg-1"></div>
                    <div class="col-lg-3">
                        <h2 class="mt-4 mb-4">Recent Post</h2>
                        <hr>
                        <?php

                        $sql = "SELECT * FROM berita ORDER BY id DESC LIMIT 5";
                        $result = $conn->query($sql);

                        $data = array(); // Array untuk menyimpan data
                
                        foreach ($result as $row) { ?>
                            <div class="card mb-4">
                                <img style="height: 150px; object-fit: cover;"
                                    src="../../pelaporan_peta/asset/gambar_berita/<?= $row['gambar']; ?>">
                                <div class="card-body">
                                    <a style="text-decoration: none;" href="../pelaporan_peta/detail_berita?id=<?= $row['id']; ?>" class="card-text">
                                        <?= substr($row['judul'], 0, 50); ?> ....
                                    </a>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <?php
        } else {
            echo "Data tidak ditemukan.";
        }

        // Tutup statement
        $stmt->close();
    } else {
        echo "ID tidak valid.";
    }

    // Tutup koneksi
    $conn->close();
    ?>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>

    <!-- DataTables JavaScript -->
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap5.min.js"></script>
</body>

</html>