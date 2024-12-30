<?php
header("Content-Type: application/json");

// Konfigurasi database
$servername = "127.0.0.1"; // Nama server
$username = "jdih_bnpb";   // Username database
$password = "jdih_T@n99uH@@#!"; // Password database
$dbname = "jdih_2024";     // Nama database

// Membuat array untuk response JSON
$varjson = array();
$row_array = (object)array();

// config base url
$base_url = "https://jdih.bnpb.go.id/storage/";
$base_detail_url = "https://jdih.bnpb.go.id/peraturan/";

try {
    // Aktifkan error reporting untuk MySQLi
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    // Membuat koneksi ke MySQL
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Atur charset (opsional, jika diperlukan)
    $conn->set_charset("utf8mb4");

    // Query untuk mendapatkan data
    $sql = "SELECT * FROM peraturans";
    $result = $conn->query($sql);

    // Memproses hasil query
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Memastikan setiap kolom ada dengan menggunakan isset()
            $row_array->idData = isset($row["id"]) ? $row["id"] : "-";
            $row_array->tahun_pengundangan = isset($row["tahunTerbit"]) ? $row["tahunTerbit"] : "-";
            $row_array->tanggal_pengundangan = isset($row["tglPengundangan"]) ? $row["tglPengundangan"] : "-";
            $row_array->jenis = isset($row["jenisDok"]) ? $row["jenisDok"] : "-";
            $row_array->noPeraturan = isset($row["nomor"]) ? $row["nomor"] : "-";
            $row_array->judul = isset($row["judul"]) ? $row["judul"] : "-";
            $row_array->noPanggil = isset($row["noPanggil"]) ? $row["noPanggil"] : "-";
            $row_array->singkatanJenis = isset($row["singkatan"]) ? $row["singkatan"] : "-";
            $row_array->tempatTerbit = isset($row["tempatPenetapan"]) ? $row["tempatPenetapan"] : "-";
            $row_array->penerbit = isset($row["penerbit"]) ? $row["penerbit"] : "-";
            $row_array->deskripsiFisik = isset($row["deskripsiFisik"]) ? $row["deskripsiFisik"] : "-";
            $row_array->sumber = isset($row["sumber"]) ? $row["sumber"] : "-";
            $row_array->subjek = isset($row["subjek"]) ? $row["subjek"] : "-";
            $row_array->isbn = isset($row["isbn"]) ? $row["isbn"] : "-";
            $row_array->status = isset($row["status"]) ? $row["status"] : "-";
            $row_array->bahasa = isset($row["bahasa"]) ? $row["bahasa"] : "-";
            $row_array->bidangHukum = isset($row["bidangHukum"]) ? $row["bidangHukum"] : "-";
            $row_array->teuBadan = isset($row["teuBadan"]) ? $row["teuBadan"] : "-";
            $row_array->nomorIndukBuku = isset($row["nomorIndukBuku"]) ? $row["nomorIndukBuku"] : "-";
            $row_array->fileDownload = isset($row["lampiran"]) ? basename($row["lampiran"]) : "-";
            $row_array->urlDownload = isset($row["lampiran"])
                ? $base_url . basename($row["lampiran"])
                : "-";
            $row_array->abstrak = isset($row["abstrak"]) ? $row["abstrak"] : "-";
            $row_array->urlabstrak = isset($row["urlabstrak"]) ? $row["urlabstrak"] : "-";
            $row_array->urlDetailPeraturan = isset($row["slug"])
                ? $base_detail_url . $row["slug"] . '/detail'
                : "-";
            $row_array->operasi = "4";
            $row_array->display = "1";

            // Menambahkan data ke dalam array
            array_push($varjson, json_decode(json_encode($row_array)));
        }

        // Mengirimkan hasil dalam format JSON
        echo json_encode($varjson, JSON_UNESCAPED_SLASHES);
    } else {
        // Jika tidak ada data, kirimkan respons JSON
        echo json_encode([
            "status" => "error",
            "message" => "0 results"
        ]);
    }
} catch (mysqli_sql_exception $e) {
    // Menangani error koneksi atau query
    echo json_encode([
        "status" => "error",
        "message" => "Error: " . $e->getMessage()
    ]);
} finally {
    // Pastikan koneksi ditutup jika sudah dibuka
    if (isset($conn) && $conn->connect_error == null) {
        $conn->close();
    }
}
