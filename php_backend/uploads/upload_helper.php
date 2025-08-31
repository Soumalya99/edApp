<?php 
class UploadHelper{
    public static function uploadFile($file, $uploadDir, $allowedTypes = 
    ['jpg', 'jpeg', 'png', 'pdf']){

        // Simple and reliable project root detection
        $projectRootPath = dirname(dirname(__FILE__)); // Go up from uploads/ to php_backend/
        $projectRootPath = dirname($projectRootPath);   // Go up from php_backend/ to EduPlatform/
        
        $originalUploadDir = $uploadDir;
        
        // Check if uploadDir is already an absolute path
        $isDriveAbsolute = (strlen($uploadDir) >= 3 && $uploadDir[1] === ':' && ($uploadDir[2] === '\\' || $uploadDir[2] === '/'));
        
        if (!$isDriveAbsolute) {
            // Build the full path relative to the chosen project root
            $uploadDir = $projectRootPath . DIRECTORY_SEPARATOR . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, ltrim($uploadDir, '/\\'));
        } else {
            $xamppProjectRoot = 'C:\\xampp\\htdocs\\EduPlatform';
            // Map absolute paths back into the chosen project root when they point under XAMPP or contain a /public/ segment
            $normalized = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $uploadDir);
            // If the absolute path starts with the XAMPP project root, remap to dev project root
            if (stripos($normalized, $xamppProjectRoot) === 0) {
                $uploadDir = $projectRootPath . substr($normalized, strlen($xamppProjectRoot));
            } else {
                // If the path contains a public segment, rebuild it under the dev project root
                $forward = str_replace(DIRECTORY_SEPARATOR, '/', $normalized);
                $publicPos = stripos($forward, '/public/');
                if ($publicPos !== false) {
                    $tail = substr($forward, $publicPos + strlen('/public/'));
                    $uploadDir = $projectRootPath . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $tail);
                } else {
                    $uploadDir = $normalized; // leave as-is
                }
            }
        }
        
        // Ensure trailing directory separator
        $uploadDir = rtrim($uploadDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

        // Debug info
        file_put_contents(__DIR__ . '/../../../debug_upload_log.txt', print_r([
            'original_uploadDir' => $originalUploadDir,
            'normalized_uploadDir' => $uploadDir,
            'project_root' => $projectRootPath,
            'input_name' => isset($file['name']) ? $file['name'] : 'NONE',
            'file_error' => isset($file['error']) ? $file['error'] : 'NONE',
            'tmp_name_exists' => (isset($file['tmp_name']) && file_exists($file['tmp_name'])) ? 'yes':'no',
            'is_uploaded_file' => (isset($file['tmp_name']) && is_uploaded_file($file['tmp_name'])) ? 'yes':'no',
            'tmp_name' => isset($file['tmp_name']) ? $file['tmp_name'] : 'NONE',
            'is_upload_dir_writable' => is_writable($uploadDir) ? 'yes':'NO',
        ], true), FILE_APPEND);
        //check firstly if file was uploaded without error
        if($file['error'] != UPLOAD_ERR_OK){
            return ['success' => false, 'error' => 'Upload failed with error code : ' . $file['error']];
        }

        //Validate the file size
        $maxSize = 7 * 1024 * 1024; //7MB
        if($file['size'] > $maxSize){
            return ['success' => false, 'error' => 'File size exceeds the limit of 7MB'];
        }

        //Getting/ splitting out the file extension for upload purpose
        $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        //Validating file type
        if(!in_array($fileExtension, $allowedTypes)){
            return ['success' => false, 'error' => 'Invalid file type. Allowed types are : ' . implode(', ', $allowedTypes)];
        };

        //Create upload directory and ensure it is writable
        if(!is_dir($uploadDir)){
            mkdir($uploadDir, 0777, true);
        }
        if(!is_writable($uploadDir)){
            @chmod($uploadDir, 0777);
        }

        //Generating unique filename
        $fileName = uniqid().'_'.time(). '.'.$fileExtension;
        $filePath = $uploadDir. $fileName;

        //Move the file to the upload directory
        if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
            return ['success' => false, 'error' => 'Invalid upload source. tmp_name is missing or not an uploaded file', 'details' => [
                'tmp_name' => isset($file['tmp_name']) ? $file['tmp_name'] : 'NONE',
                'exists' => (isset($file['tmp_name']) && file_exists($file['tmp_name'])) ? 'yes' : 'no'
            ]];
        }

        if(move_uploaded_file($file['tmp_name'], $filePath)){
            error_log('[UploadHelper] Move succeeded: ' . $filePath);

            // Mirror copy to alternate project roots (dev and XAMPP) to keep both in sync
            $devProjectRoot = 'C:\\users\\soumalya\\EduPlatform';
            $xamppProjectRoot = 'C:\\xampp\\htdocs\\EduPlatform';
            $roots = [];
            if (is_dir($devProjectRoot)) $roots[] = $devProjectRoot;
            if (is_dir($xamppProjectRoot)) $roots[] = $xamppProjectRoot;

            // Compute relative path from runtime root to file
            $relFromRuntime = ltrim(str_replace(['\\','/'], '/', str_replace($projectRootPath, '', $filePath)), '/');

            foreach ($roots as $root) {
                if (stripos($projectRootPath, $root) === 0) {
                    // this is the runtime root, already wrote here
                    continue;
                }
                $target = rtrim($root, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $relFromRuntime);
                $targetDir = dirname($target);
                if (!is_dir($targetDir)) {
                    @mkdir($targetDir, 0777, true);
                }
                @copy($filePath, $target);
                error_log('[UploadHelper] Mirrored to: ' . $target . ' (exists: ' . (file_exists($target) ? 'yes' : 'no') . ')');
            }

            //returning relative path for DB storage
            $relativePath = str_replace(DIRECTORY_SEPARATOR, '/', str_replace($projectRootPath, '', $filePath));
            $relativePath = ltrim($relativePath, '/');
            return ['success' => true, 'path' => $relativePath]; 
        }else{
            $lastError = error_get_last();
            return ['success' => false, 'error' => 'Failed to move the uploaded file to the upload directory', 'details' => [
                'destination' => $filePath,
                'upload_dir_writable' => is_writable($uploadDir) ? 'yes' : 'no',
                'file_exists' => (isset($file['tmp_name']) && file_exists($file['tmp_name'])) ? 'yes' : 'no',
                'last_error' => $lastError ? $lastError['message'] : null
            ]];
        }
    }
    
    public static function deleteFile($filePath){
        $devProjectRoot = 'C:\\users\\soumalya\\EduPlatform';
        $xamppProjectRoot = 'C:\\xampp\\htdocs\\EduPlatform';
        $normalizedRel = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, ltrim($filePath, '/\\'));

        $deleted = false;
        $targets = [];
        if (is_dir($devProjectRoot)) $targets[] = rtrim($devProjectRoot, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $normalizedRel;
        if (is_dir($xamppProjectRoot)) $targets[] = rtrim($xamppProjectRoot, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $normalizedRel;

        foreach ($targets as $path) {
            if (file_exists($path)) {
                @unlink($path);
                $deleted = true;
            }
        }
        return $deleted;
    }
    
}
?>