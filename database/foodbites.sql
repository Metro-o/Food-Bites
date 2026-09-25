-- ============================================================
-- FoodBites Database Schema
-- Tanzanian Catering E-commerce + KDS System
-- Timezone: Africa/Dar_es_Salaam (UTC+3)
-- ============================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+03:00";
START TRANSACTION;

-- Create and select database
CREATE DATABASE IF NOT EXISTS `foodbites`
  DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;
USE `foodbites`;

-- ─────────────────────────────────────────────────────────────
-- CATEGORIES
-- ─────────────────────────────────────────────────────────────
CREATE TABLE `categories` (
  `id`         INT(11)     NOT NULL AUTO_INCREMENT,
  `name`       VARCHAR(50) NOT NULL,
  `slug`       VARCHAR(50) NOT NULL UNIQUE,
  `icon`       VARCHAR(40) DEFAULT NULL,
  `sort_order` INT(11)     NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ─────────────────────────────────────────────────────────────
-- USERS
-- ─────────────────────────────────────────────────────────────
CREATE TABLE `users` (
  `id`         INT(11)                               NOT NULL AUTO_INCREMENT,
  `name`       VARCHAR(100)                          NOT NULL,
  `email`      VARCHAR(100)                          NOT NULL UNIQUE,
  `phone`      VARCHAR(20)                           DEFAULT NULL,
  `password`   VARCHAR(255)                          NOT NULL,
  `role`       ENUM('customer','admin','kitchen')    NOT NULL DEFAULT 'customer',
  `created_at` TIMESTAMP                             NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_email` (`email`),
  INDEX `idx_role`  (`role`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ─────────────────────────────────────────────────────────────
-- PRODUCTS
-- ─────────────────────────────────────────────────────────────
CREATE TABLE `products` (
  `id`          INT(11)                        NOT NULL AUTO_INCREMENT,
  `name`        VARCHAR(150)                   NOT NULL,
  `description` TEXT                           DEFAULT NULL,
  `price`       DECIMAL(10,2)                  NOT NULL,
  `image`       VARCHAR(255)                   DEFAULT NULL,
  `category_id` INT(11)                        DEFAULT NULL,
  `is_combo`    TINYINT(1)                     NOT NULL DEFAULT 0,
  `is_bulk`     TINYINT(1)                     NOT NULL DEFAULT 0,
  `is_featured` TINYINT(1)                     NOT NULL DEFAULT 0,
  `status`      ENUM('active','inactive')      NOT NULL DEFAULT 'active',
  `created_at`  TIMESTAMP                      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_category` (`category_id`),
  INDEX `idx_status`   (`status`),
  FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ─────────────────────────────────────────────────────────────
-- ORDERS
-- ─────────────────────────────────────────────────────────────
CREATE TABLE `orders` (
  `id`                INT(11)                                              NOT NULL AUTO_INCREMENT,
  `user_id`           INT(11)                                              NOT NULL,
  `total_amount`      DECIMAL(10,2)                                        NOT NULL,
  `delivery_fee`      DECIMAL(10,2)                                        NOT NULL DEFAULT 2000.00,
  `status`            ENUM('pending','preparing','ready','delivered','cancelled') NOT NULL DEFAULT 'pending',
  `delivery_location` VARCHAR(255)                                         NOT NULL,
  `delivery_time`     DATETIME                                             DEFAULT NULL,
  `notes`             TEXT                                                 DEFAULT NULL,
  `payment_method`    ENUM('cash','mpesa','tigopesa','airtel')             NOT NULL DEFAULT 'cash',
  `order_type`        ENUM('standard','bulk','subscription')               NOT NULL DEFAULT 'standard',
  `created_at`        TIMESTAMP                                            NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`        TIMESTAMP                                            NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_user`    (`user_id`),
  INDEX `idx_status`  (`status`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ─────────────────────────────────────────────────────────────
-- ORDER ITEMS
-- ─────────────────────────────────────────────────────────────
CREATE TABLE `order_items` (
  `id`         INT(11)       NOT NULL AUTO_INCREMENT,
  `order_id`   INT(11)       NOT NULL,
  `product_id` INT(11)       NOT NULL,
  `quantity`   INT(11)       NOT NULL DEFAULT 1,
  `price`      DECIMAL(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `idx_order`   (`order_id`),
  INDEX `idx_product` (`product_id`),
  FOREIGN KEY (`order_id`)   REFERENCES `orders`(`id`)   ON DELETE CASCADE,
  FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ─────────────────────────────────────────────────────────────
-- PAYMENTS
-- ─────────────────────────────────────────────────────────────
CREATE TABLE `payments` (
  `id`         INT(11)                                   NOT NULL AUTO_INCREMENT,
  `order_id`   INT(11)                                   NOT NULL,
  `method`     ENUM('cash','mpesa','tigopesa','airtel')  NOT NULL DEFAULT 'cash',
  `amount`     DECIMAL(10,2)                             NOT NULL,
  `reference`  VARCHAR(100)                              DEFAULT NULL,
  `status`     ENUM('pending','completed','failed')      NOT NULL DEFAULT 'pending',
  `created_at` TIMESTAMP                                 NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ─────────────────────────────────────────────────────────────
-- NEWSLETTER SUBSCRIBERS
-- ─────────────────────────────────────────────────────────────
CREATE TABLE `newsletter_subscribers` (
  `id`          INT(11)      NOT NULL AUTO_INCREMENT,
  `email`       VARCHAR(150) NOT NULL UNIQUE,
  `lang`        VARCHAR(5)   DEFAULT 'en',
  `subscribed_at` TIMESTAMP  NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ─────────────────────────────────────────────────────────────
-- CART (Persistent DB cart for logged-in users)
-- ─────────────────────────────────────────────────────────────
CREATE TABLE `cart` (
  `id`         INT(11)   NOT NULL AUTO_INCREMENT,
  `user_id`    INT(11)   NOT NULL,
  `product_id` INT(11)   NOT NULL,
  `quantity`   INT(11)   NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY  `user_product` (`user_id`, `product_id`),
  FOREIGN KEY (`user_id`)    REFERENCES `users`(`id`)    ON DELETE CASCADE,
  FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ─────────────────────────────────────────────────────────────
-- SEED DATA — Categories
-- ─────────────────────────────────────────────────────────────
INSERT INTO `categories` (`name`, `slug`, `icon`, `sort_order`) VALUES
('Breakfast',   'breakfast', 'fa-mug-hot', 1),
('Lunch',       'lunch',     'fa-bowl-food', 2),
('Dinner',      'dinner',    'fa-moon', 3),
('Events',      'events',    'fa-champagne-glasses', 4),
('Combo Meals', 'combo',     'fa-boxes-stacked', 5),
('Drinks',      'drinks',    'fa-glass-water', 6);

-- ─────────────────────────────────────────────────────────────
-- SEED DATA — Users
-- Passwords are all: Admin@1234  (bcrypt hash)
-- ─────────────────────────────────────────────────────────────
INSERT INTO `users` (`name`, `email`, `phone`, `password`, `role`) VALUES
('FoodBites Admin',  'admin@foodbites.co.tz',   '+255700000000', '$2y$12$LCbZgEfEEo0YkJy5gQMdaOx2k5/5B7Q5WFM8G4Lfxqq3wHAtTaOiC', 'admin'),
('Kitchen Staff',    'kitchen@foodbites.co.tz', '+255700000001', '$2y$12$LCbZgEfEEo0YkJy5gQMdaOx2k5/5B7Q5WFM8G4Lfxqq3wHAtTaOiC', 'kitchen'),
('Amina Hassan',     'amina@example.com',       '+255712345678', '$2y$12$LCbZgEfEEo0YkJy5gQMdaOx2k5/5B7Q5WFM8G4Lfxqq3wHAtTaOiC', 'customer');

-- ─────────────────────────────────────────────────────────────
-- SEED DATA — Products
-- ─────────────────────────────────────────────────────────────
INSERT INTO `products` (`name`, `description`, `price`, `image`, `category_id`, `is_combo`, `is_bulk`, `is_featured`, `status`) VALUES
-- Breakfast
('Mandazi & Chai',         'Soft Tanzanian mandazi doughnuts with aromatic ginger spiced tea — the classic Tanzanian breakfast duo.', 4500.00, NULL, 1, 0, 0, 1, 'active'),
('Uji wa Wimbi',           'Nutritious finger millet porridge, sweetened with sugar and served warm. Traditional morning energy.', 3000.00, NULL, 1, 0, 0, 0, 'active'),
('Maandazi Mchanganyiko',  'Assorted Tanzanian pastries including maandazi, vitumbua and mkate wa ufuta (sesame bread).', 6000.00, NULL, 1, 0, 0, 0, 'active'),

-- Lunch
('Ugali na Mchuzi wa Nyama','Traditional Tanzanian ugali served with rich beef stew, slow-cooked with tomatoes and spices.', 8500.00, NULL, 2, 0, 0, 1, 'active'),
('Pilau ya Kuku',           'Aromatic spiced rice with tender chicken, cooked with cardamom, cinnamon and cloves.', 12000.00, NULL, 2, 0, 0, 1, 'active'),
('Chips Mayai',             'Classic Tanzanian street food – crispy fries mixed with fluffy omelette eggs. A beloved staple.', 7000.00, NULL, 2, 0, 0, 1, 'active'),
('Zanzibar Pizza',          'Famous street food – crispy flatbread stuffed with spiced minced meat, eggs and vegetables.', 9000.00, NULL, 2, 0, 0, 0, 'active'),
('Wali wa Nazi na Kuku',    'Coconut rice served with tangy lemon-marinated chicken stew. Coastal Swahili flavors.', 13000.00, NULL, 2, 0, 0, 0, 'active'),

-- Dinner
('Nyama Choma',             'Grilled goat or beef seasoned with traditional spices, served with kachumbari salad and ugali.', 18000.00, NULL, 3, 0, 0, 1, 'active'),
('Samaki wa Kupaka',        'Coastal Swahili fish in coconut curry sauce, served with wali wa nazi (coconut rice).', 14000.00, NULL, 3, 0, 0, 1, 'active'),
('Mbuzi Choma na Ugali',    'Whole roasted goat leg with seasoned ugali and tomato relish. Perfect weekend dinner.', 25000.00, NULL, 3, 0, 0, 0, 'active'),

-- Events / Bulk
('Office Lunch Package',    'Bulk catering for offices — minimum 10 people. Includes 2 main dishes, salad and drinks per person.', 15000.00, NULL, 4, 0, 1, 0, 'active'),
('Wedding Catering Package','Full wedding catering service — customizable menu for 50+ guests. Contact us for tailored pricing.', 500000.00, NULL, 4, 0, 1, 1, 'active'),

-- Combo Meals
('Family Combo Meal',       'Perfect family feast – ugali, pilau rice, two meat dishes, kachumbari salad and 4 sodas.', 45000.00, NULL, 5, 1, 0, 1, 'active'),
('Couple Dinner Combo',     'Romantic dinner for two – nyama choma, coconut rice, salad and two soft drinks or juice.', 32000.00, NULL, 5, 1, 0, 0, 'active'),

-- Drinks
('Chai ya Tangawizi',       'Hot ginger-spiced tea with milk, the quintessential Tanzanian beverage.', 2000.00, NULL, 6, 0, 0, 0, 'active'),
('Ubuyu (Baobab Juice)',    'Refreshing baobab fruit juice — Tanzania''s superfruit drink, served chilled.', 3500.00, NULL, 6, 0, 0, 1, 'active'),
('Madafu (Green Coconut)',  'Fresh young green coconut water, harvested locally. Naturally hydrating and sweet.', 4000.00, NULL, 6, 0, 0, 0, 'active');

-- ─────────────────────────────────────────────────────────────
-- SEED DATA — Sample Orders (for KDS demo)
-- ─────────────────────────────────────────────────────────────
INSERT INTO `orders` (`user_id`, `total_amount`, `delivery_fee`, `status`, `delivery_location`, `delivery_time`, `payment_method`, `order_type`) VALUES
(3, 22500.00, 2000.00, 'pending',   'Kinondoni, Dar es Salaam', DATE_ADD(NOW(), INTERVAL 2 HOUR), 'cash', 'standard'),
(3, 45000.00, 2000.00, 'preparing', 'Kariakoo Market, Dar es Salaam', DATE_ADD(NOW(), INTERVAL 1 HOUR), 'mpesa', 'standard');

INSERT INTO `order_items` (`order_id`, `product_id`, `quantity`, `price`) VALUES
(1, 4, 2, 8500.00),
(1, 1, 1, 4500.00),
(2, 14, 1, 45000.00);

INSERT INTO `payments` (`order_id`, `method`, `amount`, `status`) VALUES
(1, 'cash',  22500.00, 'pending'),
(2, 'mpesa', 45000.00, 'pending');

COMMIT;
