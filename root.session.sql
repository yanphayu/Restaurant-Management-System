CREATE DATABASE IF NOT EXISTS rms_mid;
USE rms_mid;

-- ==========================
-- USERS
-- ==========================
CREATE TABLE users (
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    user_name VARCHAR(100) NOT NULL,
    user_password VARCHAR(255) NOT NULL,
    user_role ENUM('admin', 'Staff') DEFAULT 'Staff',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ==========================
-- RESTAURANT TABLES
-- ==========================
CREATE TABLE restaurant_tables (
    table_id INT PRIMARY KEY AUTO_INCREMENT,
    table_name VARCHAR(100) NOT NULL,
    capacity INT NOT NULL DEFAULT 2,
    status ENUM('Available', 'Occupied', 'Reserved') DEFAULT 'Available'
);

-- ==========================
-- FOOD CATEGORIES
-- ==========================
CREATE TABLE categories (
    category_id INT PRIMARY KEY AUTO_INCREMENT,
    category_name VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ==========================
-- FOODS
-- ==========================
CREATE TABLE foods (
    food_id INT PRIMARY KEY AUTO_INCREMENT,
    category_id INT NOT NULL,
    food_name VARCHAR(100) NOT NULL,
    food_description TEXT,
    food_price DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    food_image VARCHAR(255),
    status ENUM('Available', 'Unavailable') DEFAULT 'Available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_food_category
        FOREIGN KEY (category_id)
        REFERENCES categories(category_id)
);

-- ==========================
-- ORDERS
-- ==========================
CREATE TABLE orders (
    order_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    table_id INT NOT NULL,
    order_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    status ENUM('Pending', 'Completed', 'Paid') DEFAULT 'Pending',
    total_amount DECIMAL(10,2) DEFAULT 0.00,

    CONSTRAINT fk_order_user
        FOREIGN KEY (user_id)
        REFERENCES users(user_id),

    CONSTRAINT fk_order_table
        FOREIGN KEY (table_id)
        REFERENCES restaurant_tables(table_id)
);

-- ==========================
-- ORDER DETAILS
-- ==========================
CREATE TABLE order_details (
    od_id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT NOT NULL,
    food_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    price DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    subtotal DECIMAL(10,2) NOT NULL DEFAULT 0.00,

    CONSTRAINT fk_detail_order
        FOREIGN KEY (order_id)
        REFERENCES orders(order_id),

    CONSTRAINT fk_detail_food
        FOREIGN KEY (food_id)
        REFERENCES foods(food_id)
);