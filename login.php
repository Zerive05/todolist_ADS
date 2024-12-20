<?php
header("Content-Type: application/json");

// Koneksi ke database
$servername = "localhost";
$username = "root"; // Sesuaikan dengan username MySQL Anda
$password = ""; // Sesuaikan dengan password MySQL Anda
$dbname = "dbtodolist"; // Nama database

$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Database connection failed: " . $conn->connect_error]);
    exit;
}

// Ambil data dari request
$data = json_decode(file_get_contents("php://input"), true);

// Validasi input
if (!isset($data["nama"]) || !isset($data["password"])) {
    echo json_encode(["success" => false, "message" => "Missing username or password"]);
    exit;
}

$nama = $data["nama"];
$password_input = $data["password"];

// Query untuk memeriksa username dan password
$sql = "SELECT * FROM users WHERE nama = ? AND password = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(["success" => false, "message" => "Database query failed: " . $conn->error]);
    exit;
}

$stmt->bind_param("ss", $nama, $password_input);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode(["success" => true, "message" => "Login successful"]);
} else {
    echo json_encode(["success" => false, "message" => "Invalid username or password"]);
}

$stmt->close();
$conn->close();
?>
