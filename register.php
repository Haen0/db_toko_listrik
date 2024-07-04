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
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(array('message' => 'Registrasi berhasil'));
    } else {
        echo json_encode(array('error' => 'Registrasi gagal: ' . $conn->error));
    }
} else {
    echo json_encode(array('error' => 'Metode request tidak valid'));
}

$conn->close();
?>
