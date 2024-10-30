<?php

include '../../config.php'; // Menyertakan file config.php

// Menyimpan waktu saat ini
$created_at = date("Y-m-d H:i:s");

// Validasi dan proses upload gambar
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
            // Mengambil data dari POST
            $judul = $_POST['judul'];
            $konten = $_POST['konten'];
            $gambar = $newFileName; // Simpan nama file ke dalam database

            // Query SQL untuk menyimpan data ke database
            $sql = "INSERT INTO berita (judul, gambar, konten, created_at) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);

            // Bind parameter
            $stmt->bind_param("ssss", $judul, $gambar, $konten, $created_at);

            // Menjalankan query
            if ($stmt->execute()) {
                $response = array(
                    "status" => "success",
                    "message" => "Berita berhasil ditambahkan.",
                    "data" => array(
                        "id" => $stmt->insert_id,
                        "judul" => $judul,
                        "gambar" => $gambar,
                        "konten" => $konten,
                        "created_at" => $created_at
                    )
                );
            } else {
                $response = array(
                    "status" => "error",
                    "message" => "Gagal menambahkan Berita: " . $stmt->error
                );
            }
            // Menutup statement
            $stmt->close();
        } else {
            $response = array(
                "status" => "error",
                "message" => "Gagal mengupload gambar."
            );
        }
    } else {
        $response = array(
            "status" => "error",
            "message" => "File yang diupload bukan gambar atau format tidak diizinkan."
        );
    }
} else {
    $response = array(
        "status" => "error",
        "message" => "Gambar harus diupload."
    );
}

// Menutup koneksi
$conn->close();

// Mengembalikan respons JSON
header('Content-Type: application/json');
echo json_encode($response);
?>