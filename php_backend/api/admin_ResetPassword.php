<?php
// Enable error reporting for development
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');
require_once __DIR__.'/../config/conf.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["success" => false, "error" => "Invalid request method."]);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$username = trim($input['username'] ?? '');
$phone = trim($input['phone'] ?? '');
$password = $input['password'] ?? '';
$sessionKey = $input['session'] ?? '';

if (empty($username) || empty($phone) || empty($password) || empty($sessionKey)) {
    echo json_encode(["success" => false, "error" => "Missing required parameters."]);
    exit;
}

// Check session validity and match username/phone against session
if (!isset($_SESSION[$sessionKey]) ||
    $_SESSION[$sessionKey]['username'] !== $username ||
    $_SESSION[$sessionKey]['phone'] !== $phone) {
    echo json_encode(["success" => false, "error" => "Session or identity verification failed."]);
    exit;
}

try {
    // Check admin
    $stmt = $pdo->prepare("SELECT id FROM admins WHERE username = :username AND contactNumber = :phone LIMIT 1");
    $stmt->execute(['username' => $username, 'phone' => $phone]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$admin) {
        echo json_encode(["success" => false, "error" => "Admin not found."]);
        exit;
    }
    
    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    // Update password
    $stmtUp = $pdo->prepare("UPDATE admins SET password = :password WHERE id = :id");
    $stmtUp->execute(['password' => $hashedPassword, 'id' => $admin['id']]);
    
    // Clear reset session
    unset($_SESSION[$sessionKey]);

    echo json_encode(["success" => true, "message" => "Password has been reset successfully."]);
    exit;
} catch (PDOException $e) {
    error_log('ResetPassword PDOException: ' . $e->getMessage());
    echo json_encode(["success" => false, "error" => "Internal server error."]);
    exit;
}
