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

/** Uploading PDF materials resources */
switch($method){
    case 'GET':
        $folderName = $_GET['folder_name'] ?? null;
        // Grouped folder fetch
        if ($folderName) {
            // Fetch specific folder with its resources
            $stmt = $pdo->prepare("SELECT id, folder_name FROM resource_folder WHERE folder_name = ?");
            $stmt->execute([$folderName]);
            $folder = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$folder) {
                respond(['success' => true, 'folders' => []]);
            }
            // Get resources in this folder
            $stmtR = $pdo->prepare("SELECT * FROM resources WHERE folder_id = ? ORDER BY created_at DESC");
            $stmtR->execute([$folder['id']]);
            $resources = $stmtR->fetchAll(PDO::FETCH_ASSOC);
            $folders = [[
                'folder_id' => $folder['id'],
                'folder_name' => $folder['folder_name'],
                'resources' => $resources
            ]];
            respond(['success' => true, 'folders' => $folders]);
        } else {
            // Fetch all folders with their resources
            $stmt = $pdo->query("SELECT id, folder_name FROM resource_folder ORDER BY folder_name ASC");
            $allFolders = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $folders = [];
            foreach ($allFolders as $f) {
                $stmtR = $pdo->prepare("SELECT * FROM resources WHERE folder_id = ? ORDER BY created_at DESC");
                $stmtR->execute([$f['id']]);
                $resources = $stmtR->fetchAll(PDO::FETCH_ASSOC);
                $folders[] = [
                    'folder_id' => $f['id'],
                    'folder_name' => $f['folder_name'],
                    'resources' => $resources
                ];
            }
            respond(['success' => true, 'folders' => $folders]);
        }
        break;
    case 'POST':
    try {
        $folderName = $_POST['folder_name'] ?? '';
        $title = $_POST['title'] ?? '';
        if (empty($folderName) || empty($title)) {
            respond(['success' => false, 'error' => 'Folder name and title are required'], 400);
        }
        if(!isset($_FILES['resource_pdf']) || $_FILES['resource_pdf']['error'] !== UPLOAD_ERR_OK){
            respond(['success' => false, 'error' => 'PDF file is required'], 400);
        }
        // 1. Check OR Create Folder in DB
        $stmt = $pdo->prepare("SELECT id FROM resource_folder WHERE folder_name = ?");
        $stmt->execute([$folderName]);
        $folder = $stmt->fetch(PDO::FETCH_ASSOC);
        if(!$folder) {
            // Folder doesn't exist, so create it in DB and on disk
            $stmt = $pdo->prepare("INSERT INTO resource_folder (folder_name) VALUES (?)");
            $stmt->execute([$folderName]);
            $folderId = $pdo->lastInsertId();
            $uploadDir = dirname(__DIR__, 2)."public/resources/{$folderName}";
            if(!is_dir($uploadDir)){
                mkdir($uploadDir, 0777, true);
            }
        } else {
            $folderId = $folder['id'];
            $uploadDir = dirname(__DIR__, 2)."public/resources/{$folderName}";
            if(!is_dir($uploadDir)){
                mkdir($uploadDir, 0777, true);
            }
        }
        // 2. Upload PDF
        $uploadResult = UploadHelper::uploadFile($_FILES['resource_pdf'], $uploadDir, ['pdf']);
        if(!$uploadResult['success']){
            respond(['success' => false, 'error' => $uploadResult['error']], 400);
        }
        $pdfPath = "resources/{$folderName}/" . basename($uploadResult['path']);
        // 3. Insert into DB
        $stmt = $pdo->prepare("INSERT INTO resources (pdf_path, title, folder_id) VALUES (?, ?, ?)");
        $stmt->execute([$pdfPath, $title, $folderId]);
        respond(['success' => true, 'message' => 'PDF uploaded successfully']);
    } catch (PDOException $e) {
        respond(['success' => false, 'error' => $e->getMessage()], 500);
    }
    break;
}