<?php
// api/gallery.php — Paginated gallery images
// GET /api/gallery.php?service_id=1&offset=0&limit=9&subcat_id=2

header('Content-Type: application/json');
header('Cache-Control: no-store');
header('X-Content-Type-Options: nosniff');

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/functions.php';

$serviceId = isset($_GET['service_id']) ? (int)$_GET['service_id'] : 0;
$offset    = isset($_GET['offset'])     ? max(0, (int)$_GET['offset']) : 0;
$limit     = isset($_GET['limit'])      ? min(20, max(1, (int)$_GET['limit'])) : 9;
$subcatId  = isset($_GET['subcat_id']) && (int)$_GET['subcat_id'] > 0 ? (int)$_GET['subcat_id'] : null;

if (!$serviceId) {
    echo json_encode(['error' => 'service_id required', 'images' => []]);
    exit;
}

try {
    $rows = getGalleryImages($serviceId, $offset, $limit, $subcatId);

    $images = array_map(function ($row) {
        $rel = ltrim($row['filepath'], '/');
        $rel = preg_replace('#^uploads/#', '', $rel);
        return [
            'id'       => (int)$row['id'],
            'url'      => UPLOAD_URL . $rel,
            'filename' => $row['filename'],
            'width'    => (int)$row['width'],
            'height'   => (int)$row['height'],
        ];
    }, $rows);

    echo json_encode(['images' => $images, 'total' => count($images)]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server error', 'images' => []]);
}
