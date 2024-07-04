<?php
// Database credentials
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tokolistrik";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi ke database gagal: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productId = $_POST['product_id'];

    $sql = "DELETE FROM keranjang WHERE id_produk = '$productId'";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(array('message' => 'Produk berhasil dihapus'));
    } else {
        echo json_encode(array('error' => 'Gagal menghapus produk'));
    }
} else {
    echo json_encode(array('error' => 'Metode request tidak valid'));
}

$conn->close();
?>
