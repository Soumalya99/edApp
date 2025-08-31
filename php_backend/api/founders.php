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
    case 'GET':
        //get all founders or specific founder by ID
        $id = $_GET['id'] ?? null;

        if($id){
            //fetch founder's from database
            $stmt = $pdo->prepare("SELECT * FROM founders WHERE id=?");
            $stmt->execute([$id]);
            $founder = $stmt->fetch(PDO::FETCH_ASSOC);

            if($founder){
                respond($founder);
            }else{
                respond(['error' => 'Founder not found'], 404);
            }
        }else{
            $stmt = $pdo->query("SELECT * FROM founders ORDER BY created_at DESC");
            $founders = $stmt->fetchAll(PDO::FETCH_ASSOC);
            respond($founders);
        }
        break;
    case 'POST':
        //create founder's profile
        $name = $_POST['founder_name'] ?? '';

        if(empty($name)){
            respond(['success' => false, 'error' => 'Name is required'], 400);
        };

        // ✅ CHECK FOR DUPLICATES FIRST (before upload)
        $stmt = $pdo->prepare("SELECT id, name FROM founders WHERE LOWER(name) = LOWER(?)");
        $stmt->execute([$name]);
        $existingFounder = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($existingFounder) {
            respond([
                'error' => 'Founder with this name already exists',
                'existing_founder' => $existingFounder
            ], 409); // 409 Conflict
        }
        
        
        $photoPath = null;
        //handle photo uploads
        if(isset($_FILES['founder_image']) && $_FILES['founder_image']['error'] === UPLOAD_ERR_OK){
            $uploadDir = 'public/founders';
            $uploadedResult = UploadHelper::uploadFile($_FILES['founder_image'], $uploadDir, ['jpg', 'jpeg', 'png']);

            if(!$uploadedResult['success']){
                respond(['success' => false, 'error' => $uploadedResult['error']], 400);
            }
            $photoPath = $uploadedResult['path'];

        }else{
            respond(['success' => false, 'error' => 'Photo is required'], 400);
        }

        //CHECK IF FOUNDER ALREADY EXISTS
        $stmt = $pdo->prepare("INSERT INTO founders (name, image_path) VALUES (?,?)");
        $stmt->execute([$_POST['founder_name'], $photoPath]);

        respond([
            'success' => true,
            'id' => $pdo->lastInsertId(),
            'message' => 'Founder created successfully'
        ]);
        break;

    case 'DELETE':
        //delete founder's profile
        $id = $_GET['id'] ?? null;

        if(!$id){
            respond(['success' => false, 'error' => 'ID is required'], 400);
        };

        //get the founder to delete the photo
        $stmt = $pdo->prepare("SELECT * FROM founders WHERE id=?");
        $stmt->execute([$id]);
        $founder = $stmt->fetch(PDO::FETCH_ASSOC);

        if(!$founder){
            respond(['success' => false, 'error' => 'Founder not found'], 404);
        };

        //delete founder from database
        $stmt = $pdo->prepare("DELETE FROM founders WHERE id=?");
        $stmt->execute([$id]);

        //delete photo
        if($founder['photo_path']){
            UploadHelper::deleteFile($founder['photo_path']);
        };
        respond([
            'success' => true,
            'message' => 'Founder deleted successfully'
        ]);
        break;
    default:
        respond(['success' => false, 'error' => 'Invalid request method'], 405);
}


?>