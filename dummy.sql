
-- =============================
-- Table: sales
-- =============================
DROP TABLE IF EXISTS sales;
CREATE TABLE sales (
  id SERIAL PRIMARY KEY,
  customer_id INT,
  product_id INT,
  amount DECIMAL(12,2),
  sale_date DATE
);

INSERT INTO sales (customer_id, product_id, amount, sale_date) VALUES
(101, 11, 50000, '2024-01-05'),
(102, 13, 20000, '2024-01-06'),
(101, 12, 100000, '2024-01-07'),
(103, 11, 150000, '2024-01-07');


-- =============================
-- Table: employees
-- =============================
DROP TABLE IF EXISTS employees;
CREATE TABLE employees (
  id SERIAL PRIMARY KEY,
  name VARCHAR(50),
  department VARCHAR(50),
  salary DECIMAL(12,2)
);

INSERT INTO employees (name, department, salary) VALUES
('Andi', 'IT', 7000),
('Budi', 'IT', 8000),
('Citra', 'HR', 5000),
('Deni', 'HR', 6000);


-- =============================
-- Table: orders & payments
-- =============================
DROP TABLE IF EXISTS orders;
DROP TABLE IF EXISTS payments;

CREATE TABLE orders (
  id SERIAL PRIMARY KEY,
  customer_id INT,
  total_amount DECIMAL(12,2)
);

CREATE TABLE payments (
  id SERIAL PRIMARY KEY,
  order_id INT,
  paid_amount DECIMAL(12,2)
);

INSERT INTO orders (customer_id, total_amount) VALUES
(1, 50000),
(2, 75000);

INSERT INTO payments (order_id, paid_amount) VALUES
(1, 50000),
(2, 50000);


-- =============================
-- Table: categories
-- =============================
DROP TABLE IF EXISTS categories;
CREATE TABLE categories (
  id SERIAL PRIMARY KEY,
  name VARCHAR(50),
  parent_id INT
);

INSERT INTO categories (name, parent_id) VALUES
('Elektronik', NULL),
('Laptop', 1),
('Gaming', 2),
('Aksesoris', 1),
('Headset', 4);


-- =============================
-- Table: attendance
-- =============================
DROP TABLE IF EXISTS attendance;
CREATE TABLE attendance (
  id SERIAL PRIMARY KEY,
  name VARCHAR(50),
  day VARCHAR(20),
  status VARCHAR(20)
);

INSERT INTO attendance (name, day, status) VALUES
('Andi', 'Monday', 'Present'),
('Andi', 'Tuesday', 'Absent'),
('Budi', 'Monday', 'Present'),
('Budi', 'Tuesday', 'Present');


-- ==========================================================
-- DUMMY DATA
-- ==========================================================
DROP TABLE IF EXISTS customers;
DROP TABLE IF EXISTS products;
DROP TABLE IF EXISTS sales_extended;

CREATE TABLE customers (
  id SERIAL PRIMARY KEY,
  name VARCHAR(50),
  city VARCHAR(50),
  join_date DATE
);

CREATE TABLE products (
  id SERIAL PRIMARY KEY,
  name VARCHAR(50),
  category VARCHAR(50),
  price DECIMAL(10,2)
);

CREATE TABLE sales_extended (
  id SERIAL PRIMARY KEY,
  customer_id INT,
  product_id INT,
  quantity INT,
  sale_date DATE
);

INSERT INTO customers (name, city, join_date) VALUES
('Andi', 'Jakarta', '2023-01-01'),
('Budi', 'Bandung', '2023-02-15'),
('Citra', 'Surabaya', '2023-03-01'),
('Deni', 'Jakarta', '2023-04-01'),
('Eka', 'Medan', '2023-05-12');

INSERT INTO products (name, category, price) VALUES
('Laptop A', 'Elektronik', 15000000),
('Laptop B', 'Elektronik', 18000000),
('Mouse X', 'Aksesoris', 300000),
('Keyboard Y', 'Aksesoris', 700000),
('Headset Z', 'Aksesoris', 500000);

INSERT INTO sales_extended (customer_id, product_id, quantity, sale_date) VALUES
(1, 1, 2, '2024-01-10'),
(2, 2, 1, '2024-01-11'),
(1, 3, 5, '2024-01-15'),
(3, 5, 3, '2024-01-16'),
(4, 1, 1, '2024-02-01'),
(4, 4, 2, '2024-02-02'),
(5, 2, 1, '2024-02-03'),
(1, 5, 1, '2024-02-10');