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
                <form id="searchForm">
                    <div class="input-group mb-3 w-100">
                        <select name="" class="form-select" id="">
                            <option value="">Tahun 2024</option>
                        </select>
                        <select name="" class="form-select" id="">
                            <option value="">Unit Sarpras</option>
                        </select>
                        <button type="submit" class="input-group-text" id="basic-addon2">Search</button>
                    </div>
                </form>
                <div id="bar-chart" style="height: 280px;"></div>
                <hr>
                <div id="pie-chart" style="height: 250px;"></div>
            </div>
            <div class="col-lg-8">
                <form id="searchForm">
                    <div class="input-group mb-3 w-100">
                        <input type="text" id="searchQuery" class="form-control"
                            placeholder="Cari Perihal atau Keterangan">
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

    <script src="https://code.highcharts.com/highcharts.js"></script>

    <script>
        var map = L.map('map').setView([51.505, -0.09], 13);

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);
    </script>

    <script>
        Highcharts.chart('pie-chart', {
            chart: { type: 'pie' },
            title: { text: 'Pie Chart Example' },
            series: [{
                name: 'Data Share',
                data: [
                    { name: 'Category A', y: 40 },
                    { name: 'Category B', y: 25 },
                    { name: 'Category C', y: 20 },
                    { name: 'Category D', y: 15 }
                ]
            }]
        });
    </script>

    <script>
        Highcharts.chart('bar-chart', {
            chart: { type: 'column' },
            title: { text: 'Bar Chart Example' },
            xAxis: {
                categories: ['Apples', 'Bananas', 'Oranges', 'Pears', 'Grapes'],
                title: { text: '' }
            },
            yAxis: {
                title: { text: '' }
            },
            series: [{
                name: 'Fruit Consumption',
                data: [5, 7, 3, 8, 2]
            }]
        });
    </script>
</body>

</html>