<?php 
require_once __DIR__ .'/../config/conf.php';
require_once __DIR__ .'/../uploads/upload_helper.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin:*');
header('Access-Control-Allow-Methods:GET,POST,PUT,DELETE');
header('Access-Control-Allow-Headers:Content-Type');

function respond($data, $status = 200){
    http_response_code($status);
    echo json_encode($data);
    exit;
};
$method = $_SERVER['REQUEST_METHOD'];

switch($method){
    case 'GET':
        // Get all candidates or specific candidate by ID
        $id = $_GET['id'] ?? null;
        
        if ($id) {
            $stmt = $pdo->prepare("SELECT * FROM candidates WHERE id = ?");
            $stmt->execute([$id]);
            $candidate = $stmt->fetch();
            
            if ($candidate) {
                respond($candidate);
            } else {
                respond(['error' => 'Candidate not found'], 404);
            }
        } else {
            $stmt = $pdo->query("SELECT * FROM candidates ORDER BY created_at DESC");
            $candidates = $stmt->fetchAll();
            respond($candidates);
        }
        break;
    case 'POST':
        //Creating new candidate
        $name = $_POST['candidate_name'] ?? '';

        if(empty($name)){
            respond(['success' => false, 'error' => 'Name is required'], 400);
        };

        $imagePath  = null;

        if(isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK){
            $uploadedResult = UploadHelper::uploadFile($_FILES['image'], 'public/candidates', ['jpg', 'jpeg', 'png']);
            
            if(!$uploadedResult['success']){
                respond(['success' => false, 'error' => $uploadedResult['error']], 400);
            }
            $imagePath = $uploadedResult['path'];
        }else{
            respond(['error' => 'Image is required'], 400);
        };

        //insert into database
        $stml = $pdo->prepare("INSERT INTO candidates (name, image_path) VALUES(?,?)");
        $stml->execute([$name, $imagePath]);

        respond(['success' => true, 'message' => 'Candidate created successfully'], 201);
        break;

    case 'PUT':
        //Update candidates image
        parse_str(file_get_contents("php://input"), $putData);

        $id = $putData['id'] ?? $_POST['id'] ?? null;
        $name = $putData['name'] ?? $_POST['name'] ?? '';

        if(!$id){
            respond(['success' => false, 'error' => 'ID is required'], 400);
        };


        //Check if candidate exists
        $stmt = $pdo->prepare("SELECT * FROM candidates WHERE id = ?");
        $stmt->execute([$id]);
        $candidate = $stmt->fetch();

        // Preserve existing image path unless a new image is uploaded
        $imagePath = $candidate['image_path'];

        //Handle new image upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadResult = UploadHelper::uploadFile($_FILES['image'], 'public/candidates', ['jpg', 'jpeg', 'png']);
            
            if (!$uploadResult['success']) {
                respond(['error' => $uploadResult['error']], 400);
            }
            
            // Delete old image
            if (!empty($candidate['image_path'])) {
                UploadHelper::deleteFile($candidate['image_path']);
            }
            
            $imagePath = $uploadResult['path'];
        };
        // Update database
        $stmt = $pdo->prepare("UPDATE candidates SET name = ?, image_path = ? WHERE id = ?");
        $stmt->execute([$name, $imagePath, $id]);
        
        respond(['success' => true, 'message' => 'Candidate updated successfully']);
        break;

    case 'DELETE':
        // Delete candidate
        $id = $_GET['id'] ?? null;
        
        if (!$id) {
            respond(['error' => 'ID is required'], 400);
        }
        
        // Get candidate to delete image file
        $stmt = $pdo->prepare("SELECT * FROM candidates WHERE id = ?");
        $stmt->execute([$id]);
        $candidate = $stmt->fetch();
        
        if (!$candidate) {
            respond(['error' => 'Candidate not found'], 404);
        }
        
        // Delete from database
        $stmt = $pdo->prepare("DELETE FROM candidates WHERE id = ?");
        $stmt->execute([$id]);
        
        // Delete image file
        if ($candidate['image_path']) {
            UploadHelper::deleteFile($candidate['image_path']);
        }
        
        respond(['success' => true, 'message' => 'Candidate deleted successfully']);
        break;
    
    default:
        respond(['error' => 'Method not allowed'], 405);

}
?>