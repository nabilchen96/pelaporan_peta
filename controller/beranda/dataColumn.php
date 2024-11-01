<?php

// dataColumn.php
header('Content-Type: application/json');
include '../../config.php'; // Pastikan Anda sudah menghubungkan ke database

// Mendapatkan parameter dari URL
$tahun = isset($_GET['tahun']) ? $_GET['tahun'] : null;
$unit = isset($_GET['unit']) ? $_GET['unit'] : null;

// Menyiapkan query SQL dasar
if ($tahun && $unit) {
    // Jika `tahun` dan `unit` keduanya diisi
    $sql = "SELECT unit_kerja, COUNT(*) as total 
            FROM peta 
            WHERE YEAR(tanggal) = '$tahun' 
            AND unit_kerja = '$unit' 
            GROUP BY unit_kerja";
} elseif ($tahun) {
    // Jika hanya `tahun` yang diisi
    $sql = "SELECT unit_kerja, COUNT(*) as total 
            FROM peta 
            WHERE YEAR(tanggal) = '$tahun' 
            GROUP BY unit_kerja";
} elseif ($unit) {
    // Jika hanya `unit` yang diisi
    $sql = "SELECT YEAR(tanggal) as tahun, COUNT(*) as total 
            FROM peta 
            WHERE unit_kerja = '$unit' 
            GROUP BY tahun";
} else {
    // Jika tidak ada filter, tampilkan semua data per unit kerja
    $sql = "SELECT unit_kerja, COUNT(*) as total 
            FROM peta 
            GROUP BY unit_kerja";
}

$result = $conn->query($sql);

$data = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        if ($unit && !$tahun) {
            // Jika hanya unit yang diisi, tampilkan per tahun
            $data[] = [
                'name' => $row['tahun'], // Tahun
                'y' => (int) $row['total'], // Jumlah kejadian per tahun untuk unit tertentu
            ];
        } else {
            // Untuk kasus lainnya, tampilkan berdasarkan unit kerja
            $data[] = [
                'name' => $row['unit_kerja'], // Nama unit kerja
                'y' => (int) $row['total'],    // Jumlah kejadian di setiap unit kerja
            ];
        }
    }
}

// Mengembalikan data dalam format JSON
echo json_encode($data);
