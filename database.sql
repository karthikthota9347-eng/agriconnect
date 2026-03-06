-- AgriConnect Database
-- Run in phpMyAdmin → SQL tab

CREATE DATABASE IF NOT EXISTS agriconnect_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE agriconnect_db;

CREATE TABLE users (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  name       VARCHAR(100) NOT NULL,
  shop_name  VARCHAR(150) DEFAULT NULL,
  phone      VARCHAR(15)  NOT NULL,
  email      VARCHAR(150) DEFAULT NULL,
  password   VARCHAR(255) NOT NULL,
  address    TEXT         DEFAULT NULL,
  role       ENUM('farmer','shop','delivery','admin') NOT NULL,
  language   VARCHAR(5)   DEFAULT 'en',
  created_at TIMESTAMP    DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE products (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  shop_id    INT           NOT NULL,
  name       VARCHAR(200)  NOT NULL,
  category   ENUM('fertilizers','pesticides','seeds','safety','tools','irrigation','organic','other') DEFAULT 'other',
  price      DECIMAL(10,2) NOT NULL,
  stock      INT           DEFAULT 0,
  unit       VARCHAR(20)   DEFAULT 'kg',
  image      VARCHAR(255)  DEFAULT NULL,
  status     ENUM('active','inactive') DEFAULT 'active',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE orders (
  id             INT AUTO_INCREMENT PRIMARY KEY,
  farmer_id      INT           NOT NULL,
  shop_id        INT           NOT NULL,
  delivery_id    INT           DEFAULT NULL,
  total_amount   DECIMAL(10,2) NOT NULL,
  status         ENUM('pending','accepted','rejected','out_for_delivery','delivered') DEFAULT 'pending',
  payment_status ENUM('unpaid','paid') DEFAULT 'unpaid',
  delivery_code  VARCHAR(6) DEFAULT NULL,
  created_at     TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE order_items (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  order_id   INT           NOT NULL,
  product_id INT           NOT NULL,
  quantity   INT           NOT NULL,
  price      DECIMAL(10,2) NOT NULL
);

CREATE TABLE delivery_locations (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  delivery_id INT           NOT NULL,
  order_id    INT           NOT NULL,
  lat           DECIMAL(10,8) NOT NULL,
  lng           DECIMAL(11,8) NOT NULL,
  delivery_name  VARCHAR(100) DEFAULT NULL,
  delivery_phone VARCHAR(15)  DEFAULT NULL,
  updated_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Contact/Support Messages
CREATE TABLE contacts (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  user_id    INT          DEFAULT NULL,
  role       ENUM('farmer','shop','guest') DEFAULT 'guest',
  name       VARCHAR(100) NOT NULL,
  phone      VARCHAR(15)  NOT NULL,
  email      VARCHAR(150) DEFAULT NULL,
  subject    VARCHAR(200) NOT NULL,
  message    TEXT         NOT NULL,
  status     ENUM('new','read','resolved') DEFAULT 'new',
  admin_reply TEXT        DEFAULT NULL,
  created_at TIMESTAMP    DEFAULT CURRENT_TIMESTAMP
);

-- Admin users
CREATE TABLE admins (
  id       INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50)  NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL
);

-- Default admin: username=admin, password=admin123
INSERT INTO admins (username,password) VALUES ('admin','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');
