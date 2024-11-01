<?php

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: /pelaporan_peta/login");
    exit;
}

include '../../config.php'; // Menyertakan file config.php

// Cek apakah request adalah POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    // Jika bukan POST, arahkan kembali atau tampilkan pesan error
    header("Location: /pelaporan_peta/user"); // Atau halaman lain sesuai kebutuhan
    exit;
}

// Mengambil data dari POST
$nama = $_POST['nama'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash password
$created_at = date("Y-m-d H:i:s"); // Menyimpan waktu saat ini

// Cek apakah email sudah ada di database
$sqlCheckEmail = "SELECT COUNT(*) FROM user WHERE email = ?";
$stmtCheck = $conn->prepare($sqlCheckEmail);
$stmtCheck->bind_param("s", $email);
$stmtCheck->execute();
$stmtCheck->bind_result($emailCount);
$stmtCheck->fetch();
$stmtCheck->close();

if ($emailCount > 0) {
    // Jika email sudah ada, kembalikan respons kesalahan
    $response = array(
        "status" => "error",
        "message" => "Email sudah terdaftar. Silakan gunakan email lain."
    );
} else {
    // Jika email belum ada, lanjutkan untuk menyimpan data pengguna
    $sql = "INSERT INTO user (nama, email, password, created_at) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    // Bind parameter
    $stmt->bind_param("ssss", $nama, $email, $password, $created_at);

    if ($stmt->execute()) {
        $response = array(
            "status" => "success",
            "message" => "User berhasil ditambahkan.",
            "data" => array(
                "id" => $stmt->insert_id,
                "nama" => $nama,
                "email" => $email,
                "created_at" => $created_at // Menggunakan variabel created_at
            )
        );
    } else {
        $response = array(
            "status" => "error",
            "message" => "Gagal menambahkan user: " . $stmt->error
        );
    }

    // Menutup statement
    $stmt->close();
}

// Menutup koneksi
$conn->close();

// Mengembalikan respons JSON
header('Content-Type: application/json');
echo json_encode($response);
?>