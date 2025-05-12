<?php
$success = false;

require_once 'db.php';

$host = "localhost";
$dbname = "currency_converter";
$username = "root";
$password = "";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = trim($_POST["first_name"]);
    $last_name = trim($_POST["last_name"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];
    $country = $_POST["country"];
    $currency = $_POST["currency"];

    if (empty($first_name) || empty($last_name) || empty($email) || empty($password) || empty($confirm_password) || empty($country) || empty($currency)) {
        die("Please fill all boxes.");
    }

    if ($password !== $confirm_password) {
        die("Passwords don't match.");
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $profile_pic_path = null;

    if (isset($_FILES["profile_pic"]) && $_FILES["profile_pic"]["error"] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES["profile_pic"]["tmp_name"];
        $file_name = $_FILES["profile_pic"]["name"];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed_exts = ["jpg", "jpeg", "png", "gif"];

        if (!in_array($file_ext, $allowed_exts)) {
            die("Photo extension isn't supported. Please pick a JPG, PNG or GIF file.");
        }

        $new_file_name = uniqid("profile_", true) . '.' . $file_ext;
        $upload_dir = "uploads/";
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $destination = $upload_dir . $new_file_name;
        if (!move_uploaded_file($file_tmp, $destination)) {
            die("Photo upload failed.");
        }

        $profile_pic_path = $destination;
    }

    $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, email, password, country, currency, profile_pic)
                            VALUES (:first_name, :last_name, :email, :password, :country, :currency, :profile_pic)");

    $stmt->execute([
        ":first_name" => $first_name,
        ":last_name" => $last_name,
        ":email" => $email,
        ":password" => $hashed_password,
        ":country" => $country,
        ":currency" => $currency,
        ":profile_pic" => $profile_pic_path
    ]);

    $success = true;
}
?>
