<?php

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: /pelaporan_peta/login");
    exit;
}

// Koneksi ke database
include $_SERVER['DOCUMENT_ROOT'] . '/pelaporan_peta/config.php';

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Ambil query dari searchForm
    $query = isset($_GET['query']) ? trim($_GET['query']) : '';

    // Ambil customQuery dari URL dan pecah menjadi parameter
    $customQuery = isset($_GET['customQuery']) ? trim($_GET['customQuery']) : '';
    $year = '';
    $unit = '';

    if (!empty($customQuery)) {
        // Pisahkan parameter tahun dan unit
        parse_str($customQuery, $params); // Menggunakan parse_str untuk mengurai parameter
        
        $year = isset($params['tahun']) ? $params['tahun'] : ''; // Ambil tahun
        $unit = isset($params['unit']) ? $params['unit'] : ''; // Ambil unit
    }

    // SQL dasar
    $sql = "SELECT * FROM peta";
    $conditions = [];
    $bindParameters = []; // Array untuk menyimpan parameter yang akan diikat

    // Cek apakah ada input tahun dari searchForm1
    if (!empty($year)) {
        $conditions[] = "YEAR(tanggal) = :year"; // Filter berdasarkan tahun dari tanggal
        $bindParameters[':year'] = $year; // Simpan parameter untuk binding
    }

    // Cek apakah ada input unit dari searchForm1
    if (!empty($unit)) {
        $conditions[] = "(unit_kerja LIKE :unit)"; // Filter berdasarkan unit_kerja
        $bindParameters[':unit'] = "%$unit%"; // Simpan parameter untuk binding
    }

    // Kondisi pencarian dari searchForm
    if (!empty($query)) {
        // Pecah query menjadi array kata kunci
        $keywords = explode(' ', $query);

        // Buat bagian WHERE dengan LIKE untuk setiap kata kunci di kolom unit_kerja atau keterangan
        $searchConditions = [];
        foreach ($keywords as $index => $word) {
            // Gunakan index untuk membuat nama parameter yang unik
            $paramName = ':word' . $index;
            $searchConditions[] = "(unit_kerja LIKE $paramName OR keterangan LIKE $paramName)";
            $bindParameters[$paramName] = "%$word%"; // Simpan parameter untuk binding
        }

        // Gabungkan kondisi pencarian menggunakan OR
        if (!empty($searchConditions)) {
            if (!empty($conditions)) {
                // Jika ada kondisi sebelumnya, gabungkan dengan AND
                $sql .= " WHERE " . implode(" OR ", $searchConditions) . " AND " . implode(" AND ", $conditions);
            } else {
                $sql .= " WHERE " . implode(" OR ", $searchConditions);
            }
        }
    } else if (!empty($conditions)) {
        // Jika hanya ada kondisi dari searchForm1
        $sql .= " WHERE " . implode(" AND ", $conditions);
    }

    // Siapkan statement
    $stmt = $conn->prepare($sql);

    // Bind semua parameter
    foreach ($bindParameters as $param => $value) {
        $stmt->bindValue($param, $value);
    }

    // Jalankan query
    $stmt->execute();

    // Ambil semua data sebagai array
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Ubah data ke format JSON
    echo json_encode($results);

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

$conn = null;
?>
