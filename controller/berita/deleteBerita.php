<?php

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: /pelaporan_peta/login");
    exit;
}

require_once '../../config.php'; // Pastikan Anda mengubah path ini sesuai kebutuhan

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id']; // Ambil ID dari permintaan POST

    // Query untuk menghapus user
    $query = "DELETE FROM berita WHERE id = ?";
    $stmt = $conn->prepare($query);

    if ($stmt->execute([$id])) {
        echo json_encode(['status' => 'success', 'message' => 'Berita berhasil dihapus.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Terjadi kesalahan saat menghapus berita.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Metode tidak diizinkan.']);
}
?>