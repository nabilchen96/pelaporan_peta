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
    header("Location: /pelaporan_peta/berita"); // Atau halaman lain sesuai kebutuhan
    exit;
}

// Mengambil ID dari POST untuk menentukan data yang akan diperbarui
$id = $_POST['id'];

// Validasi dan proses upload gambar jika ada gambar yang diupload
if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['gambar']['tmp_name'];
    $fileName = $_FILES['gambar']['name'];
    $fileSize = $_FILES['gambar']['size'];
    $fileType = $_FILES['gambar']['type'];
    $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    // Ekstensi file yang diizinkan
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

    // Validasi file hanya gambar
    if (in_array($fileExtension, $allowedExtensions) && strpos($fileType, 'image/') === 0) {
        // Nama unik untuk file gambar
        $newFileName = uniqid() . '.' . $fileExtension;
        $uploadFileDir = '../../asset/gambar_berita/';
        $destPath = $uploadFileDir . $newFileName;

        // Memindahkan file ke direktori tujuan
        if (move_uploaded_file($fileTmpPath, $destPath)) {
            $gambar = $newFileName; // Simpan nama file baru jika ada gambar diupload
        } else {
            $response = array(
                "status" => "error",
                "message" => "Gagal mengupload gambar."
            );
            // Mengembalikan respons JSON dan berhenti
            header('Content-Type: application/json');
            echo json_encode($response);
            exit();
        }
    } else {
        $response = array(
            "status" => "error",
            "message" => "File yang diupload bukan gambar atau format tidak diizinkan."
        );
        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    }
} else {
    // Jika tidak ada gambar baru yang diupload, gunakan gambar lama
    $sql = "SELECT * FROM berita WHERE id = $id";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $gambar = $row['gambar'];
    } else {
        $gambar = "";
    }
}

// Mengambil data dari POST
$judul = $_POST['judul'];
$konten = $_POST['konten'];
$iframe = $_POST['iframe'] ?? '';

// Query SQL untuk memperbarui data di database
$sql = "UPDATE berita SET judul = ?, gambar = ?, konten = ?, iframe = ? WHERE id = ?";
$stmt = $conn->prepare($sql);

// Bind parameter
$stmt->bind_param("ssssi", $judul, $gambar, $konten, $iframe, $id);

// Menjalankan query
if ($stmt->execute()) {
    $response = array(
        "status" => "success",
        "message" => "Berita berhasil diperbarui.",
        "data" => array(
            "id" => $id,
            "judul" => $judul,
            "gambar" => $gambar,
            "konten" => $konten
        )
    );
} else {
    $response = array(
        "status" => "error",
        "message" => "Gagal memperbarui Berita: " . $stmt->error
    );
}

// Menutup statement dan koneksi
$stmt->close();
$conn->close();

// Mengembalikan respons JSON
header('Content-Type: application/json');
echo json_encode($response);
?>