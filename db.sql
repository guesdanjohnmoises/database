-- db.sql: create database and tables
CREATE DATABASE IF NOT EXISTS crud_db CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE crud_db;

CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(80) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  fullname VARCHAR(120) DEFAULT '',
  role ENUM('admin','staff') DEFAULT 'staff'
);

CREATE TABLE IF NOT EXISTS products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(200) NOT NULL,
  category VARCHAR(80) DEFAULT '',
  price DECIMAL(10,2) DEFAULT 0.00,
  stock INT DEFAULT 0,
  image VARCHAR(255) DEFAULT NULL
);

CREATE TABLE IF NOT EXISTS orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  customer VARCHAR(150) NOT NULL,
  product_id INT,
  quantity INT,
  total_price DECIMAL(12,2),
  order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE SET NULL
);

-- default admin (password is plain 'admin123' and will be upgraded to a hash on first login)
INSERT INTO users (username, password, fullname, role) VALUES ('admin', 'admin123', 'Administrator', 'admin');

-- sample products
INSERT INTO products (name, category, price, stock) VALUES
('Unisex Cotton T-Shirt', 'Clothes', 299.00, 25),
('Sports Sneakers', 'Shoes', 1499.00, 10),
('Wireless Earbuds', 'Gadgets', 1299.00, 6),
('Chocolate Cookies Pack', 'Food', 89.00, 50);

