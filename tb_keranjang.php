<?php
// Database credentials
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tokolistrik";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query SQL untuk mengambil data keranjang
$sql = "SELECT k.id_keranjang, k.id_pembeli, k.id_produk, k.quantity, p.nama_produk, p.harga_produk, p.gambar_produk_1 
        FROM keranjang k
        JOIN produks p ON k.id_produk = p.id_produk
        WHERE k.id_pembeli = 1
        ";

$result = $conn->query($sql);

// Initialize array untuk menyimpan data keranjang
$keranjang = array();

// Memeriksa apakah ada hasil dari query dan mengambil data
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $keranjang[] = $row;
    }
}

// Mengatur tipe konten menjadi JSON
header('Content-Type: application/json');

// Mengonversi array keranjang menjadi format JSON dan mengirimkannya sebagai respons
echo json_encode($keranjang);

// Menutup koneksi
$conn->close();
?>