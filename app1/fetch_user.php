<?php
$servername = "db"; // Mengacu ke nama service dari Docker Compose
$username = "keycloak"; // Sesuaikan dengan username pada Docker Compose
$password = "password"; // Sesuaikan dengan password pada Docker Compose
$dbname = "keycloak"; // Sesuaikan dengan nama database pada Docker Compose

// Membuat koneksi ke database
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ambil keycloak_id dari parameter URL
$keycloak_id = $_GET['keycloak_id'] ?? '';

if ($keycloak_id) {
    $stmt = $conn->prepare("SELECT email, email_verified, name, preferred_username, given_name, family_name FROM users WHERE keycloak_id = ?");
    $stmt->bind_param("s", $keycloak_id);
    $stmt->execute();
    $stmt->bind_result($email, $email_verified, $name, $preferred_username, $given_name, $family_name);

    $user_data = [];
    if ($stmt->fetch()) {
        $user_data = [
            "email" => $email,
            "email_verified" => $email_verified,
            "name" => $name,
            "preferred_username" => $preferred_username,
            "given_name" => $given_name,
            "family_name" => $family_name
        ];
    } else {
        $user_data = [
            "email" => "Not found",
            "email_verified" => false,
            "name" => "Not found",
            "preferred_username" => "Not found",
            "given_name" => "Not found",
            "family_name" => "Not found"
        ];
    }

    $stmt->close();
    $conn->close();

    echo json_encode($user_data);
} else {
    echo json_encode(["error" => "Keycloak ID not provided"]);
}
?>
