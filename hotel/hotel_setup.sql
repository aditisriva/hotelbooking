-- ================================================================
-- BookHotel — Hotel Table Setup & Initial Data
-- Run this in phpMyAdmin or MySQL CLI
-- ================================================================

USE bookhotel_db;

-- Drop and recreate hotels table
CREATE TABLE IF NOT EXISTS `hotels` (
  `hotel_id`           INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `hotel_name`         VARCHAR(255) NOT NULL,
  `city`               VARCHAR(100) NOT NULL,
  `location`           VARCHAR(255) NOT NULL,
  `state`              VARCHAR(100) DEFAULT NULL,
  `description`        TEXT DEFAULT NULL,
  `price_per_night`    DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `original_price`     DECIMAL(10,2) DEFAULT NULL,
  `discount_percentage` DECIMAL(5,2) DEFAULT 0.00,
  `gst_percentage`     DECIMAL(5,2) DEFAULT 12.00,
  `rating`             DECIMAL(3,1) DEFAULT 0.0,
  `star_rating`        TINYINT(1) DEFAULT 3,
  `property_type`      ENUM('hotel','resort','villa','homestay','boutique-hotel') DEFAULT 'hotel',
  `amenities`          TEXT DEFAULT NULL COMMENT 'comma-separated: wifi,pool,breakfast,parking,ac,gym,spa',
  `capacity`           TINYINT(3) DEFAULT 2 COMMENT 'max guests',
  `availability_status` ENUM('active','inactive','maintenance') DEFAULT 'active',
  `hotel_images`       TEXT DEFAULT NULL COMMENT 'JSON array of image URLs',
  `featured`           TINYINT(1) DEFAULT 0,
  `created_at`         TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`         TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_city`     (`city`),
  INDEX `idx_status`   (`availability_status`),
  INDEX `idx_rating`   (`rating`),
  INDEX `idx_price`    (`price_per_night`),
  INDEX `idx_featured` (`featured`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── Seed initial hotel data ──
INSERT INTO `hotels`
  (`hotel_name`,`city`,`location`,`state`,`description`,`price_per_night`,`original_price`,`discount_percentage`,`gst_percentage`,`rating`,`star_rating`,`property_type`,`amenities`,`capacity`,`availability_status`,`hotel_images`,`featured`)
VALUES
(
  'The Grand Palace','mumbai','Marine Drive, Mumbai','Maharashtra',
  'Iconic luxury hotel overlooking the Arabian Sea with world-class dining and premium spa facilities.',
  4299.00, 6500.00, 33.86, 12.00, 4.8, 5, 'hotel',
  'wifi,pool,breakfast,parking,spa,gym,ac',
  4, 'active',
  '["https://images.unsplash.com/photo-1566073771259-6a8506099945?w=800&q=80"]',
  1
),
(
  'Sunset Beach Resort','goa','Calangute, North Goa','Goa',
  'Beachfront resort with stunning ocean views, water sports, and award-winning seafood restaurant.',
  5499.00, 8000.00, 31.26, 12.00, 4.6, 5, 'resort',
  'wifi,pool,parking,ac',
  4, 'active',
  '["https://images.unsplash.com/photo-1571003123894-1f0594d2b5d9?w=800&q=80"]',
  1
),
(
  'Heritage Haveli','jaipur','M.I. Road, Pink City, Jaipur','Rajasthan',
  'Royal heritage property with authentic Rajasthani architecture, cultural performances and royal dining.',
  4680.00, 7200.00, 35.00, 12.00, 4.9, 5, 'boutique-hotel',
  'wifi,breakfast,ac,spa',
  4, 'active',
  '["https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?w=800&q=80"]',
  1
),
(
  'Mountain View Lodge','manali','Old Manali Road, Manali','Himachal Pradesh',
  'Cosy mountain retreat with panoramic Himalayan views, wood-fired fireplaces and adventure activities.',
  3299.00, 5500.00, 40.02, 12.00, 4.7, 4, 'hotel',
  'wifi,breakfast,ac',
  2, 'active',
  '["https://images.unsplash.com/photo-1611892440504-42a792e24d32?w=800&q=80"]',
  0
),
(
  'Lake Palace Udaipur','udaipur','Lake Pichola, Udaipur','Rajasthan',
  'Floating palace on Lake Pichola offering unparalleled royal luxury with stunning sunset views.',
  12499.00, 18000.00, 30.56, 18.00, 4.9, 5, 'resort',
  'wifi,pool,spa,breakfast,parking,ac,gym',
  6, 'active',
  '["https://images.unsplash.com/photo-1578683010236-d716f9a3f461?w=800&q=80"]',
  1
),
(
  'Kerala Backwater Resort','kerala','Alleppey Backwaters, Kerala','Kerala',
  'Serene resort on the famous backwaters with houseboat experiences and Ayurvedic treatments.',
  6799.00, 9000.00, 24.46, 12.00, 4.8, 5, 'resort',
  'wifi,breakfast,spa,ac',
  4, 'active',
  '["https://images.unsplash.com/photo-1582610116397-edb318620f90?w=800&q=80"]',
  1
),
(
  'Zen Garden Resort','kerala','Munnar Tea Estates, Kerala','Kerala',
  'Nestled in lush tea plantations with valley views, yoga retreats, and organic farm dining.',
  4100.00, 6500.00, 36.92, 12.00, 4.5, 4, 'boutique-hotel',
  'wifi,breakfast,ac',
  2, 'active',
  '["https://images.unsplash.com/photo-1561501900-3701fa6a0864?w=800&q=80"]',
  0
),
(
  'The Imperial Delhi','delhi','Janpath, New Delhi','Delhi',
  'Historic luxury hotel in the heart of New Delhi with colonial charm and modern five-star amenities.',
  8799.00, 11000.00, 20.01, 18.00, 4.7, 5, 'hotel',
  'wifi,pool,parking,ac,gym',
  4, 'active',
  '["https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?w=800&q=80"]',
  0
);

-- Verify
SELECT hotel_id, hotel_name, city, price_per_night, availability_status FROM hotels;
