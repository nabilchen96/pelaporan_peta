<?php
session_start(); // Memulai session
?>
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

        th,
        td {
            white-space: nowrap;
        }
    </style>
</head>

<body>

    <?php include('../components/slider_berita.php'); ?>
    <div class="container content mt-4">
        <h2>Data Peta</h2>
        <button data-bs-toggle="modal" data-bs-target="#modalImport" class="btn btn-warning mt-4 mb-4">
            📄 Import Excel
        </button>
        <div class="table-responsive">
            <table id="example" class="table table-striped table-bordered" style="width:100%">
                <thead class="bg-info text-white">
                    <tr>
                        <th>Longitude</th>
                        <th>Latitude</th>
                        <th>Unit Kerja</th>
                        <th>Keterangan</th>
                        <th>Tanggal</th>
                        <th width="5%">Edit</th>
                        <th width="5%">Hapus</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Data pengguna akan dimuat di sini -->
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="modalImport" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Form User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="controller/peta/importExcel.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" id="id" name="id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>File</label>
                            <input type="file" class="form-control" id="file" placeholder="File" required name="file">
                        </div>
                        <a href="/pelaporan_peta/asset/contoh.xlsx">Download format import data</a>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- JavaScript untuk menampilkan alert -->
    <script>
        <?php if (isset($_SESSION['message']) && isset($_SESSION['status'])): ?>
            alert("<?php echo addslashes($_SESSION['message']); ?>");
            <?php unset($_SESSION['message'], $_SESSION['status']); // Hapus pesan setelah ditampilkan ?>
        <?php endif; ?>
    </script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>

    <!-- DataTables JavaScript -->
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap5.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Inisialisasi DataTable
            const table = $('#example').DataTable({
                ajax: {
                    url: '/pelaporan_peta/controller/peta/dataPeta.php', // URL untuk mengambil data
                    dataSrc: '' // Mengambil data langsung dari array JSON
                },
                columns: [
                    { data: 'longitude' },
                    { data: 'latitude' },
                    { data: 'unit_kerja' },
                    { data: 'keterangan' },
                    { data: 'tanggal' },
                    {
                        data: null, render: function (data, type, row) {
                            return '<button data-bs-toggle="modal" data-bs-target="#exampleModal" class="btn btn-success btn-sm" data-id="' + row.id + '">✏️</button>'; // Tombol edit
                        }
                    },
                    {
                        data: null, render: function (data, type, row) {
                            return '<button class="text-center btn btn-danger btn-sm delete-btn" data-id="' + row.id + '">🗑️</button>'; // Tombol hapus
                        }
                    }
                ],
                paging: true,
                searching: true,
                ordering: false,
                info: true,
                lengthChange: true,
            });
        });
    </script>


</body>

</html>