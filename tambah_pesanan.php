<?php

$host = 'localhost'; // Ganti dengan host database Anda
$username = 'root'; // Ganti dengan username database Anda
$password = ''; // Ganti dengan password database Anda
$database = 'tokolistrik'; // Ganti dengan nama database Anda

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Koneksi ke database gagal: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_pembeli = $_POST['id_pembeli'];
    $alamat = $_POST['alamat'];
    $metode_pembayaran = $_POST['metode_pembayaran'];
    $total_pembayaran = $_POST['total_pembayaran'];
    $tanggal_pesanan = date('Y-m-d H:i:s');

    // Mulai transaksi
    $conn->begin_transaction();

    try {
        // Insert into tabel pesanan
        $sql_pesanan = "INSERT INTO pesanan (id_pembeli, status_pesanan, alamat_pesanan, metode_pembayaran, total_pembayaran, tanggal_pesanan)
                        VALUES ('$id_pembeli', 'Unpaid', '$alamat', '$metode_pembayaran', '$total_pembayaran', '$tanggal_pesanan')";
        if (!$conn->query($sql_pesanan)) {
            throw new Exception("Gagal menyimpan pesanan: " . $conn->error);
        }

        $id_pesanan = $conn->insert_id;

        // Insert into tabel produk_pesanan
        $produk_pesanan = $_POST['produk_pesanan']; // Expects JSON array
        $produk_pesanan = json_decode($produk_pesanan, true);

        foreach ($produk_pesanan as $produk) {
            $id_produk = $produk['id_produk'];
            $quantity = $produk['quantity'];

            $sql_produk_pesanan = "INSERT INTO produk_pesanan (id_pesanan, id_produk, quantity) VALUES ('$id_pesanan', '$id_produk', '$quantity')";
            if (!$conn->query($sql_produk_pesanan)) {
                throw new Exception("Gagal menyimpan produk pesanan: " . $conn->error);
            }
        }

        // Commit transaksi
        $conn->commit();
        echo json_encode(array('id_pesanan' => $id_pesanan, 'message' => 'Pesanan berhasil disimpan'));
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(array('error' => $e->getMessage()));
    }
} else {
    echo json_encode(array('error' => 'Metode tidak diizinkan'));
}

$conn->close();
?>
