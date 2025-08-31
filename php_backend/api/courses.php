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

//detecting http method
$method = $_SERVER['REQUEST_METHOD'];

switch($method){
    case 'GET':
        //get all the data from the request body
        /* to handle track level like /batch.html?track=neet&level=class11 */
        $id = $_GET['id'] ?? null;
        $track = $_GET['track'] ?? null;
        $level = $_GET['level'] ?? null;

        if(!$id){
            /* build query with optional filtering */
            $sql = "SELECT * FROM courses";//base query
            $condition = [];
            $params = [];
            if($track){
                $condition[] = "track=?";
                $params[] = $track;
            }
            if($level){
                $condition[] = "level=?";
                $params[] = $level;
            }
            /* if condition is not wmpty
            concatinate the base sql query with the condition[filters] for searching
            and to prevent sql injection
            e.g. $sql = "SELECT * FROM courses "
            $sql .= "WHERE" .implode("AND", $condition)
            (this .= concatinate condition with base sql query)

            Result = SELECT * FROM courses WHERE track='neet' AND level='class11'
            */
            if (!empty($condition)) {
                $sql .= " WHERE " . implode(" AND ", $condition); // note spaces before WHERE, AND, and after
            }

            $sql .= " ORDER BY created_at DESC"; //order by created_at in descending order

            /* do a lookup in database */
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if($courses){
                respond($courses);
            }else{
                respond(['success' => false, 'error' => 'No courses found'], 404);
            }

        }else{
            /* get the specific course data from the database*/
            $stmt = $pdo->prepare("SELECT * FROM courses WHERE id = ?");
            $stmt->execute([$id]);
            $course = $stmt->fetch(PDO::FETCH_ASSOC);

            if($course){
                respond($course);
            }else{
                respond(['success' => false, 'error' => 'Course not found'], 404);
            }
        }
        break;

    case 'POST':
        //get all the data from the request body
        $title = $_POST['batch_title'] ?? '';
        $description = $_POST['batch_inclusives'] ?? '';
        $price = $_POST['current_price'] ?? 0;
        $track = $_POST['track'] ?? '';
        $level = $_POST['level'] ?? '';

        //validating req body for non empty
        if(empty($title) || empty($description) || $price <= 0){
            respond(['success' => false, 'error' => 'All fields are required and price should be greater than 0'], 400);
        };

        $imagePath = null;

        //handle batch image upload
        if(isset($_FILES['batch_image']) && $_FILES['batch_image']['error'] === UPLOAD_ERR_OK){
            //if no error then continue with image upload process
            $uploadedResult = UploadHelper::uploadFile($_FILES['batch_image'], 'public/batches', ['jpg', 'jpeg', 'png']);

            if(!$uploadedResult['success']){
                respond(['success' => false, 'error' => $uploadedResult['error']], 400);
            }

            $imagePath = $uploadedResult['path'];
        }
        else{
            respond(['success' => false, 'error' => 'Batch image is required'], 400);
        };

        if($imagePath) error_log('[PostUploadCourse] Uploaded to: '.$imagePath);
        //inserting data into database
        $stmt = $pdo->prepare("INSERT INTO courses (title, description, price, batch_image_path, track, level) VALUES (?,?,?,?,?,?)");
        $stmt->execute([$title, $description, $price, $imagePath, $track, $level]);
        respond(['success' => true, 'message' => 'Course created successfully'], 201);
        break;
    
    case 'PUT':
        //update course batch
        parse_str(file_get_contents("php://input"), $putData);
        $id = $putData['id']?? $_POST['id'] ?? null;
        $title = $putData['batch_title']?? $_POST['batch_title'] ?? '';
        $description = $putData['batch_inclusives']?? $_POST['batch_inclusives'] ?? '';
        $price = $putData['current_price']?? $_POST['current_price'] ?? 0;
        $track = $putData['track']?? $_POST['track'] ?? '';
        $level = $putData['level']?? $_POST['level'] ?? '';

        //validation
        if(!$id || empty($title) || empty($description) || $price <= 0){
            respond(['success' => false, 'error' => 'All fields are required and price should be greater than 0'], 400);
        };
        //check if course exists
        $stmt = $pdo->prepare("SELECT * FROM courses WHERE id = ?");
        $stmt->execute([$id]);
        $course = $stmt->fetch(PDO::FETCH_ASSOC);

        //validate if course exist or not
        if(!$course){
            respond(['success' => false, 'error' => 'Course not found'], 404);
        }
        //handle batch image upload
        $imagePath = $course['batch_image'];

        //Handling the new image upload
        if(isset($_FILES['batch_image']) && $_FILES['batch_image']['error'] === UPLOAD_ERR_OK) {
            //if no error then continue with image upload process
            $uploadedResult = UploadHelper::uploadFile($_FILES['batch_image'], 'public/batches', ['jpg', 'jpeg', 'png']);

            if(!$uploadedResult['success']){
                respond(['success' => false, 'error' => $uploadedResult['error']], 400);
            };

            //delete existing image to update to new image
            if($course['batch_image']){
                UploadHelper::deleteFile($course['batch_image']);
            }
            $imagePath = $uploadedResult['path'];
        };

        //update Database
        $stmt = $pdo->prepare("UPDATE courses SET title = ?, description = ?, price = ?, batch_image_path = ?, track = ?, level = ? WHERE id = ?");
        $stmt->execute([$title, $description, $price, $imagePath, $track, $level, $id]);
        respond(['success' => true, 'message' => 'Course updated successfully'], 200);
        break;
    
    case 'DELETE':
        //delete course and for which we need $id
        $id = $_GET['id'] ?? null;

        if(!$id){
            respond(['success' => false, 'error' => 'ID is required'], 400);
        };

        //get course data first before deleting 
        $stmt = $pdo->prepare("SELECT * FROM courses WHERE id = ?");
        $stmt->execute([$id]);
        $course = $stmt->fetch(PDO::FETCH_ASSOC);

        if(!$course){
            respond(['success' => false, 'error' => 'Course not found'], 404);
        };

        //delete course from database
        $stmt = $pdo->prepare("DELETE FROM courses WHERE id = ?");
        $stmt->execute([$id]);

        //delete batch image file
        if($course['batch_image']){
            UploadHelper::deleteFile($course['batch_image']);
        }
        respond(['success' => true, 'message' => 'Course deleted successfully'], 200);
        break;
    default:
        respond(['success' => false, 'error' => 'Invalid request method'], 405);

}

?>


