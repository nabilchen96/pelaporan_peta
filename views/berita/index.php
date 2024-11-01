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
            /* margin: 0 0 1em; */
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
    // session_start();
    if (!isset($_SESSION['user_id'])) {
        header("Location: /pelaporan_peta/login");
        exit;
    }
    ?>
    <div class="container content mt-4">
        <h2>Data Berita</h2>
        <button data-bs-toggle="modal" data-bs-target="#exampleModal" class="btn btn-warning mt-4 mb-4">
            ➕ Tambah
        </button>
        <div class="table-responsive">
            <table id="example" class="table table-striped table-bordered" style="width:100%">
                <thead class="bg-info text-white">
                    <tr>
                        <th>Gambar</th>
                        <th width="45%">Konten</th>
                        <th width="25%">Iframe</th>
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

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Form Berita</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form>
                    <input type="hidden" id="id" name="id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Judul</label>
                            <input type="text" class="form-control" id="judul" placeholder="Judul" required
                                name="judul">
                        </div>
                        <div class="mb-3">
                            <label>Gambar</label>
                            <input type="file" class="form-control" id="gambar" placeholder="Gambar" name="gambar">
                            <div id="preview_gambar" class="mt-2"></div>
                        </div>
                        <div class="mb-3">
                            <label>Konten</label>
                            <textarea name="konten" id="konten" rows="10" placeholder="Konten" required
                                class="form-control"></textarea>
                        </div>
                        <div class="mb-3">
                            <label>Iframe</label>
                            <textarea name="iframe" id="iframe" rows="10" placeholder="Konten"
                                class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

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
                    url: '/pelaporan_peta/controller/berita/dataBerita.php', // URL untuk mengambil data
                    dataSrc: '' // Mengambil data langsung dari array JSON
                },
                columns: [
                    {
                        data: null, render: function (data, type, row) {
                            return `<img class="shadow" style="width: 100%; object-fit: cover; height: 150px;" src="/pelaporan_peta/asset/gambar_berita/${row.gambar}">`; // Tombol edit
                        }
                    },
                    {
                        data: null, render: function (data, type, row) {
                            return `<div>
                                        <b>Judul</b><br>
                                        ${row.judul} 
                                        <br><br>
                                        <b>Konten</b><br>
                                        ${row.konten.slice(0, 250)}
                                    </div>`
                        }
                    },
                    {
                        data: null, render: function (data, type, row) {
                            // Periksa apakah row.iframe ada dan bukan null
                            if (row.iframe) {
                                // Mengganti width dan height iframe
                                const updatedIframe = row.iframe
                                    .replace(/width="[^"]*"/, 'width="100%"')
                                    .replace(/height="[^"]*"/, 'height="150"');
                                return updatedIframe;
                            } else {
                                // Kembalikan pesan atau elemen kosong jika iframe tidak ada
                                return '<div>Tidak ada iframe tersedia</div>';
                            }
                        }
                    },
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

            var exampleModal = document.getElementById('exampleModal')
            exampleModal.addEventListener('show.bs.modal', function (event) {
                var button = event.relatedTarget
                var recipient = button.getAttribute('data-id')
                var cok = $("#example").DataTable().rows().data().toArray()

                let cokData = cok.filter((dt) => {
                    return dt.id == recipient;
                })

                document.querySelector('form').reset();

                if (recipient) {
                    var modal = $(this)
                    modal.find('#id').val(cokData[0].id)
                    modal.find('#judul').val(cokData[0].judul)
                    modal.find('#konten').val(cokData[0].konten)
                    modal.find('#iframe').val(cokData[0].iframe)

                    document.getElementById('preview_gambar').innerHTML = `
                        Gambar Preview <br>
                        <img class="shadow" height="100px" src="/pelaporan_peta/asset/gambar_berita/${cokData[0].gambar}">
                    `
                } else {
                    document.getElementById('preview_gambar').innerHTML = ``
                }
            })

            // Tambahkan event listener untuk tombol submit di modal
            document.querySelector('form').addEventListener('submit', function (event) {
                event.preventDefault(); // Mencegah submit form biasa

                const formData = new FormData(this); // Ambil data dari form
                const userId = document.getElementById('id').value; // Ambil ID pengguna dari input hidden

                // URL endpoint untuk update atau create user
                const url = userId ? '/pelaporan_peta/controller/berita/updateBerita.php' : '/pelaporan_peta/controller/berita/createBerita.php';

                fetch(url, {
                    method: 'POST',
                    body: formData // Mengirim data form
                })
                    .then(response => response.json())
                    .then(data => {
                        alert(data.message); // Tampilkan pesan sukses atau error
                        if (data.status === "success") {
                            const modal = bootstrap.Modal.getInstance(document.getElementById('exampleModal'));
                            modal.hide(); // Sembunyikan modal
                            this.reset(); // Reset form
                            table.ajax.reload(); // Refresh DataTable
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat menyimpan data.');
                    });
            });

            // Event listener untuk tombol hapus
            $('#example tbody').on('click', '.delete-btn', function () {
                const userId = $(this).data('id'); // Ambil ID pengguna dari atribut data-id

                if (confirm('Apakah Anda yakin ingin menghapus pengguna ini?')) {
                    fetch('/pelaporan_peta/controller/berita/deleteBerita.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: new URLSearchParams({ id: userId }) // Kirim ID pengguna untuk dihapus
                    })
                        .then(response => response.json()) // Mengkonversi respons ke JSON
                        .then(data => {
                            alert(data.message); // Tampilkan pesan sukses atau error
                            if (data.status === "success") {
                                // Refresh DataTable
                                table.ajax.reload();
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Terjadi kesalahan saat menghapus user.');
                        });
                }
            });
        });
    </script>


</body>

</html>