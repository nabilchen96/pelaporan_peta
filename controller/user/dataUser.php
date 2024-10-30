<?php
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