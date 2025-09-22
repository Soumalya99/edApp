<?php
require_once __DIR__ . '/../config/conf.php';
require_once __DIR__ . '/../uploads/upload_helper.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

function respond($data, $status=200){
    http_response_code($status);
    echo json_encode($data);
    exit;
};

$method = $_SERVER['REQUEST_METHOD'];

switch($method){
    

    case 'POST':
        // Register new admin
        $username = trim($_POST['username'] ?? '');
        $contactNumber = trim($_POST['phone'] ?? ''); 
        $password = $_POST['password'] ?? '';
        $confirm = trim($_POST['confirm'] ?? '');

        if(empty($username) || empty($contactNumber) || empty($password) || empty($confirm)){
            respond(['success' => false, 'error' => 'All fields are required'], 400);
        }
        if($password !== $confirm){
            respond(['success' => false, 'error' => 'Passwords do not match'], 400);
        }
        if(strlen($password) < 6){
            respond(['success' => false, 'error' => 'Password must be at least 6 characters'], 400);
        }
        if(!preg_match('/^[0-9]{10}$/', $contactNumber)){
            respond(['success' => false, 'error' => 'Contact number must be between 10 digits'], 400);
        }
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        try{
            // Insert new admin into database
            $stmt = $pdo->prepare("INSERT INTO admins (username, contactNumber, password) VALUES (:username, :contactNumber, :password)");
            $stmt->execute([
                'username' => $username,
                'contactNumber' => $contactNumber,
                'password' => $hashedPassword
            ]);
            respond(['success' => true, 'message' => 'Admin registered successfully'], 201);
        }catch(PDOException $e){
            if($e->getCode() == 23000){ // Integrity constraint violation (e.g., duplicate username)
                respond(['success' => false, 'error' => 'Username already exists'], 409);
            }else{
                respond(['success' => false, 'error' => 'Database error: ' . $e->getMessage()], 500);
            }
        }
        break;
    default:
        respond(['success' => false, 'error' => 'Invalid request method'], 405);
}


?>