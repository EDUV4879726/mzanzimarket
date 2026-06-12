CREATE DATABASE IF NOT EXISTS mzanzimarket;
USE mzanzimarket;

DROP TABLE IF EXISTS reviews;
DROP TABLE IF EXISTS orders;
DROP TABLE IF EXISTS products;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS roles;
DROP TABLE IF EXISTS categories;

CREATE TABLE roles (
    role_id INT AUTO_INCREMENT PRIMARY KEY,
    role_name VARCHAR(50) NOT NULL
);

INSERT INTO roles (role_name) VALUES
('Admin'),
('Buyer'),
('Seller');

CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    role_id INT NOT NULL,
    is_verified TINYINT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (role_id) REFERENCES roles(role_id)
);

INSERT INTO users (full_name, email, password, phone, role_id, is_verified) VALUES
('Site Admin', 'admin@mzanzimarket.co.za', '$2y$10$RODSVT/gzfd6Rc8wTj4wX.//k3eNBNGHHaN6X49SYv4YLS5cYxK7q', '0110000000', 1, 1),
('Demo Buyer', 'buyer@mzanzimarket.co.za', '$2y$10$RODSVT/gzfd6Rc8wTj4wX.//k3eNBNGHHaN6X49SYv4YLS5cYxK7q', '0830000000', 2, 1),
('Demo Seller', 'seller@mzanzimarket.co.za', '$2y$10$RODSVT/gzfd6Rc8wTj4wX.//k3eNBNGHHaN6X49SYv4YLS5cYxK7q', '0820000000', 3, 1);

CREATE TABLE categories (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    category_name VARCHAR(100) NOT NULL
);

INSERT INTO categories (category_name) VALUES
('Clothing'),
('Electronics'),
('Home Goods'),
('Books'),
('Beauty'),
('Other');

CREATE TABLE products (
    product_id INT AUTO_INCREMENT PRIMARY KEY,
    seller_id INT NOT NULL,
    category_id INT,
    product_name VARCHAR(150) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    location VARCHAR(100),
    image VARCHAR(255),
    status VARCHAR(30) DEFAULT 'Active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (seller_id) REFERENCES users(user_id),
    FOREIGN KEY (category_id) REFERENCES categories(category_id)
);

INSERT INTO products (seller_id, category_id, product_name, description, price, location, image, status) VALUES
(3, 1, 'Pre-Loved Denim Jacket', 'Good condition denim jacket suitable for casual wear.', 250.00, 'Midrand', 'jacket.jpg', 'Active'),
(3, 2, 'Used Bluetooth Speaker', 'Portable speaker with clear sound and working battery.', 180.00, 'Soweto', 'speaker.jpg', 'Active'),
(3, 3, 'Small Coffee Table', 'Affordable second-hand coffee table for a lounge.', 300.00, 'Alexandra', 'table.jpg', 'Active');

CREATE TABLE orders (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    buyer_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT DEFAULT 1,
    total_amount DECIMAL(10,2),
    order_status VARCHAR(50) DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (buyer_id) REFERENCES users(user_id),
    FOREIGN KEY (product_id) REFERENCES products(product_id)
);

CREATE TABLE reviews (
    review_id INT AUTO_INCREMENT PRIMARY KEY,
    buyer_id INT NOT NULL,
    seller_id INT NOT NULL,
    product_id INT NOT NULL,
    rating INT NOT NULL,
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (buyer_id) REFERENCES users(user_id),
    FOREIGN KEY (seller_id) REFERENCES users(user_id),
    FOREIGN KEY (product_id) REFERENCES products(product_id)
);