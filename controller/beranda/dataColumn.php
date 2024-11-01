<?php

// dataColumn.php
header('Content-Type: application/json');
include '../../config.php'; // Pastikan Anda sudah menghubungkan ke database

// Mendapatkan parameter dari URL
$tahun = isset($_GET['tahun']) ? $_GET['tahun'] : null;
$unit = isset($_GET['unit']) ? $_GET['unit'] : null;

// Menyiapkan query SQL dasar
$sql = "SELECT unit_kerja, COUNT(*) as total FROM peta";

// Menambahkan kondisi berdasarkan parameter `tahun` dan `unit` jika ada
$conditions = [];
if ($tahun) {
    $conditions[] = "YEAR(tanggal) = '$tahun'";
}
if ($unit) {
    $conditions[] = "unit_kerja = '$unit'";
}

// Menyusun query dengan kondisi jika ada
if (!empty($conditions)) {
    $sql .= " WHERE " . implode(" AND ", $conditions);
}

// Menambahkan GROUP BY pada query
$sql .= " GROUP BY unit_kerja";

$result = $conn->query($sql);

$data = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = [
            'name' => $row['unit_kerja'], // Nama unit kerja
            'y' => (int) $row['total'],    // Jumlah kejadian di setiap unit kerja
        ];
    }
}

// Mengembalikan data dalam format JSON
echo json_encode($data);
