<?php
/**
 * Database Configuration and Connection
 * bookHotel Hotel Booking System
 */

// Database credentials
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'Aditi@1521');
define('DB_NAME', 'bookhotel_db');
define('DB_PORT', 3307);

// Create connection without selecting database first
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, null, DB_PORT);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Create database if not exists
$sql = "CREATE DATABASE IF NOT EXISTS " . DB_NAME . " CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
if (!mysqli_query($conn, $sql)) {
    die("Error creating database: " . mysqli_error($conn));
}

// Now select the database
if (!mysqli_select_db($conn, DB_NAME)) {
    die("Error selecting database: " . mysqli_error($conn));
}

// Set charset to utf8mb4 for full Unicode support
mysqli_set_charset($conn, "utf8mb4");

/**
 * Function to create tables if they don't exist
 */
function initializeDatabase() {
    global $conn;
    
    // Create users table
    $create_users_table = "CREATE TABLE IF NOT EXISTS users (
        id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        first_name VARCHAR(100) NOT NULL,
        last_name VARCHAR(100) NOT NULL,
        email VARCHAR(255) NOT NULL UNIQUE,
        mobile VARCHAR(20) NOT NULL,
        password VARCHAR(255) NOT NULL,
        profile_image VARCHAR(255) DEFAULT NULL,
        status ENUM('active', 'inactive', 'suspended') DEFAULT 'active',
        role ENUM('user', 'admin', 'hotel_manager') DEFAULT 'user',
        email_verified TINYINT(1) DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        last_login TIMESTAMP NULL DEFAULT NULL,
        INDEX idx_email (email),
        INDEX idx_mobile (mobile),
        INDEX idx_status (status)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    
    if (!mysqli_query($conn, $create_users_table)) {
        die("Error creating users table: " . mysqli_error($conn));
    }
    
    // Add role to users if missing
    $check_role = mysqli_query($conn, "SHOW COLUMNS FROM users LIKE 'role'");
    if ($check_role && mysqli_num_rows($check_role) === 0) {
        mysqli_query($conn, "ALTER TABLE users ADD COLUMN `role` ENUM('customer','hotel_manager','admin') DEFAULT 'customer' AFTER `status`");
    }
    
    // Create password_resets table
    $create_resets_table = "CREATE TABLE IF NOT EXISTS password_resets (
        id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        email VARCHAR(255) NOT NULL,
        token VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        expires_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        used TINYINT(1) DEFAULT 0,
        INDEX idx_email (email),
        INDEX idx_token (token)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    
    if (!mysqli_query($conn, $create_resets_table)) {
        die("Error creating password_resets table: " . mysqli_error($conn));
    }
    
    // Create login_attempts table for security
    $create_attempts_table = "CREATE TABLE IF NOT EXISTS login_attempts (
        id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        email VARCHAR(255) NOT NULL,
        ip_address VARCHAR(45) NOT NULL,
        attempted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        success TINYINT(1) DEFAULT 0,
        INDEX idx_email (email),
        INDEX idx_ip (ip_address)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    
    if (!mysqli_query($conn, $create_attempts_table)) {
        die("Error creating login_attempts table: " . mysqli_error($conn));
    }
    
    // Create contact_submissions table
    $create_contact_table = "CREATE TABLE IF NOT EXISTS contact_submissions (
        id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL,
        subject VARCHAR(255) NOT NULL,
        message TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    
    if (!mysqli_query($conn, $create_contact_table)) {
        die("Error creating contact_submissions table: " . mysqli_error($conn));
    }
    
    return true;
}

/**
 * Function to sanitize user input
 */
function sanitize($data) {
    global $conn;
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $data = mysqli_real_escape_string($conn, $data);
    return $data;
}

/**
 * Function to validate email
 */
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

/**
 * Function to validate mobile number (Indian format)
 */
function validateMobile($mobile) {
    // Remove any non-digit characters
    $mobile = preg_replace('/[^0-9]/', '', $mobile);
    
    // Check if it's a 10-digit number
    if (preg_match('/^[6-9][0-9]{9}$/', $mobile)) {
        return true;
    }
    return false;
}

/**
 * Function to check if email exists
 */
function emailExists($email) {
    global $conn;
    $email = sanitize($email);
    $sql = "SELECT id FROM users WHERE email = '$email' LIMIT 1";
    $result = mysqli_query($conn, $sql);
    return mysqli_num_rows($result) > 0;
}

/**
 * Function to check if mobile exists
 */
function mobileExists($mobile) {
    global $conn;
    $mobile = sanitize($mobile);
    $sql = "SELECT id FROM users WHERE mobile = '$mobile' LIMIT 1";
    $result = mysqli_query($conn, $sql);
    return mysqli_num_rows($result) > 0;
}

/**
 * Function to get user IP address
 */
function getUserIP() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        return $_SERVER['REMOTE_ADDR'];
    }
}

/**
 * Function to check login attempts (prevent brute force)
 */
function checkLoginAttempts($email, $max_attempts = 5, $lockout_time = 900) {
    global $conn;
    $email = sanitize($email);
    $ip = getUserIP();
    
    // Check attempts in last lockout_time seconds (default 15 minutes)
    $time_threshold = date('Y-m-d H:i:s', time() - $lockout_time);
    
    $sql = "SELECT COUNT(*) as attempts FROM login_attempts 
            WHERE (email = '$email' OR ip_address = '$ip') 
            AND attempted_at > '$time_threshold' 
            AND success = 0";
    
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    
    return $row['attempts'] < $max_attempts;
}

/**
 * Function to log login attempt
 */
function logLoginAttempt($email, $success = false) {
    global $conn;
    $email = sanitize($email);
    $ip = getUserIP();
    $success_int = $success ? 1 : 0;
    
    $sql = "INSERT INTO login_attempts (email, ip_address, success) 
            VALUES ('$email', '$ip', $success_int)";
    
    mysqli_query($conn, $sql);
}

/**
 * Function to clean old login attempts (run periodically)
 */
function cleanOldLoginAttempts($days = 30) {
    global $conn;
    $threshold = date('Y-m-d H:i:s', time() - ($days * 24 * 60 * 60));
    $sql = "DELETE FROM login_attempts WHERE attempted_at < '$threshold'";
    mysqli_query($conn, $sql);
}

/**
 * Auto-create hotels table if it doesn't exist
 */
function initializeHotelsTable() {
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
}

// Initialize database on first run
initializeDatabase();
initializeHotelsTable();
initializeBookingsTable();
initializeRoomsTable();
initializeReviewsTable();
initializeCitiesTable();
initializeCouponsTable();
initializeCommissionsTable();

/**
 * Auto-create cities table
 */
function initializeCitiesTable() {
    global $conn;
    $sql = "CREATE TABLE IF NOT EXISTS `cities` (
      `id`               INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
      `name`             VARCHAR(100) NOT NULL UNIQUE,
      `status`           ENUM('active','inactive') DEFAULT 'active',
      `created_at`       TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
      `updated_at`       TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
      INDEX `idx_status` (`status`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    mysqli_query($conn, $sql);
}

/**
 * Auto-create coupons table
 */
function initializeCouponsTable() {
    global $conn;
    $sql = "CREATE TABLE IF NOT EXISTS `coupons` (
      `id`               INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
      `code`             VARCHAR(50) NOT NULL UNIQUE,
      `discount_type`    ENUM('percent','fixed') NOT NULL DEFAULT 'percent',
      `discount_value`   DECIMAL(10,2) NOT NULL,
      `expiry_date`      DATE NOT NULL,
      `usage_limit`      INT(11) DEFAULT NULL,
      `times_used`       INT(11) DEFAULT 0,
      `status`           ENUM('active','inactive') DEFAULT 'active',
      `created_at`       TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
      `updated_at`       TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
      INDEX `idx_code`   (`code`),
      INDEX `idx_status` (`status`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    mysqli_query($conn, $sql);
}

/**
 * Auto-create commissions table
 */
function initializeCommissionsTable() {
    global $conn;
    $sql = "CREATE TABLE IF NOT EXISTS `commissions` (
      `hotel_id`         INT(11) UNSIGNED PRIMARY KEY,
      `commission_rate`  DECIMAL(5,2) NOT NULL DEFAULT 15.00,
      `updated_at`       TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    mysqli_query($conn, $sql);
}

/**
 * Auto-create rooms table
 */
function initializeRoomsTable() {
    global $conn;
    $sql = "CREATE TABLE IF NOT EXISTS `rooms` (
      `room_id`          INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
      `hotel_id`         INT(11) UNSIGNED NOT NULL,
      `room_type`        VARCHAR(100) NOT NULL,
      `base_price`       DECIMAL(10,2) NOT NULL DEFAULT 0.00,
      `capacity`         TINYINT(3) DEFAULT 2,
      `amenities`        TEXT DEFAULT NULL,
      `status`           ENUM('Available','Occupied','Maintenance') DEFAULT 'Available',
      `created_at`       TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
      `updated_at`       TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
      INDEX `idx_hotel`  (`hotel_id`),
      INDEX `idx_status` (`status`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    mysqli_query($conn, $sql);
}

/**
 * Auto-create reviews table
 */
function initializeReviewsTable() {
    global $conn;
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
}

/**
 * Auto-create bookings table
 */
function initializeBookingsTable() {
    global $conn;
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

    // Add approval_status to hotels if missing
    $check = mysqli_query($conn, "SHOW COLUMNS FROM hotels LIKE 'approval_status'");
    if ($check && mysqli_num_rows($check) === 0) {
        mysqli_query($conn, "ALTER TABLE hotels ADD COLUMN `approval_status` ENUM('pending','approved','rejected') DEFAULT 'approved' AFTER `availability_status`");
        // Mark existing hotels as approved
        mysqli_query($conn, "UPDATE hotels SET approval_status='approved' WHERE approval_status IS NULL OR approval_status=''");
    }

    // Add assigned_to to hotels if missing
    $checkAssigned = mysqli_query($conn, "SHOW COLUMNS FROM hotels LIKE 'assigned_to'");
    if ($checkAssigned && mysqli_num_rows($checkAssigned) === 0) {
        mysqli_query($conn, "ALTER TABLE hotels ADD COLUMN `assigned_to` INT(11) UNSIGNED DEFAULT NULL AFTER `approval_status`");
        mysqli_query($conn, "ALTER TABLE hotels ADD INDEX `idx_assigned` (`assigned_to`)");
    }

    // Fix property_type ENUM truncation issues by altering it to VARCHAR
    mysqli_query($conn, "ALTER TABLE hotels MODIFY COLUMN `property_type` VARCHAR(50) DEFAULT 'hotel'");

    // Add role to users if missing, and extend ENUM to include hotel_manager
    $checkRole = mysqli_query($conn, "SHOW COLUMNS FROM users LIKE 'role'");
    if ($checkRole && mysqli_num_rows($checkRole) === 0) {
        mysqli_query($conn, "ALTER TABLE users ADD COLUMN `role` ENUM('user', 'admin', 'hotel_manager') DEFAULT 'user' AFTER `status`");
    } else {
        mysqli_query($conn, "ALTER TABLE users MODIFY COLUMN `role` ENUM('user', 'admin', 'hotel_manager') DEFAULT 'user'");
    }

    // Insert default admin if it doesn't exist
    $checkAdmin = mysqli_query($conn, "SELECT id FROM users WHERE email = 'admin@bookhotel.com'");
    if ($checkAdmin && mysqli_num_rows($checkAdmin) === 0) {
        $admin_pw = password_hash('admin', PASSWORD_DEFAULT);
        mysqli_query($conn, "INSERT INTO users (first_name, last_name, email, mobile, password, role, status) VALUES ('Main', 'Admin', 'admin@bookhotel.com', '0000000000', '$admin_pw', 'admin', 'active')");
    }
}

?>
