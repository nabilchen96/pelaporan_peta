<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
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
    <?php
    include('../components/slider_berita.php');
    include '../../config.php';

    $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

    if ($id > 0) {
        $sql = "SELECT * FROM berita WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $berita = $result->fetch_assoc();
        $stmt->close();
    }

    $recent_sql = "SELECT * FROM berita ORDER BY id DESC LIMIT 5";
    $recent_posts = $conn->query($recent_sql);

    $conn->close();
    ?>

    <div class="container mt-2">
        <?php if ($id > 0 && isset($berita)): ?>
            <div class="row">
                <div class="col-lg-8">
                    <h2 class="mt-4"><?= htmlspecialchars($berita['judul']); ?></h2>
                    <p class="mb-4">Dipublikasi pada <?= htmlspecialchars($berita['created_at']); ?></p>
                    <hr>
                    <img class="mt-4 mb-4" style="border-radius: 20px; width: 100%; height: 490px; object-fit: cover;"
                        src="../../pelaporan_peta/asset/gambar_berita/<?= htmlspecialchars($berita['gambar']); ?>" alt="">
                    <p style="line-height: 2rem;"><?= nl2br(htmlspecialchars($berita['konten'])); ?></p>
                    <h2 class="mt-4 mb-4">Iframe</h2>
                    <hr>
                    <?php
                    $iframe = str_replace('<iframe', '<iframe width="100%" height="500px"', $berita['iframe']);
                    echo $iframe;
                    ?>
                </div>

                <div class="col-lg-3 offset-lg-1">
                    <h2 class="mt-4 mb-4">Recent Post</h2>
                    <hr>
                    <?php while ($post = $recent_posts->fetch_assoc()): ?>
                        <div class="card mb-4">
                            <img style="height: 150px; object-fit: cover;"
                                src="../../pelaporan_peta/asset/gambar_berita/<?= htmlspecialchars($post['gambar']); ?>" alt="">
                            <div class="card-body">
                                <a style="text-decoration: none;" href="../pelaporan_peta/detail_berita?id=<?= $post['id']; ?>"
                                    class="card-text">
                                    <?= substr(htmlspecialchars($post['judul']), 0, 50); ?>...
                                </a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        <?php else: ?>
            <p>Data tidak ditemukan atau ID tidak valid.</p>
        <?php endif; ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap5.min.js"></script>
</body>

</html>