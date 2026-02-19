CREATE DATABASE IF NOT EXISTS car_showroom CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE car_showroom;

SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS orders;
DROP TABLE IF EXISTS inquiries;
DROP TABLE IF EXISTS sell_requests;
DROP TABLE IF EXISTS car_images;
DROP TABLE IF EXISTS cars;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS users;
SET FOREIGN_KEY_CHECKS=1;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  full_name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('Admin','Customer') NOT NULL DEFAULT 'Customer',
  mobile VARCHAR(20) NULL,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE categories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  slug VARCHAR(100) NOT NULL,
  is_active TINYINT(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB;

CREATE TABLE cars (
  id INT AUTO_INCREMENT PRIMARY KEY,
  category_id INT NULL,
  title VARCHAR(150) NOT NULL,
  brand VARCHAR(100) NOT NULL,
  model VARCHAR(100) NOT NULL,
  car_year INT NOT NULL,
  fuel VARCHAR(50) NOT NULL,
  transmission VARCHAR(50) NOT NULL,
  price DECIMAL(12,2) NOT NULL,
  mileage_km INT NOT NULL DEFAULT 0,
  location VARCHAR(100) NOT NULL,
  description TEXT NULL,
  status VARCHAR(50) NOT NULL DEFAULT 'Published',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_car_cat FOREIGN KEY (category_id) REFERENCES categories(id)
) ENGINE=InnoDB;

CREATE TABLE car_images (
  id INT AUTO_INCREMENT PRIMARY KEY,
  car_id INT NOT NULL,
  image_path VARCHAR(191) NOT NULL,
  is_primary TINYINT(1) NOT NULL DEFAULT 0,
  CONSTRAINT fk_img_car FOREIGN KEY (car_id) REFERENCES cars(id)
) ENGINE=InnoDB;

CREATE TABLE sell_requests (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  car_title VARCHAR(150) NOT NULL,
  car_year INT NOT NULL,
  fuel VARCHAR(50) NOT NULL,
  transmission VARCHAR(50) NOT NULL,
  expected_price DECIMAL(12,2) NOT NULL,
  mileage_km INT NOT NULL DEFAULT 0,
  location VARCHAR(100) NOT NULL,
  notes TEXT NULL,
  status VARCHAR(50) NOT NULL DEFAULT 'Pending',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_sell_user FOREIGN KEY (user_id) REFERENCES users(id)
) ENGINE=InnoDB;

CREATE TABLE inquiries (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NULL,
  car_id INT NULL,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL,
  phone VARCHAR(20) NULL,
  message TEXT NOT NULL,
  status VARCHAR(50) NOT NULL DEFAULT 'New',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_inq_user FOREIGN KEY (user_id) REFERENCES users(id),
  CONSTRAINT fk_inq_car FOREIGN KEY (car_id) REFERENCES cars(id)
) ENGINE=InnoDB;

CREATE TABLE orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  car_id INT NOT NULL,
  amount DECIMAL(12,2) NOT NULL,
  status VARCHAR(50) NOT NULL DEFAULT 'Pending',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_order_user FOREIGN KEY (user_id) REFERENCES users(id),
  CONSTRAINT fk_order_car FOREIGN KEY (car_id) REFERENCES cars(id)
) ENGINE=InnoDB;

-- Seed (3-4 rows each)
INSERT INTO categories (name,slug,is_active) VALUES
('SUV','suv',1),('Sedan','sedan',1),('Hatchback','hatchback',1),('Electric','electric',1);

-- Password hashes are bcrypt (works PHP 5.6+)
-- Admin@123 and User@123
INSERT INTO users (full_name,email,password_hash,role,mobile,is_active) VALUES
('Admin User','admin@demo.com','$2y$10$lOfwqI5gPPFfOunAsb/6x.3S0HjvBAPQmGxbrw0i8U7j7h8z1vGf6','Admin','9999999999',1),
('Rahul Sharma','user1@demo.com','$2y$10$4Jt2mJmQG9h0i1RrB8e0vO8q6Aq4QY7T9Pj6R1J5yYp9dVYvQkZ4K','Customer','9000000001',1),
('Ayesha Khan','user2@demo.com','$2y$10$4Jt2mJmQG9h0i1RrB8e0vO8q6Aq4QY7T9Pj6R1J5yYp9dVYvQkZ4K','Customer','9000000002',1),
('Vivek Patil','user3@demo.com','$2y$10$4Jt2mJmQG9h0i1RrB8e0vO8q6Aq4QY7T9Pj6R1J5yYp9dVYvQkZ4K','Customer','9000000003',1);

INSERT INTO cars (category_id,title,brand,model,car_year,fuel,transmission,price,mileage_km,location,description,status) VALUES
(1,'Urban SUV 2022','Toyota','HyRider',2022,'Petrol','Automatic',1250000,28000,'Pune','Well maintained SUV, single owner, full service records.','Published'),
(2,'City Sedan 2021','Honda','City',2021,'Diesel','Manual',975000,42000,'Mumbai','Comfortable sedan with excellent mileage and clean interior.','Published'),
(3,'Smart Hatch 2020','Hyundai','i20',2020,'Petrol','Manual',525000,56000,'Nagpur','Budget-friendly hatchback. Service history available.','Published'),
(4,'Electro EV 2023','Tata','Nexon EV',2023,'Electric','Automatic',1850000,12000,'Delhi','Long range EV with fast charging and warranty.','Published');

INSERT INTO car_images (car_id,image_path,is_primary) VALUES
(1,'assets/images/suv.jpg',1),
(2,'assets/images/sedan.jpg',1),
(3,'assets/images/hatch.jpg',1),
(4,'assets/images/ev.jpg',1);

INSERT INTO sell_requests (user_id,car_title,car_year,fuel,transmission,expected_price,mileage_km,location,notes,status) VALUES
(2,'Family Sedan 2018',2018,'Diesel','Manual',450000,82000,'Aurangabad','Urgent sale, negotiable.','Pending'),
(3,'Mini SUV 2017',2017,'Petrol','Manual',520000,90000,'Pune','Single owner, insurance valid.','Approved'),
(4,'City Hatch 2016',2016,'CNG','Manual',320000,110000,'Mumbai','CNG updated, good condition.','Rejected'),
(3,'Luxury Sedan 2020',2020,'Petrol','Automatic',2100000,26000,'Hyderabad','Premium maintained, showroom serviced.','Pending');

INSERT INTO inquiries (user_id,car_id,name,email,phone,message,status) VALUES
(2,1,'Rahul Sharma','user1@demo.com','9000000001','Is this available for weekend test drive?','New'),
(3,2,'Ayesha Khan','user2@demo.com','9000000002','Can you share service history and final price?','Closed'),
(NULL,4,'Guest Buyer','guest@example.com','9111111111','Interested. Please call me back.','New'),
(4,3,'Vivek Patil','user3@demo.com','9000000003','Is there negotiation on listed price?','New');

INSERT INTO orders (user_id,car_id,amount,status) VALUES
(2,2,975000,'Pending'),
(3,1,1250000,'Paid'),
(4,3,525000,'Cancelled'),
(3,4,1850000,'Pending');
