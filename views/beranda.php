<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

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

    <?php include('components/slider_berita.php'); ?>
    <div class="container content mt-4">
        <div class="row">
            <div class="col-lg-4">
                <form id="searchForm1">
                    <div class="input-group mb-3 w-100">
                        <select name="tahun" class="form-select" id="tahun">
                            <option value="">--PILIH TAHUN--</option>
                            <?php
                            // Mengambil data unik tahun dari kolom tanggal
                            $sql = "SELECT DISTINCT YEAR(tanggal) AS tahun FROM peta";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                // Menampilkan setiap tahun unik sebagai opsi
                                while ($row = $result->fetch_assoc()) {
                                    ?>
                                    <option><?= $row['tahun']; ?></option>
                                    <?php
                                }
                            }
                            ?>

                        </select>
                        <select name="unit_kerja" class="form-select" id="unit_kerja">
                            <option value="">--PILIH UNIT--</option>
                            <?php
                            $sql = "SELECT DISTINCT unit_kerja FROM peta"; // Menggunakan DISTINCT untuk data unik
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                // Menampilkan setiap unit_kerja unik sebagai opsi
                                while ($row = $result->fetch_assoc()) {
                                    ?>
                                    <option><?= $row['unit_kerja']; ?></option>
                                    <?php
                                }
                            }
                            ?>

                        </select>
                        <button type="submit" class="input-group-text" id="basic-addon2">Search</button>
                    </div>
                </form>
                <div id="bar-chart" style="height: 280px;"></div>
                <hr>
                <div id="pie-chart" style="height: 250px;"></div>
            </div>
            <div class="col-lg-8 mb-4">
                <form id="searchForm">
                    <div class="input-group mb-3 w-100">
                        <input type="text" id="searchQuery" class="form-control"
                            placeholder="Cari Unit Kerja atau Keterangan">
                        <button type="submit" class="input-group-text" id="basic-addon2">Search</button>
                    </div>
                </form>
                <div id="map"></div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>

    <!-- Make sure you put this AFTER Leaflet's CSS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <!-- Leaflet MarkerCluster JS -->
    <script src="https://unpkg.com/leaflet.markercluster/dist/leaflet.markercluster.js"></script>


    <script src="https://code.highcharts.com/highcharts.js"></script>

    <script>
        // Inisialisasi peta dengan tampilan default
        var map = L.map('map').setView([-2.983333, 104.764383], 8);

        // Menambahkan tile layer OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Membuat layer cluster untuk mengelompokkan marker
        var markers = L.markerClusterGroup();

        // Fungsi untuk memuat data dengan query pencarian
        function loadMapData(queryType = '', queryValue = '') {
            // URL untuk data
            let url = '/pelaporan_peta/controller/beranda/dataPeta.php';
            if (queryType && queryValue) {
                url += `?${queryType}=${encodeURIComponent(queryValue)}`;
            }

            // console.log("URL yang dikirim:", url); // Debug URL

            // Menghapus semua marker sebelumnya dari cluster
            markers.clearLayers();

            // Fetch data dari server
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    data.forEach(function (item) {
                        if (item.keterangan) {
                            // Menambahkan marker ke peta
                            var marker = L.marker([item.latitude, item.longitude]);

                            // URL Google Maps untuk navigasi
                            var googleMapsUrl = `https://www.google.com/maps/dir/?api=1&destination=${item.latitude},${item.longitude}`;

                            // Menambahkan pop-up pada marker
                            marker.bindPopup(
                                `<p style="margin-bottom: -15px;"><b>Keterangan:</b> ${item.keterangan}<br></p>` +
                                `<p style="margin-bottom: -15px;"><b>Waktu Laporan:</b> ${item.tanggal}<br></p>` +
                                `<p><b>Unit Kerja:</b> ${item.unit_kerja}<br></p>` +
                                `<a href="${googleMapsUrl}" target="_blank">Temukan arah dengan Google Maps</a>`
                            );

                            // Tambahkan marker ke cluster
                            markers.addLayer(marker);
                        }
                    });

                    // Menambahkan seluruh marker ke peta melalui cluster
                    map.addLayer(markers);
                })
                .catch(error => console.error('Error fetching data:', error));
        }

        // Event listener untuk form pencarian kedua (form dengan input teks)
        document.getElementById('searchForm').addEventListener('submit', function (e) {
            e.preventDefault();

            // Ambil nilai dari input pencarian
            var query = document.getElementById('searchQuery').value;

            // Panggil fungsi untuk memuat ulang data pada peta dengan query tipe "text"
            loadMapData('query', query);
        });

        // Event listener untuk form pencarian pertama (form dengan select dropdown)
        document.getElementById('searchForm1').addEventListener('submit', function (e) {
            e.preventDefault();

            // Ambil nilai dari dropdown (contoh untuk tahun dan unit)
            var tahun = document.querySelector('#searchForm1 select:nth-child(1)').value;
            var unit = document.querySelector('#searchForm1 select:nth-child(2)').value;

            // Gabungkan nilai tahun dan unit sebagai query jika ada
            var query = '';
            if (tahun) query += `tahun=${encodeURIComponent(tahun)}&`;
            if (unit) query += `unit=${encodeURIComponent(unit)}`;

            // Panggil fungsi untuk memuat ulang data pada peta dengan query untuk form 1
            loadMapData('customQuery', query);
        });

        // Load data awal tanpa filter pencarian
        loadMapData();
    </script>


    <script>
        // Fungsi untuk mengambil data dari PHP dengan fetch, dengan parameter filter
        async function fetchData(tahun = '', unit = '') {
            let url = '/pelaporan_peta/controller/beranda/dataColumn.php';

            // Menambahkan parameter tahun dan unit jika ada
            if (tahun || unit) {
                url += `?tahun=${encodeURIComponent(tahun)}&unit=${encodeURIComponent(unit)}`;
            }

            const response = await fetch(url);
            const data = await response.json();
            return data;
        }

        // Event listener untuk form pencarian pertama (form dengan select dropdown)
        document.getElementById('searchForm1').addEventListener('submit', function (e) {
            e.preventDefault();

            // Ambil nilai dari dropdown (contoh untuk tahun dan unit)
            var tahun = document.querySelector('#searchForm1 select:nth-child(1)').value;
            var unit = document.querySelector('#searchForm1 select:nth-child(2)').value;

            // Memanggil fetchData dengan filter dan me-refresh kedua chart
            fetchData(tahun, unit).then(data => {
                // Memuat Highcharts untuk pie chart
                Highcharts.chart('pie-chart', {
                    chart: {
                        type: 'pie'
                    },
                    title: {
                        text: 'Persentase Kejadian per Unit Kerja'
                    },
                    tooltip: {
                        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                    },
                    accessibility: {
                        point: {
                            valueSuffix: '%'
                        }
                    },
                    plotOptions: {
                        pie: {
                            allowPointSelect: true,
                            cursor: 'pointer',
                            dataLabels: {
                                enabled: true,
                                format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                            }
                        }
                    },
                    series: [{
                        name: 'Unit Kerja',
                        colorByPoint: true,
                        data: data
                    }]
                });

                // Menentukan kategori untuk sumbu X pada column chart
                const categories = data.map(item => item.name);

                // Memuat Highcharts untuk column chart
                Highcharts.chart('bar-chart', {
                    chart: {
                        type: 'column'
                    },
                    title: {
                        text: 'Jumlah Kejadian per Unit Kerja'
                    },
                    xAxis: {
                        categories: categories, // Menggunakan kategori unit atau tahun
                        title: {
                            text: tahun && unit ? 'Tahun dan Unit Kerja' : tahun ? 'Unit Kerja' : 'Tahun'
                        }
                    },
                    yAxis: {
                        title: {
                            text: ''
                        }
                    },
                    series: [{
                        name: 'Total',
                        colorByPoint: true,
                        data: data.map(item => item.y) // Data tanpa nama, hanya nilai total
                    }]
                });
            });
        });

        // Load data awal tanpa filter
        fetchData().then(data => {
            // Inisialisasi chart tanpa filter
            Highcharts.chart('pie-chart', {
                chart: {
                    type: 'pie'
                },
                title: {
                    text: 'Persentase Kejadian per Unit Kerja'
                },
                tooltip: {
                    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                },
                accessibility: {
                    point: {
                        valueSuffix: '%'
                    }
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: true,
                            format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                        }
                    }
                },
                series: [{
                    name: 'Unit Kerja',
                    colorByPoint: true,
                    data: data
                }]
            });

            // Menentukan kategori untuk sumbu X pada column chart
            const categories = data.map(item => item.name);

            Highcharts.chart('bar-chart', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Jumlah Kejadian per Unit Kerja'
                },
                xAxis: {
                    categories: categories, // Kategori unit atau tahun
                    title: {
                        text: 'Unit Kerja'
                    }
                },
                yAxis: {
                    title: {
                        text: ''
                    }
                },
                series: [{
                    name: 'Total',
                    colorByPoint: true,
                    data: data.map(item => item.y) // Data tanpa nama, hanya nilai total
                }]
            });
        });
    </script>



</body>

</html>