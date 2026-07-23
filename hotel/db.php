<?php
/**
 * Hotel Manager Panel — Database & Auth
 * Uses the main users table with role-based access
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../db.php';

// Ensure role column has hotel_manager option
$col_check = mysqli_query($conn, "SHOW COLUMNS FROM users LIKE 'role'");
if ($col_check && mysqli_num_rows($col_check) > 0) {
    $row = mysqli_fetch_assoc($col_check);
    if (strpos($row['Type'], 'hotel_manager') === false) {
        mysqli_query($conn, "ALTER TABLE users MODIFY COLUMN role ENUM('user','admin','hotel_manager') DEFAULT 'user'");
    }
}

// Load main hotel tables if not already loaded by main db.php
function ensure_hotel_tables() {
    global $conn;
    
    $sql = "CREATE TABLE IF NOT EXISTS `hotels` (
      `hotel_id`            INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
      `hotel_name`          VARCHAR(255) NOT NULL,
      `city`                VARCHAR(100) NOT NULL,
      `location`            VARCHAR(255) NOT NULL,
      `state`               VARCHAR(100) DEFAULT NULL,
      `description`         TEXT DEFAULT NULL,
      `price_per_night`     DECIMAL(10,2) NOT NULL DEFAULT 0.00,
      `original_price`      DECIMAL(10,2) DEFAULT NULL,
      `discount_percentage` DECIMAL(5,2) DEFAULT 0.00,
      `gst_percentage`      DECIMAL(5,2) DEFAULT 12.00,
      `rating`              DECIMAL(3,1) DEFAULT 0.0,
      `star_rating`         TINYINT(1) DEFAULT 3,
      `property_type`       VARCHAR(50) DEFAULT 'hotel',
      `amenities`           TEXT DEFAULT NULL,
      `capacity`            TINYINT(3) DEFAULT 2,
      `availability_status` ENUM('active','inactive','maintenance') DEFAULT 'active',
      `hotel_images`        TEXT DEFAULT NULL,
      `featured`            TINYINT(1) DEFAULT 0,
      `checkin_time`        VARCHAR(10) DEFAULT '14:00',
      `checkout_time`       VARCHAR(10) DEFAULT '11:00',
      `phone`               VARCHAR(30) DEFAULT NULL,
      `email`               VARCHAR(255) DEFAULT NULL,
      `assigned_to`         INT(11) UNSIGNED DEFAULT NULL,
      `created_at`          TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
      `updated_at`          TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
      INDEX `idx_city`      (`city`),
      INDEX `idx_status`    (`availability_status`),
      INDEX `idx_rating`    (`rating`),
      INDEX `idx_price`     (`price_per_night`),
      INDEX `idx_featured`  (`featured`),
      INDEX `idx_assigned`  (`assigned_to`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    mysqli_query($conn, $sql);
    
    $sql = "CREATE TABLE IF NOT EXISTS `rooms` (
      `room_id`          INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
      `hotel_id`         INT(11) UNSIGNED NOT NULL,
      `manager_id`       INT(11) UNSIGNED NOT NULL,
      `room_number`      VARCHAR(20) NOT NULL,
      `room_type`        VARCHAR(100) NOT NULL,
      `room_name`        VARCHAR(150) DEFAULT NULL,
      `floor`            VARCHAR(50) DEFAULT NULL,
      `adult_capacity`   TINYINT(3) DEFAULT 2,
      `child_capacity`   TINYINT(3) DEFAULT 0,
      `bed_type`         VARCHAR(100) DEFAULT NULL,
      `base_price`       DECIMAL(10,2) NOT NULL DEFAULT 0.00,
      `discount_percent` DECIMAL(5,2) DEFAULT 0.00,
      `final_price`      DECIMAL(10,2) NOT NULL DEFAULT 0.00,
      `description`      TEXT DEFAULT NULL,
      `amenities`        TEXT DEFAULT NULL,
      `room_images`      TEXT DEFAULT NULL,
      `status`           ENUM('Available','Occupied','Maintenance') DEFAULT 'Available',
      `created_at`       TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
      `updated_at`       TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
      INDEX `idx_hotel`  (`hotel_id`),
      INDEX `idx_manager` (`manager_id`),
      INDEX `idx_status` (`status`),
      INDEX `idx_number` (`room_number`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    mysqli_query($conn, $sql);
    
    $col_check = mysqli_query($conn, "SHOW COLUMNS FROM rooms LIKE 'manager_id'");
    if ($col_check && mysqli_num_rows($col_check) === 0) {
        mysqli_query($conn, "ALTER TABLE `rooms` ADD COLUMN `manager_id` INT(11) UNSIGNED NOT NULL AFTER `hotel_id`");
    }
    $col_check = mysqli_query($conn, "SHOW COLUMNS FROM rooms LIKE 'room_name'");
    if ($col_check && mysqli_num_rows($col_check) === 0) {
        mysqli_query($conn, "ALTER TABLE `rooms` ADD COLUMN `room_name` VARCHAR(150) DEFAULT NULL AFTER `room_type`");
    }
    
    $sql = "CREATE TABLE IF NOT EXISTS `bookings` (
      `booking_id`       VARCHAR(20) PRIMARY KEY,
      `user_id`          INT(11) UNSIGNED DEFAULT NULL,
      `hotel_id`         INT(11) UNSIGNED DEFAULT NULL,
      `hotel_name`       VARCHAR(255) NOT NULL,
      `hotel_city`       VARCHAR(100) DEFAULT NULL,
      `room_type`        VARCHAR(100) DEFAULT 'Standard Room',
      `guest_name`       VARCHAR(255) NOT NULL,
      `guest_email`      VARCHAR(255) NOT NULL,
      `guest_phone`      VARCHAR(30) DEFAULT NULL,
      `checkin_date`     DATE NOT NULL,
      `checkout_date`    DATE NOT NULL,
      `nights`           TINYINT(3) DEFAULT 1,
      `guests`           TINYINT(3) DEFAULT 2,
      `base_amount`      DECIMAL(10,2) DEFAULT 0.00,
      `discount_amount`  DECIMAL(10,2) DEFAULT 0.00,
      `tax_amount`       DECIMAL(10,2) DEFAULT 0.00,
      `service_charge`   DECIMAL(10,2) DEFAULT 200.00,
      `coupon_discount`  DECIMAL(10,2) DEFAULT 0.00,
      `total_amount`     DECIMAL(10,2) NOT NULL,
      `payment_method`   VARCHAR(50) DEFAULT 'UPI',
      `payment_status`   ENUM('pending','paid','failed','refunded') DEFAULT 'pending',
      `booking_status`   ENUM('pending','confirmed','checked_in','checked_out','cancelled') DEFAULT 'confirmed',
      `special_requests` TEXT DEFAULT NULL,
      `arrival_time`     VARCHAR(30) DEFAULT NULL,
      `created_at`       TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
      `updated_at`       TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
      INDEX `idx_user`   (`user_id`),
      INDEX `idx_hotel`  (`hotel_id`),
      INDEX `idx_status` (`booking_status`),
      INDEX `idx_checkin`(`checkin_date`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    mysqli_query($conn, $sql);
    
    $sql = "CREATE TABLE IF NOT EXISTS `reviews` (
      `review_id`        INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
      `hotel_id`         INT(11) UNSIGNED NOT NULL,
      `user_id`          INT(11) UNSIGNED DEFAULT NULL,
      `guest_name`       VARCHAR(255) NOT NULL,
      `rating`           DECIMAL(2,1) NOT NULL,
      `comment`          TEXT DEFAULT NULL,
      `manager_reply`    TEXT DEFAULT NULL,
      `created_at`       TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
      `updated_at`       TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
      INDEX `idx_hotel`  (`hotel_id`),
      INDEX `idx_user`   (`user_id`),
      INDEX `idx_rating` (`rating`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    mysqli_query($conn, $sql);

    $sql = "CREATE TABLE IF NOT EXISTS `notifications` (
      `id`         INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
      `user_id`    INT(11) UNSIGNED NOT NULL,
      `type`       VARCHAR(100) NOT NULL,
      `title`      VARCHAR(255) NOT NULL,
      `message`    TEXT DEFAULT NULL,
      `is_read`    TINYINT(1) DEFAULT 0,
      `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
      `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
      INDEX `idx_user` (`user_id`,`is_read`,`created_at`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    mysqli_query($conn, $sql);

    $sql = "CREATE TABLE IF NOT EXISTS `notification_settings` (
      `id`           INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
      `user_id`      INT(11) UNSIGNED NOT NULL,
      `booking_new`  TINYINT(1) DEFAULT 1,
      `booking_cancel` TINYINT(1) DEFAULT 1,
      `room_update`  TINYINT(1) DEFAULT 1,
      `hotel_approval` TINYINT(1) DEFAULT 1,
      `created_at`   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
      `updated_at`   TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
      UNIQUE KEY `uniq_user` (`user_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    mysqli_query($conn, $sql);
}

ensure_hotel_tables();

function hm_sanitize($data) {
    global $conn;
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $data = mysqli_real_escape_string($conn, $data);
    return $data;
}

function hm_validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function hm_validateMobile($mobile) {
    $mobile = preg_replace('/[^0-9]/', '', $mobile);
    return preg_match('/^[6-9][0-9]{9}$/', $mobile);
}

function hm_getUserIP() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        return $_SERVER['REMOTE_ADDR'];
    }
}

function hm_checkLoginAttempts($email, $max_attempts = 5, $lockout_time = 900) {
    return true;
}

function hm_logLoginAttempt($email, $success = false) {
    // Logging disabled to prevent login_attempts table issues
}

function initializeLoginAttemptsTable() {
    global $conn;
    $sql = "CREATE TABLE IF NOT EXISTS `login_attempts` (
        id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        email VARCHAR(255) NOT NULL,
        ip_address VARCHAR(45) NOT NULL,
        attempted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        success TINYINT(1) DEFAULT 0,
        INDEX idx_email (email),
        INDEX idx_ip (ip_address)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    mysqli_query($conn, $sql);
}

initializeLoginAttemptsTable();

?>
