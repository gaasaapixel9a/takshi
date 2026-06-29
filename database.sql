-- ============================================================
-- THAKSHI PHOTOGRAPHY — Full Database Schema
-- Compatible with MySQL 5.7+ (Hostinger Basic)
-- ============================================================

CREATE DATABASE IF NOT EXISTS thakshi_photography CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE thakshi_photography;

-- ============================================================
-- SERVICES
-- ============================================================
CREATE TABLE services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    slug VARCHAR(100) NOT NULL UNIQUE,
    name VARCHAR(100) NOT NULL,
    hero_image VARCHAR(255),
    display_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO services (slug, name, display_order) VALUES
('wedding', 'Wedding', 1),
('newborn', 'New Born', 2),
('model-shoot', 'Model Shoot', 3),
('maternity', 'Maternity', 4),
('corporate', 'Corporate', 5),
('couple-portraits', 'Couple Portraits', 6);

-- ============================================================
-- SUBCATEGORIES (per service, managed by admin)
-- ============================================================
CREATE TABLE subcategories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    service_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    display_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE CASCADE
);

-- ============================================================
-- GALLERY IMAGES
-- ============================================================
CREATE TABLE gallery_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    service_id INT NOT NULL,
    subcategory_id INT DEFAULT NULL,
    filename VARCHAR(255) NOT NULL,
    filepath VARCHAR(500) NOT NULL,
    filesize INT,
    width INT,
    height INT,
    display_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE CASCADE,
    FOREIGN KEY (subcategory_id) REFERENCES subcategories(id) ON DELETE SET NULL
);

-- ============================================================
-- USERS (identified by phone number)
-- ============================================================
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    phone VARCHAR(20) NOT NULL UNIQUE,
    total_visits INT DEFAULT 0,
    total_request_count INT DEFAULT 0,
    first_seen TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_seen TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ============================================================
-- ACCESS REQUESTS (per user, per service)
-- ============================================================
CREATE TABLE access_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    service_id INT NOT NULL,
    status ENUM('pending','approved','rejected','expired') DEFAULT 'pending',
    request_count INT DEFAULT 1,
    approved_at TIMESTAMP NULL,
    expires_at TIMESTAMP NULL,
    admin_note VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE CASCADE
);

-- ============================================================
-- VISIT TRACKING
-- ============================================================
CREATE TABLE visit_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    service_id INT DEFAULT NULL,
    page VARCHAR(255),
    ip_address VARCHAR(45),
    user_agent TEXT,
    time_spent_seconds INT DEFAULT 0,
    visited_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ============================================================
-- ADMIN NOTIFICATIONS
-- ============================================================
CREATE TABLE admin_notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    type ENUM('new_request','re_request') DEFAULT 'new_request',
    user_id INT,
    request_id INT,
    message VARCHAR(255),
    is_read TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (request_id) REFERENCES access_requests(id) ON DELETE SET NULL
);

-- ============================================================
-- ADMIN USERS
-- ============================================================
CREATE TABLE admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    push_subscription TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Default admin: username=admin, password=Admin@1234 (CHANGE THIS!)
INSERT INTO admin_users (username, password_hash)
VALUES ('admin', '$2y$12$XbK9E5e7GkZ1V8P2qN3mMuWsL4tH6nRjDcOoIYvFpBxQAzKhUwSdG');

-- ============================================================
-- INDEXES for performance
-- ============================================================
CREATE INDEX idx_requests_user ON access_requests(user_id);
CREATE INDEX idx_requests_service ON access_requests(service_id);
CREATE INDEX idx_requests_status ON access_requests(status);
CREATE INDEX idx_gallery_service ON gallery_images(service_id);
CREATE INDEX idx_visits_user ON visit_logs(user_id);
CREATE INDEX idx_notif_read ON admin_notifications(is_read);
