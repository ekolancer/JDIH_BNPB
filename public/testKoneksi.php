<?php
$host = '127.0.0.1'; // atau 'localhost'
$db = 'jdih_2024'; // Nama database
$user = 'jdih_bnpb'; // Username database
$pass = 'jdih_T@n99uH@@#!'; // Password database

try {
    // Membuat koneksi menggunakan PDO
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Query untuk mendapatkan seluruh data dari tabel peraturans
    $sql = "SELECT * FROM peraturans";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    // Mengambil hasil sebagai array asosiatif
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Menampilkan data dalam format JSON
    header('Content-Type: application/json');
    echo json_encode($data, JSON_PRETTY_PRINT);

} catch (PDOException $e) {
    // Menangani error koneksi atau query
    header('Content-Type: application/json');
    echo json_encode([
        "status" => "error",
        "message" => "Koneksi gagal: " . $e->getMessage()
    ]);
}