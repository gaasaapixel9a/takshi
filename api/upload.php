<?php
// api/upload.php — Admin image upload handler
// POST multipart/form-data: service_id, subcat_id (optional), images[]

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/functions.php';

// Must be admin
requireAdmin();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['error' => 'POST required'], 405);
}

$serviceId = isset($_POST['service_id']) ? (int)$_POST['service_id'] : 0;
$subcatId  = isset($_POST['subcat_id'])  ? (int)$_POST['subcat_id']  : null;

if (!$serviceId) {
    jsonResponse(['error' => 'service_id required'], 400);
}

// Get service slug for folder
try {
    $db = getDB();
    $stmt = $db->prepare("SELECT slug FROM services WHERE id=? LIMIT 1");
    $stmt->execute([$serviceId]);
    $svc = $stmt->fetch();
} catch (Exception $e) {
    jsonResponse(['error' => 'DB error'], 500);
}

if (!$svc) jsonResponse(['error' => 'Service not found'], 404);

$uploadDir = UPLOAD_DIR . $svc['slug'] . '/';
if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

if (empty($_FILES['images'])) {
    jsonResponse(['error' => 'No files uploaded'], 400);
}

$allowed = ['image/jpeg', 'image/png', 'image/webp'];
$maxSize = 10 * 1024 * 1024; // 10MB

$uploaded = [];
$errors   = [];

// Normalize $_FILES array for multiple uploads
$files = $_FILES['images'];
$count = is_array($files['name']) ? count($files['name']) : 1;

for ($i = 0; $i < $count; $i++) {
    $name  = is_array($files['name'])     ? $files['name'][$i]     : $files['name'];
    $type  = is_array($files['type'])     ? $files['type'][$i]     : $files['type'];
    $tmp   = is_array($files['tmp_name']) ? $files['tmp_name'][$i] : $files['tmp_name'];
    $error = is_array($files['error'])    ? $files['error'][$i]    : $files['error'];
    $size  = is_array($files['size'])     ? $files['size'][$i]     : $files['size'];

    if ($error !== UPLOAD_ERR_OK) {
        $errors[] = "Upload error for $name";
        continue;
    }
    if ($size > $maxSize) {
        $errors[] = "$name exceeds 10MB limit";
        continue;
    }

    // Verify MIME via GD
    $imgInfo = @getimagesize($tmp);
    if (!$imgInfo || !in_array($imgInfo['mime'], $allowed)) {
        $errors[] = "$name is not a valid image";
        continue;
    }

    $ext      = match($imgInfo['mime']) {
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp',
        default      => 'jpg'
    };

    $newName  = uniqid('img_', true) . '.' . $ext;
    $destPath = $uploadDir . $newName;
    $relPath  = $svc['slug'] . '/' . $newName;

    if (!move_uploaded_file($tmp, $destPath)) {
        $errors[] = "Failed to save $name";
        continue;
    }

    // Get next display_order
    try {
        $db = getDB();
        $orderRow = $db->prepare("SELECT COALESCE(MAX(display_order),0)+1 AS next_order FROM gallery_images WHERE service_id=?");
        $orderRow->execute([$serviceId]);
        $nextOrder = (int)$orderRow->fetchColumn();

        $db->prepare(
            "INSERT INTO gallery_images (service_id, subcategory_id, filename, filepath, filesize, width, height, display_order)
             VALUES (?,?,?,?,?,?,?,?)"
        )->execute([
            $serviceId,
            $subcatId ?: null,
            $newName,
            $relPath,
            $size,
            $imgInfo[0],
            $imgInfo[1],
            $nextOrder
        ]);

        $uploaded[] = [
            'id'  => (int)$db->lastInsertId(),
            'url' => UPLOAD_URL . $relPath,
        ];
    } catch (Exception $e) {
        @unlink($destPath);
        $errors[] = "DB insert failed for $name";
    }
}

jsonResponse([
    'uploaded' => $uploaded,
    'errors'   => $errors,
    'count'    => count($uploaded),
]);
