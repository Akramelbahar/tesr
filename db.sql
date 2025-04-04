-- Create database
CREATE DATABASE internship_management;
USE internship_management;

-- Students Table
CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(20),
    department VARCHAR(100),
    graduation_year INT
);

-- Companies Table
CREATE TABLE companies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    company_name VARCHAR(100) NOT NULL,
    contact_person VARCHAR(100),
    email VARCHAR(100),
    phone VARCHAR(20),
    address TEXT
);

-- Internships Table
CREATE TABLE internships (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT,
    company_id INT,
    start_date DATE,
    end_date DATE,
    position VARCHAR(100),
    status ENUM('Pending', 'Approved', 'In Progress', 'Completed', 'Cancelled') DEFAULT 'Pending',
    description TEXT,
    FOREIGN KEY (student_id) REFERENCES students(id),
    FOREIGN KEY (company_id) REFERENCES companies(id)
);

-- Application Logs Table
CREATE TABLE application_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    internship_id INT,
    log_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    action VARCHAR(100),
    details TEXT,
    FOREIGN KEY (internship_id) REFERENCES internships(id)
);

-- Sample Data Insertion
INSERT INTO students (first_name, last_name, email, phone, department, graduation_year) VALUES
('John', 'Doe', 'john.doe@example.com', '555-1234', 'Computer Science', 2024),
('Jane', 'Smith', 'jane.smith@example.com', '555-5678', 'Business Administration', 2025);

INSERT INTO companies (company_name, contact_person, email, phone, address) VALUES
('Tech Innovations Inc.', 'Michael Brown', 'michael.brown@techinnovations.com', '555-9876', '123 Tech Street, Silicon Valley, CA'),
('Global Solutions', 'Sarah Johnson', 'sarah.johnson@globalsolutions.com', '555-4321', '456 Business Avenue, New York, NY');

INSERT INTO internships (student_id, company_id, start_date, end_date, position, status, description) VALUES
(1, 1, '2024-06-01', '2024-08-31', 'Software Development Intern', 'Pending', 'Developing web applications and assisting in software development processes'),
(2, 2, '2024-07-15', '2024-12-15', 'Marketing Strategy Intern', 'Pending', 'Supporting marketing team in strategy development and market research');
