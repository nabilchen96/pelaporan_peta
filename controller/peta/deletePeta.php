<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: /pelaporan_peta/login");
    exit;
}

require_once '../../config.php'; // Pastikan path ini sudah benar

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id']; // Ambil ID dari permintaan POST

    // Query untuk menghapus user
    $query = "DELETE FROM peta WHERE id = ?";
    $stmt = $conn->prepare($query);

    // Menggunakan bind_param untuk mengikat parameter
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'User berhasil dihapus.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Terjadi kesalahan saat menghapus user.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Metode tidak diizinkan.']);
}
?>
