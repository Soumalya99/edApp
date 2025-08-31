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
        // Get all teachers or specific teacher by ID
        $id = $_GET['id'] ?? null;

        if(!$id){
            //do a lookup in db
            $sql = "SELECT * FROM teachers ORDER BY created_at DESC";
            //optionally add basic filtering
            $limit = $_GET['limit'] ?? null;
            if($limit){
                $sql .= " LIMIT ".intval($limit);
            };

            //Do the db lookup
            $stmt = $pdo->query($sql);
            $teachers = $stmt->fetchAll(PDO::FETCH_ASSOC);

            respond([
                'success' => true,
                // 'total' => count($teachers),
                'teachers' => $teachers
            ], 200);
        }else{

        }
    case 'POST':
        // Create new teacher
        $name = $_POST['teacher_name'] ?? '';
        $bio = $_POST['teacher_bio'] ?? '';

        if(empty($name)){
            respond(['success' => false, 'error' => 'Name is required'], 400);
        }

        $imagePath = null;

        //Handle profile image upload
        if(isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK){
            $uploadDir = 'public/teachers';
            $uploadedResult = UploadHelper::uploadFile($_FILES['profile_image'], $uploadDir, ['jpg', 'jpeg', 'png']);

            if(!$uploadedResult['success']){
                respond(['success' => false, 'error' => $uploadedResult['error']], 400);
            }
            $imagePath = $uploadedResult['path'];
        }else{
            respond(['success' => false, 'error' => 'Profile image is required'], 400);
        }
        // Save teacher data to the database
        $stmt = $pdo->prepare("INSERT INTO teachers (name, bio, profile_image) VALUES (?, ?, ?)");
        $stmt->execute([$name, $bio, $imagePath]);
        respond([
            'success' => true,
            'message' => 'Teacher created successfully'
        ], 201);
        break;

    case 'DELETE':
        // Delete a teacher by ID
        $id = $_GET['id'] ?? null;

        if(!$id){
            respond(['success' => false, 'error' => 'ID is required'], 400);
        }

        //Get teacher from db to delete it
        $stmt = $pdo->prepare("SELECT * FROM teachers WHERE id = ?");
        $stmt->execute([$id]);
        $teacher = $stmt->fetch(PDO::FETCH_ASSOC);

        if(!$teacher){
            respond(['success' => false, 'error' => 'Teacher not found'], 404);
        }
        //Delete from teachers table
        $stmt = $pdo->prepare("DELETE FROM teachers WHERE id = ?");
        $stmt->execute([$id]);
        
        //Remove image from teaxhers table if exist
        if($teacher['profile_image']){
            UploadHelper::deleteFile($teacher['profile_image']);
        };

        respond([
            'success'=> true,
            'message'=> 'Teacher deleted successfully'
        ], 200);
        break;

    default:
        respond(['success' => false, 'error' => 'Invalid request method'], 405);
}

?>
