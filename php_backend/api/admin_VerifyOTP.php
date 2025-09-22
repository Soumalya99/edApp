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

try{
    // Get OTP and session key from POST
    $input = json_decode(file_get_contents('php://input'), true);
    $otp = $input['otp'] ?? null;
    $sessionKey = $input['session'] ?? null;

    if (!$otp || !$sessionKey) {
        echo json_encode(["success" => false, "error" => "Missing OTP or session key."]);
        exit;
    }

    // Check if OTP exists in session for this session key
    if (!isset($_SESSION[$sessionKey]['otp'])) {
        echo json_encode(["success" => false, "error" => "OTP not found or expired."]);
        exit;
    }

    if ($_SESSION[$sessionKey]['otp'] == $otp) {
        unset($_SESSION[$sessionKey]); // Prevent OTP reuse
        echo json_encode(["success" => true, "message" => "OTP verified. Redirecting to reset password."]);
        exit;
    } else {
        echo json_encode(["success" => false, "error" => "Invalid OTP."]);
        exit;
    }

}catch (PDOException $e) {
    error_log('ForgotPassword PDOException: ' . $e->getMessage());
    echo json_encode(["success" => false, "error" => "Internal server error."]);
    exit;
}
