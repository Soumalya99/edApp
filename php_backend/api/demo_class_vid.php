<?php 

require_once __DIR__ . '/../config/conf.php';

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
        //Get all demo class videos
        $id = $_GET['id'] ?? null;
        $courseId = $_GET['course_id'] ?? null;

        if(!$id){
            $sql = "
            SELECT dc.*, c.title as course_title
            FROM demo_classes dc
            LEFT JOIN courses c ON dc.course_id = c.id
            ";

            $params = [];

            if($courseId){
                $sql .= " WHERE dc.course_id = ? ORDER BY dc.created_at DESC";
                $params[] = $courseId;
            } else {
                $sql .= " ORDER BY dc.created_at DESC";
            }
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            $demoClassVideos = $stmt->fetchAll(PDO::FETCH_ASSOC);

            respond([
                'success' => true,
                'demo_class_videos' => $demoClassVideos
            ]);
                
        }else{
            $stmt = $pdo->prepare("
                SELECT dc.*, c.title as course_title 
                FROM demo_classes dc 
                LEFT JOIN courses c ON dc.course_id = c.id 
                WHERE dc.id = ?
            ");
            $stmt->execute([$id]);
            $demoClassVideo = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($demoClassVideo) {
                respond($demoClassVideo);
            } else {
                respond(['error' => 'Demo class not found'], 404);
            }
        }
    case 'POST':
        // Create new demo class video link
        $track = $_POST['track'] ?? null;
        $level = $_POST['level'] ?? null;
        $videoLink = $_POST['video_link'] ?? '';

        if (!$track || !$level || empty($videoLink)) {
            respond(['success' => false, 'error' => 'Track, Level, and Video Link are required'], 400);
        }

        // Validate video link format
        if (!filter_var($videoLink, FILTER_VALIDATE_URL)) {
            respond(['success' => false, 'error' => 'Invalid video link format'], 400);
        }

        // Get the course id based on track and level
        $stmt = $pdo->prepare("SELECT id FROM courses WHERE track = ? AND level = ?");
        $stmt->execute([$track, $level]);
        $course = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$course) {
            respond(['success' => false, 'error' => 'Course not found for provided track and level'], 404);
        }
        $courseId = $course['id'];

        // Insert new demo class video link into the database
        $stmt = $pdo->prepare("INSERT INTO demo_classes (course_id, video_link) VALUES (?, ?)");
        $stmt->execute([$courseId, $videoLink]);

        respond([
            'success' => true,
            'id' => $pdo->lastInsertId(),
            'message' => 'Demo class video link added successfully'
        ], 201);
        break;

    case 'DELETE':
        //Delete a demo class video
        $videoId = $_GET['id'] ?? null;

        if(!$videoId){
            respond(['success' => false, 'error' => 'Video ID is required'], 400);
        }

        //Check if this demo class with id exist
        $stmt = $pdo->prepare("SELECT * FROM demo_classes WHERE id = ?");
        $stmt->execute([$videoId]);
        $demoClassVideo = $stmt->fetch(PDO::FETCH_ASSOC);

        if(!$demoClassVideo){
            respond(['success' => false, 'error' => 'Demo class video not found'], 404);
        }
        //Delete the demo class video from the database
        $stmt = $pdo->prepare("DELETE FROM demo_classes WHERE id = ?");
        $stmt->execute([$videoId]);

        respond([
            'success' => true,
            'message' => 'Demo class video deleted successfully'
        ]);
        break;

    default:
        respond(['success' => false, 'error' => 'Invalid request method'], 405);
}

?>