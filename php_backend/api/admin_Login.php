<?php

// It's crucial that session_start() is called before any output.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Recommended: Enable error reporting during development for easier debugging.
// In production, these should be turned off and errors should be logged to a file.
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// This file should establish a PDO connection and set it to throw exceptions on error.
// Example:
// $pdo = new PDO(...);
// $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
require_once __DIR__.'/../config/conf.php';

/**
 * Redirects to a given URL and terminates the script.
 * @param string $url The URL to redirect to.
 */
function redirect(string $url): void {
    header('Location: ' . $url);
    exit;
}

// --- Main Logic ---

// Only process POST requests.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('../../adminLogin.php');
}

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

if (empty($username) || empty($password)) {
    redirect('../../adminLogin.php?error=Username+and+password+required');
}

try {
    // Query for admin by username using a prepared statement.
    // Assuming the primary key is 'admin_id' and aliasing it to 'id'.
    // If your column name is different, change 'admin_id' to the correct name.
    $stmt = $pdo->prepare("SELECT username, password FROM admins WHERE username = :username");
    $stmt->execute(['username' => $username]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    // --- DEBUGGING STEP ---
    // If you are being redirected with an "Invalid credentials" error,
    // the condition below is failing. Uncomment the following lines
    // to inspect the values in your PHP error log.
    
    if (!$admin) {
        error_log("Admin login failed: No user found for username '{$username}'");
    } elseif (!password_verify($password, $admin['password'])) {
        error_log("Admin login failed: Password verification failed for username '{$username}'.");
        // Also, check if the 'password' column in your 'admins' table is VARCHAR(255).
        // A shorter length can truncate the hash, causing verification to fail.
    }
    

    // Verify admin exists and password is correct.
    if ($admin && password_verify($password, $admin['password'])) {
        // Login successful, regenerate session ID to prevent session fixation.
        session_regenerate_id(true);

        // Set session variables.
        $_SESSION['is_admin_logged_in'] = true;
        $_SESSION['admin_username'] = $admin['username'];
        // $_SESSION['admin_id'] = $admin['id'];

        // Redirect to the dashboard.
        redirect('../../adminDashboard.php');
    } else {
        // Invalid credentials.
        // A small delay can help mitigate timing-based username enumeration attacks.
        sleep(1);
        redirect('../../adminLogin.php?error=Invalid+username+or+password');
    }
} catch (PDOException $e) {
    // --- PRODUCTION-SAFE CODE ---
    // In a production environment, you should log this error and avoid showing details.
    error_log('Admin Login PDOException: ' . $e->getMessage());
    redirect('../../adminLogin.php?error=An+internal+error+occurred');
}