<?php

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: /pelaporan_peta/login");
    exit;
}

include '../../config.php'; // Menyertakan file config.php

// Mengambil data pengguna dari database
$sql = "SELECT * FROM user";
$result = $conn->query($sql);

$data = array(); // Array untuk menyimpan data

if ($result->num_rows > 0) {
    // Mengambil setiap baris data
    while ($row = $result->fetch_assoc()) {
        $data[] = $row; // Menambahkan baris data ke array
    }
}

// Menutup koneksi
$conn->close();

// Mengembalikan respons JSON
header('Content-Type: application/json');
echo json_encode($data);
?>