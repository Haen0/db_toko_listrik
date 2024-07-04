<?php

$host = 'localhost';
$username = 'root';
$password = '';
$database = 'tokolistrik';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Koneksi ke database gagal: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $id_pesanan = $_GET['id_pesanan'];

    // Fetch invoice details
    $stmt = $conn->prepare("SELECT * FROM pesanan WHERE id_pesanan = ?");
    $stmt->bind_param("i", $id_pesanan);
    $stmt->execute();
    $result = $stmt->get_result();
    $invoice = $result->fetch_assoc();

    // Fetch product details
    $stmt_produk = $conn->prepare("SELECT p.nama_produk as name, pp.quantity as qty, p.harga as price 
                                    FROM produk_pesanan pp 
                                    JOIN produk p ON pp.id_produk = p.id_produk 
                                    WHERE pp.id_pesanan = ?");
    $stmt_produk->bind_param("i", $id_pesanan);
    $stmt_produk->execute();
    $result_produk = $stmt_produk->get_result();
    $items = $result_produk->fetch_all(MYSQLI_ASSOC);

    $invoice['items'] = $items;

    echo json_encode($invoice);
} else {
    echo json_encode(array('error' => 'Metode tidak diizinkan'));
}

$conn->close();

?>
