<?php
// functions.php — Shared helper functions
// ============================================================

require_once __DIR__ . '/config.php';

/**
 * Get all active services
 */
function getServices(): array {
    try {
        $db = getDB();
        return $db->query("SELECT * FROM services WHERE is_active=1 ORDER BY display_order")->fetchAll();
    } catch (Exception $e) { return []; }
}

/**
 * Get service by slug
 */
function getServiceBySlug(string $slug): ?array {
    try {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM services WHERE slug=? AND is_active=1 LIMIT 1");
        $stmt->execute([$slug]);
        return $stmt->fetch() ?: null;
    } catch (Exception $e) { return null; }
}

/**
 * Get subcategories for a service
 */
function getSubcategories(int $serviceId): array {
    try {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM subcategories WHERE service_id=? AND is_active=1 ORDER BY display_order");
        $stmt->execute([$serviceId]);
        return $stmt->fetchAll();
    } catch (Exception $e) { return []; }
}

/**
 * Get gallery images with pagination
 */
function getGalleryImages(int $serviceId, int $offset = 0, int $limit = 9, ?int $subcatId = null): array {
    try {
        $db = getDB();
        $params = [$serviceId];
        $sql = "SELECT * FROM gallery_images WHERE service_id=? AND is_active=1";
        if ($subcatId) { $sql .= " AND subcategory_id=?"; $params[] = $subcatId; }
        $sql .= " ORDER BY display_order ASC, id ASC LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    } catch (Exception $e) { return []; }
}

/**
 * Get or create user by phone
 */
function getOrCreateUser(string $name, string $phone): ?int {
    try {
        $db = getDB();
        $stmt = $db->prepare("SELECT id FROM users WHERE phone=? LIMIT 1");
        $stmt->execute([$phone]);
        $user = $stmt->fetch();
        if ($user) {
            $db->prepare("UPDATE users SET name=?, last_seen=NOW() WHERE id=?")->execute([$name, $user['id']]);
            return $user['id'];
        }
        $db->prepare("INSERT INTO users (name,phone) VALUES (?,?)")->execute([$name, $phone]);
        return (int)$db->lastInsertId();
    } catch (Exception $e) { return null; }
}

/**
 * Get active access request for user+service
 */
function getAccessRequest(int $userId, int $serviceId): ?array {
    try {
        $db = getDB();
        $stmt = $db->prepare(
            "SELECT * FROM access_requests WHERE user_id=? AND service_id=?
             ORDER BY created_at DESC LIMIT 1"
        );
        $stmt->execute([$userId, $serviceId]);
        return $stmt->fetch() ?: null;
    } catch (Exception $e) { return null; }
}

/**
 * Check if user has valid approved access
 */
function hasValidAccess(int $userId, int $serviceId): bool {
    $req = getAccessRequest($userId, $serviceId);
    if (!$req) return false;
    if ($req['status'] !== 'approved') return false;
    if ($req['expires_at'] && strtotime($req['expires_at']) < time()) {
        // Expire it
        try {
            getDB()->prepare("UPDATE access_requests SET status='expired' WHERE id=?")->execute([$req['id']]);
        } catch (Exception $e) {}
        return false;
    }
    return true;
}

/**
 * Create or re-request access
 */
function requestAccess(int $userId, int $serviceId): array {
    try {
        $db = getDB();
        $existing = getAccessRequest($userId, $serviceId);
        if ($existing) {
            $db->prepare(
                "UPDATE access_requests SET status='pending', request_count=request_count+1, updated_at=NOW()
                 WHERE id=?"
            )->execute([$existing['id']]);
            $reqId = $existing['id'];
        } else {
            $db->prepare(
                "INSERT INTO access_requests (user_id, service_id) VALUES (?,?)"
            )->execute([$userId, $serviceId]);
            $reqId = (int)$db->lastInsertId();
        }
        $db->prepare("UPDATE users SET total_request_count=total_request_count+1 WHERE id=?")->execute([$userId]);
        // Create admin notification
        $db->prepare(
            "INSERT INTO admin_notifications (type,user_id,request_id,message)
             VALUES ('new_request',?,?, CONCAT((SELECT name FROM users WHERE id=?), ' is requesting gallery access'))"
        )->execute([$userId, $reqId, $userId]);
        return ['success' => true, 'request_id' => $reqId];
    } catch (Exception $e) {
        return ['success' => false, 'error' => $e->getMessage()];
    }
}

/**
 * Log visit
 */
function logVisit(int $userId, ?int $serviceId = null, string $page = ''): void {
    try {
        $db = getDB();
        $db->prepare(
            "INSERT INTO visit_logs (user_id,service_id,page,ip_address,user_agent)
             VALUES (?,?,?,?,?)"
        )->execute([
            $userId, $serviceId, $page,
            $_SERVER['REMOTE_ADDR'] ?? '',
            $_SERVER['HTTP_USER_AGENT'] ?? ''
        ]);
        $db->prepare("UPDATE users SET total_visits=total_visits+1, last_seen=NOW() WHERE id=?")->execute([$userId]);
    } catch (Exception $e) {}
}

/**
 * JSON response helper
 */
function jsonResponse(array $data, int $code = 200): void {
    http_response_code($code);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

/**
 * Sanitize string
 */
function clean(string $str): string {
    return htmlspecialchars(trim($str), ENT_QUOTES, 'UTF-8');
}

/**
 * Admin auth check
 */
function requireAdmin(): void {
    if (empty($_SESSION['admin_id'])) {
        header('Location: /admin/login.php');
        exit;
    }
}
