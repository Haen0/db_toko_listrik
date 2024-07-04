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

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari body request
    $id_pembeli = $_POST['id_pembeli']; // Ganti dengan nama field yang sesuai di tabel Anda
    $id_produk = $_POST['id_produk']; // Ganti dengan nama field yang sesuai di tabel Anda
    $quantity = $_POST['quantity']; // Ganti dengan nama field yang sesuai di tabel Anda

    // Cek apakah produk dengan id_produk dan id_pembeli sudah ada di keranjang
    $checkSql = "SELECT quantity FROM keranjang WHERE id_pembeli = '$id_pembeli' AND id_produk = '$id_produk'";
    $result = $conn->query($checkSql);

    if ($result->num_rows > 0) {
        // Jika produk sudah ada di keranjang, tambahkan quantity
        $row = $result->fetch_assoc();
        $newQuantity = $row['quantity'] + $quantity;
        $updateSql = "UPDATE keranjang SET quantity = '$newQuantity' WHERE id_pembeli = '$id_pembeli' AND id_produk = '$id_produk'";

        if ($conn->query($updateSql) === TRUE) {
            // Jika berhasil diperbarui, kirim respons sukses
            echo json_encode(array('message' => 'Quantity produk diperbarui di keranjang'));
        } else {
            // Jika gagal diperbarui, kirim respons gagal
            echo json_encode(array('error' => 'Gagal memperbarui quantity produk di keranjang: ' . $conn->error));
        }
    } else {
        // Jika produk belum ada di keranjang, tambahkan baris baru
        $insertSql = "INSERT INTO keranjang (id_pembeli, id_produk, quantity) VALUES ('$id_pembeli', '$id_produk', '$quantity')";

        if ($conn->query($insertSql) === TRUE) {
            // Jika berhasil disimpan, kirim respons sukses
            echo json_encode(array('message' => 'Produk ditambahkan ke keranjang'));
        } else {
            // Jika gagal disimpan, kirim respons gagal
            echo json_encode(array('error' => 'Gagal menambahkan produk ke keranjang: ' . $conn->error));
        }
    }
} else {
    // Handle method selain POST
    echo json_encode(array('error' => 'Metode tidak diizinkan'));
}

$conn->close();
?>
