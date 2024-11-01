<?php

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: /pelaporan_peta/login");
    exit;
}

include '../../config.php'; // Sesuaikan path ke file database Anda

$response = ["status" => "error", "message" => "Terjadi kesalahan."];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;

    // Perbarui data ke database
    $query = $password
        ? "UPDATE user SET nama = ?, email = ?, password = ? WHERE id = ?"
        : "UPDATE user SET nama = ?, email = ? WHERE id = ?";

    $stmt = $conn->prepare($query);
    if ($password) {
        $stmt->bind_param("sssi", $nama, $email, $password, $id);
    } else {
        $stmt->bind_param("ssi", $nama, $email, $id);
    }

    if ($stmt->execute()) {
        $response = ["status" => "success", "message" => "Data berhasil diperbarui."];
    } else {
        $response = ["status" => "error", "message" => "Gagal memperbarui data."];
    }
}

echo json_encode($response);
?>