-- Student Hostel Management System Database
DROP DATABASE IF EXISTS hostel_management;
CREATE DATABASE hostel_management;
USE hostel_management;

-- 1. Hostels table
CREATE TABLE hostels (
    hostel_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    location VARCHAR(100),
    total_rooms INT NOT NULL,
    warden_name VARCHAR(100),
    contact_number VARCHAR(15),
    UNIQUE KEY uq_hostels_name (name)
);

-- 2. Rooms table
CREATE TABLE rooms (
    room_id INT AUTO_INCREMENT PRIMARY KEY,
    hostel_id INT,
    room_number VARCHAR(10) NOT NULL,
    capacity INT NOT NULL DEFAULT 1,
    current_occupancy INT DEFAULT 0,
    room_type ENUM('Single', 'Double', 'Triple', 'Dormitory') DEFAULT 'Single',
    amenities TEXT,
    status ENUM('Available', 'Occupied', 'Maintenance') DEFAULT 'Available',
    FOREIGN KEY (hostel_id) REFERENCES hostels(hostel_id) ON DELETE CASCADE,
    UNIQUE KEY uq_rooms_hostel_number (hostel_id, room_number)
);

-- 3. Students table
CREATE TABLE students (
    student_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    date_of_birth DATE,
    contact_number VARCHAR(15),
    home_address TEXT,
    emergency_contact_name VARCHAR(100),
    emergency_contact_number VARCHAR(15),
    gender ENUM('Male', 'Female', 'Other') NOT NULL
);

-- 4. Allocations table
CREATE TABLE allocations (
    allocation_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT,
    room_id INT,
    hostel_id INT,
    check_in_date DATE NOT NULL,
    check_out_date DATE,
    status ENUM('Active', 'Completed', 'Cancelled') DEFAULT 'Active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(student_id) ON DELETE CASCADE,
    FOREIGN KEY (room_id) REFERENCES rooms(room_id) ON DELETE CASCADE,
    FOREIGN KEY (hostel_id) REFERENCES hostels(hostel_id) ON DELETE CASCADE
);

-- 5. Payments table
CREATE TABLE payments (
    payment_id INT AUTO_INCREMENT PRIMARY KEY,
    allocation_id INT,
    student_id INT,
    amount DECIMAL(10, 2) NOT NULL,
    payment_date DATE NOT NULL,
    due_date DATE NOT NULL,
    payment_method ENUM('Cash', 'Card', 'Bank Transfer', 'Online') DEFAULT 'Cash',
    status ENUM('Paid', 'Pending', 'Overdue') DEFAULT 'Pending',
    description TEXT,
    FOREIGN KEY (allocation_id) REFERENCES allocations(allocation_id) ON DELETE CASCADE,
    FOREIGN KEY (student_id) REFERENCES students(student_id) ON DELETE CASCADE
);

-- 6. Staff table
CREATE TABLE staff (
    staff_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    position VARCHAR(100) NOT NULL,
    contact_number VARCHAR(15),
    hire_date DATE,
    salary DECIMAL(10, 2),
    hostel_id INT,
    FOREIGN KEY (hostel_id) REFERENCES hostels(hostel_id) ON DELETE SET NULL
);

-- 7. Complaints/Maintenance table
CREATE TABLE complaints (
    complaint_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT,
    hostel_id INT,
    room_id INT,
    type ENUM('Maintenance', 'Cleaning', 'Electrical', 'Plumbing', 'Other') DEFAULT 'Maintenance',
    description TEXT NOT NULL,
    status ENUM('Pending', 'In Progress', 'Resolved') DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    resolved_at TIMESTAMP NULL,
    FOREIGN KEY (student_id) REFERENCES students(student_id) ON DELETE CASCADE,
    FOREIGN KEY (hostel_id) REFERENCES hostels(hostel_id) ON DELETE CASCADE,
    FOREIGN KEY (room_id) REFERENCES rooms(room_id) ON DELETE CASCADE
);

-- 8. Visitors table
CREATE TABLE visitors (
    visitor_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT,
    visitor_name VARCHAR(100) NOT NULL,
    visitor_contact VARCHAR(15),
    relation VARCHAR(50),
    check_in TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    check_out TIMESTAMP NULL,
    purpose TEXT,
    FOREIGN KEY (student_id) REFERENCES students(student_id) ON DELETE CASCADE
);

-- Insert sample data
INSERT INTO hostels (name, location, total_rooms, warden_name, contact_number) VALUES 
('North Hall', 'North Campus', 50, 'Dr. Ahmed', '0123456789'),
('South Hall', 'South Campus', 60, 'Dr. Fatima', '0123456790'),
('East Hall', 'East Campus', 40, 'Dr. Rahman', '0123456791');

INSERT INTO rooms (hostel_id, room_number, capacity, room_type, amenities, status) VALUES
(1, '101', 2, 'Double', 'AC, Attached Bathroom', 'Available'),
(1, '102', 1, 'Single', 'Non-AC, Common Bathroom', 'Available'),
(1, '103', 3, 'Triple', 'AC, Attached Bathroom, Balcony', 'Occupied'),
(2, '201', 2, 'Double', 'AC, Attached Bathroom', 'Available'),
(2, '202', 4, 'Dormitory', 'Non-AC, Common Bathroom', 'Maintenance');

INSERT INTO students (first_name, last_name, email, date_of_birth, contact_number, home_address, emergency_contact_name, emergency_contact_number, gender) VALUES
('Emon', 'Islam', 'emon@student.edu', '2004-03-12', '0123456001', '123 Main St, Dhaka', 'Father Islam', '0123456101', 'Male'),
('Farah', 'Akter', 'farah@student.edu', '2003-11-22', '0123456002', '456 Park Ave, Chittagong', 'Mother Akter', '0123456102', 'Female'),
('Golam', 'Ahmed', 'golam@student.edu', '2004-07-05', '0123456003', '789 Road, Sylhet', 'Uncle Ahmed', '0123456103', 'Male');

INSERT INTO allocations (student_id, room_id, hostel_id, check_in_date, status) VALUES
(1, 3, 1, '2025-01-15', 'Active'),
(2, 1, 2, '2025-02-01', 'Active'),
(3, 3, 1, '2025-01-15', 'Active');

INSERT INTO payments (allocation_id, student_id, amount, payment_date, due_date, payment_method, status, description) VALUES
(1, 1, 5000.00, '2025-01-10', '2025-01-15', 'Bank Transfer', 'Paid', 'Monthly Hostel Fee'),
(2, 2, 6000.00, '2025-01-28', '2025-02-01', 'Online', 'Paid', 'Monthly Hostel Fee'),
(3, 3, 5000.00, '2025-01-12', '2025-01-15', 'Cash', 'Paid', 'Monthly Hostel Fee');

INSERT INTO staff (first_name, last_name, email, position, contact_number, hire_date, salary, hostel_id) VALUES
('Karim', 'Uddin', 'karim@hostel.edu', 'Cleaner', '0123456701', '2020-05-10', 15000.00, 1),
('Rina', 'Begum', 'rina@hostel.edu', 'Cook', '0123456702', '2019-11-15', 18000.00, 2),
('Salam', 'Mia', 'salam@hostel.edu', 'Security', '0123456703', '2021-03-20', 16000.00, 1);

INSERT INTO complaints (student_id, hostel_id, room_id, type, description, status) VALUES
(1, 1, 3, 'Electrical', 'Light not working in bathroom', 'Resolved'),
(2, 2, 1, 'Plumbing', 'Water leakage from tap', 'In Progress'),
(3, 1, 3, 'Other', 'Need extra chair', 'Pending');

INSERT INTO visitors (student_id, visitor_name, visitor_contact, relation, purpose) VALUES
(1, 'Rafiq Islam', '0123456801', 'Father', 'Personal visit'),
(2, 'Saleha Begum', '0123456802', 'Mother', 'Giving essentials'),
(3, 'Jamal Ahmed', '0123456803', 'Brother', 'Personal visit');