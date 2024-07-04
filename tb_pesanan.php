<?php

$host = 'localhost'; // Ganti dengan host database Anda
$username = 'root'; // Ganti dengan username database Anda
$password = ''; // Ganti dengan password database Anda
$database = 'tokolistrik'; // Ganti dengan nama database Anda

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Koneksi ke database gagal: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $status = $_GET['status']; // Mengambil status pesanan dari parameter URL

    $sql_pesanan = "SELECT p.id_pesanan, p.id_pembeli, p.status_pesanan, p.alamat_pesanan, p.metode_pembayaran, p.total_pembayaran, p.tanggal_pesanan, 
                    pp.id_produk, pr.nama_produk, pr.harga_produk, pr.gambar_produk_1, pp.quantity 
                    FROM pesanan p
                    JOIN produk_pesanan pp ON p.id_pesanan = pp.id_pesanan
                    JOIN produks pr ON pp.id_produk = pr.id_produk
                    WHERE p.status_pesanan = '$status'";

    $result = $conn->query($sql_pesanan);

    $orders = [];
    while ($row = $result->fetch_assoc()) {
        $id_pesanan = $row['id_pesanan'];

        if (!isset($orders[$id_pesanan])) {
            $orders[$id_pesanan] = [
                'orderNo' => $id_pesanan,
                'buyer' => $row['id_pembeli'], // Atur sesuai kebutuhan
                'date' => $row['tanggal_pesanan'],
                'items' => [],
                'total' => $row['total_pembayaran'],
                'status' => $row['status_pesanan'],
            ];
        }

        $orders[$id_pesanan]['items'][] = [
            'image' => $row['gambar_produk_1'],
            'name' => $row['nama_produk'],
            'qty' => $row['quantity'],
            'price' => $row['harga_produk'],
        ];
    }

    echo json_encode(array_values($orders));
} else {
    echo json_encode(array('error' => 'Metode tidak diizinkan'));
}

$conn->close();
?>
