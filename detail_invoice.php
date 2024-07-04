<?php

$host = 'localhost';
$username = 'root';
$password = '';
$database = 'tokolistrik';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Koneksi ke database gagal: " . $conn->connect_error);
}

if (isset($_GET['id_pesanan'])) {
    $id_pesanan = $_GET['id_pesanan'];

    $sql = "
        SELECT p.id_pesanan, p.tanggal_pesanan, p.status_pesanan, p.alamat_pesanan, p.metode_pembayaran, p.total_pembayaran, 
            pp.id_produk, pp.quantity, pr.nama_produk, pr.harga_produk, pr.gambar_produk_1
        FROM pesanan p
        JOIN produk_pesanan pp ON p.id_pesanan = pp.id_pesanan
        JOIN produks pr ON pp.id_produk = pr.id_produk
        WHERE p.id_pesanan = '$id_pesanan'
    ";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $orders = [];
        while ($row = $result->fetch_assoc()) {
            $id_pesanan = strval($row['id_pesanan']); // Konversi ke string
            $total_pembayaran = strval($row['total_pembayaran']); // Konversi ke string

            if (!isset($orders[$id_pesanan])) {
                $orders[$id_pesanan] = [
                    'orderNo' => $id_pesanan,
                    'date' => isset($row['tanggal_pesanan']) ? strval($row['tanggal_pesanan']) : null, // Konversi ke string jika perlu
                    'status' => isset($row['status_pesanan']) ? strval($row['status_pesanan']) : null, // Konversi ke string jika perlu
                    'shippingAddress' => isset($row['alamat_pesanan']) ? $row['alamat_pesanan'] : null,
                    'paymentMethod' => isset($row['metode_pembayaran']) ? $row['metode_pembayaran'] : null,
                    'total' => $total_pembayaran,
                    'items' => [],
                ];
            }

            $orders[$id_pesanan]['items'][] = [
                'image' => isset($row['gambar_produk_1']) ? $row['gambar_produk_1'] : null,
                'name' => isset($row['nama_produk']) ? $row['nama_produk'] : 'Unknown',
                'qty' => isset($row['quantity']) ? intval($row['quantity']) : 0,
                'price' => isset($row['harga_produk']) ? floatval($row['harga_produk']) : 0.0,
            ];
        }

        echo json_encode($orders[$id_pesanan]);
    } else {
        echo json_encode(['error' => 'Pesanan tidak ditemukan']);
    }
} else {
    echo json_encode(['error' => 'ID pesanan tidak diberikan']);
}

$conn->close();

?>
