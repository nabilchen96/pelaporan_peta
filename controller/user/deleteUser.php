<?php
require_once '../../config.php'; // Pastikan Anda mengubah path ini sesuai kebutuhan

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id']; // Ambil ID dari permintaan POST

    // Query untuk menghapus user
    $query = "DELETE FROM user WHERE id = ?";
    $stmt = $conn->prepare($query);

    if ($stmt->execute([$id])) {
        echo json_encode(['status' => 'success', 'message' => 'User berhasil dihapus.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Terjadi kesalahan saat menghapus user.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Metode tidak diizinkan.']);
}
?>