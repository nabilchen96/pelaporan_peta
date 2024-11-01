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

    // Simpan hasil dengan store_result() lalu ambil data menggunakan bind_result()
    $stmt->store_result();

    // Mendapatkan metadata dari kolom
    $meta = $stmt->result_metadata();
    $fields = [];
    $data = [];

    // Mengikat hasil query ke variabel dinamis
    while ($field = $meta->fetch_field()) {
        $fields[] = &$data[$field->name];
    }

    call_user_func_array([$stmt, 'bind_result'], $fields);

    // Ambil data
    if ($stmt->fetch()) {
        // Verifikasi password
        if (password_verify($password, $data['password'])) {
            // Menyimpan informasi user di session
            $_SESSION['user_id'] = $data['id'];
            $_SESSION['user_name'] = $data['name'];

            // Redirect ke halaman beranda setelah login berhasil
            header("Location: /pelaporan_peta/beranda");
            exit;
        }
    }

    // Redirect ke halaman login jika gagal
    header("Location: /pelaporan_peta?error=1");
    exit;
}
?>
