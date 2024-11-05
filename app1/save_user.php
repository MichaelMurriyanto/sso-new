<?php
// Konfigurasi database
$host = 'localhost';
$db = 'keycloak';
$user = 'root';
$pass = '';

// Koneksi ke database
$conn = new mysqli($host, $user, $pass, $db);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Menerima data dari AJAX
$data = json_decode(file_get_contents('php://input'), true);

$sub = $data['sub'];
$email_verified = $data['email_verified'];
$name = $data['name'];
$preferred_username = $data['preferred_username'];
$given_name = $data['given_name'];
$family_name = $data['family_name'];
$email = $data['email'];

// Mengecek apakah sub sudah ada di database
$checkQuery = $conn->prepare("SELECT * FROM user_info WHERE sub = ?");
$checkQuery->bind_param("s", $sub);
$checkQuery->execute();
$result = $checkQuery->get_result();

if ($result->num_rows > 0) {
    // Jika sub sudah ada, ambil data dari database
    $userData = $result->fetch_assoc();
} else {
    // Jika sub belum ada, simpan data dan ambil data yang baru disimpan
    $stmt = $conn->prepare("INSERT INTO user_info (sub, email_verified, name, preferred_username, given_name, family_name, email) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $sub, $email_verified, $name, $preferred_username, $given_name, $family_name, $email);

    if ($stmt->execute()) {
        // Mengambil data yang baru disimpan
        $userData = [
            "sub" => $sub,
            "email_verified" => $email_verified,
            "name" => $name,
            "preferred_username" => $preferred_username,
            "given_name" => $given_name,
            "family_name" => $family_name,
            "email" => $email,
        ];
    } else {
        echo json_encode(["status" => "error", "message" => "Gagal menyimpan data"]);
        exit;
    }
    $stmt->close();
}

$checkQuery->close();
$conn->close();

// Mengembalikan data pengguna dalam format JSON
echo json_encode(["status" => "success", "data" => $userData]);
?>
