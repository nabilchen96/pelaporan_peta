<?php
session_start(); // Memulai session
require '../../config.php'; // Koneksi ke database
require '../../vendor/autoload.php'; // Autoload dari PHPSpreadsheet

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;

if (isset($_FILES['file'])) {
    $file = $_FILES['file'];
    $fileName = $file['name'];
    $fileTmpName = $file['tmp_name'];
    $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

    // Validasi tipe file harus xlsx
    if ($fileExtension !== 'xlsx') {
        $_SESSION['message'] = "File harus dalam format .xlsx!";
        $_SESSION['status'] = "error";
        header("Location: /pelaporan_peta/peta");
        exit;
    }

    try {
        // Load spreadsheet
        $spreadsheet = IOFactory::load($fileTmpName);
        $sheet = $spreadsheet->getActiveSheet();
        $highestRow = $sheet->getHighestRow();

        // Mulai transaksi untuk menghindari data gagal sebagian
        $conn->begin_transaction();

        // Loop setiap baris mulai dari baris ke-2 (asumsi baris pertama header)
        for ($row = 2; $row <= $highestRow; $row++) {
            $tanggal = $sheet->getCell('A' . $row)->getValue();
            $longitude = $sheet->getCell('B' . $row)->getValue();
            $latitude = $sheet->getCell('C' . $row)->getValue();
            $unit_kerja = $sheet->getCell('D' . $row)->getValue();
            $keterangan = $sheet->getCell('E' . $row)->getValue();

            // Konversi tanggal jika berupa angka
            if (is_numeric($tanggal)) {
                $tanggal = Date::excelToDateTimeObject($tanggal)->format('Y-m-d');
            }

            // Persiapkan dan eksekusi perintah SQL untuk insert data
            $stmt = $conn->prepare("INSERT INTO peta (tanggal, longitude, latitude, unit_kerja, keterangan) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param('sddss', $tanggal, $longitude, $latitude, $unit_kerja, $keterangan);
            $stmt->execute();
        }

        $conn->commit(); // Commit transaksi
        $_SESSION['message'] = "Data berhasil diimport ke database!";
        $_SESSION['status'] = "success";
    } catch (Exception $e) {
        $conn->rollback(); // Rollback transaksi jika terjadi kesalahan
        $_SESSION['message'] = "Gagal mengimport data: " . $e->getMessage();
        $_SESSION['status'] = "error";
    }
    header("Location: /pelaporan_peta/peta");
} else {
    $_SESSION['message'] = "File belum diupload.";
    $_SESSION['status'] = "error";
    header("Location: /pelaporan_peta/peta");
}
?>