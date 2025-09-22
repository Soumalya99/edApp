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

/**
 * Send an OTP WhatsApp message via HTTP API
 *
 * @param string $phone   The 10-digit phone (Indian) number
 * @param string $otp     The OTP to send
 * @return bool           True if sent, false otherwise
 */
function sendOTPViaWhatsApp($phone, $otp) {
    // Format the phone number in international format (e.g., +91XXXXXXXXXX)
    $fullNumber = '+91' . preg_replace('/^0+/', '', $phone);

    // WhatsApp API endpoint and data (replace with your provider)
    $apiUrl = "https://your-whatsapp-provider.com/send_message";
    $apiKey = "YOUR_API_KEY_HERE"; // Secure this!

    $data = [
        "to" => $fullNumber,
        "type" => "text",
        "message" => "Your verification OTP is: $otp"
    ];

    $headers = [
        "Authorization: Bearer $apiKey",
        "Content-Type: application/json"
    ];

    $ch = curl_init($apiUrl);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $result = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // Custom: consider 200-299 HTTP code as success
    return ($httpCode >= 200 && $httpCode < 300);
}




$username = trim($_POST['username'] ?? '');
$phone = trim($_POST['phone'] ?? '');

if (empty($username) || empty($phone)) {
    echo json_encode(["success" => false, "error" => "Username and phone are required."]);
    exit;
}
if (!preg_match('/^[0-9]{10}$/', $phone)) {
    echo json_encode(["success" => false, "error" => "Phone must be 10 digits."]);
    exit;
}

try {
    // Query for admin by username and phone
    $stmt = $pdo->prepare("SELECT username, phone FROM admins WHERE username = :username AND phone = :phone LIMIT 1");
    $stmt->execute(['username' => $username, 'phone' => $phone]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    // Security: Respond generic error on not found
    if (!$admin) {
        sleep(1); // mitigate timing attacks
        echo json_encode(["success" => false, "error" => "Invalid username or phone number."]);
        exit;
    }

    // Generate random 4-digit OTP
    $otp = strval(random_int(1000, 9999));
    $otpKey = 'reset_' . md5($username . $phone . session_id());
    $_SESSION[$otpKey] = [
        'otp' => $otp,
        'username' => $username,
        'phone' => $phone,
        'created_at' => time()
    ];
    // TODO: Integrate WhatsApp/SMS API here. Example:
    sendOTPViaWhatsApp($phone, $otp);  

    echo json_encode([
        "success" => true,
        "message" => "OTP sent to your registered WhatsApp number.",
        "session" => $otpKey
    ]);
    exit;
} catch (PDOException $e) {
    error_log('ForgotPassword PDOException: ' . $e->getMessage());
    echo json_encode(["success" => false, "error" => "Internal server error."]);
    exit;
}
