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
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
        session_start();
        $_SESSION['user_id'] = $row['id'];
        echo json_encode(array('message' => 'Login berhasil', 'user_id' => $row['id']));
        } else {
        echo json_encode(array('error' => 'Password salah'));
        }
        } else {
        echo json_encode(array('error' => 'Email tidak ditemukan'));
    }
    }

$conn->close();
?>
