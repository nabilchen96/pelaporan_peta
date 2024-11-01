<?php
session_start();
require '../config.php'; // mengimpor file konfigurasi database

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Query untuk mendapatkan data user berdasarkan email
    $sql = "SELECT * FROM user WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Jika user ditemukan, verifikasi password
    if ($user && password_verify($password, $user['password'])) {
        // Menyimpan informasi user di session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];

        // Redirect ke halaman beranda setelah login berhasil
        header("Location: /pelaporan_peta/beranda");
        exit;
    } else {
        // Simpan pesan error dalam session dan redirect ke halaman login
        $_SESSION['login_error'] = "Email atau password salah.";
        header("Location: /pelaporan_peta");
        exit;
    }
}
?>
