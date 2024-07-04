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

// Query to fetch products
// $sql = "SELECT id_produk, id_penjual, nama_produk, kategori, harga_produk, desc_produk, gambar_produk_1, gambar_produk_2, gambar_produk_3, gambar_produk_4, gambar_produk_5, stok_produk, rate FROM produks";

// $idProduk = $_GET['id_produk'];

$sql = "SELECT p.id_produk, p.nama_produk, p.kategori, p.harga_produk, p.desc_produk, 
        p.gambar_produk_1, p.gambar_produk_2, p.gambar_produk_3, p.gambar_produk_4, p.gambar_produk_5, 
        p.stok_produk, p.rate, pen.nama_penjual, pen.nama_toko
        FROM produks p 
        JOIN penjual pen ON p.id_penjual = pen.id_penjual";

$result = $conn->query($sql);

// Initialize an array to hold the products
$products = array();

// Check if there are results and fetch them
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}

// Set the content type to JSON
header('Content-Type: application/json');

// Output the products array in JSON format
echo json_encode($products);

// Close the connection
$conn->close();
?>
