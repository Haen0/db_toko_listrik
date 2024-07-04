<?php

// Pastikan koneksi ke database sudah tersedia
$host = 'localhost'; // Ganti dengan host database Anda
$username = 'root'; // Ganti dengan username database Anda
$password = ''; // Ganti dengan password database Anda
$database = 'tokolistrik'; // Ganti dengan nama database Anda

$conn = new mysqli($host, $username, $password, $database);

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi ke database gagal: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lakukan query untuk menghapus produk dari keranjang (sesuai dengan logika bisnis Anda)
    $sql = "DELETE FROM keranjang WHERE id_produk IN (SELECT id_produk FROM produk_pesanan)";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(array('message' => 'Cart items removed successfully'));
    } else {
        echo json_encode(array('error' => 'Failed to remove cart items'));
    }
} else {
    echo json_encode(array('error' => 'Method not allowed'));
}

$conn->close();
?>