Database Requirements
Database Names: project101 and healthcare

Tables in project101:

admin_loginpage
book_medicine
first_aid
ambulance_request
am_pro_username_passw
pharma_upload
Tables in healthcare:

appointments
blogs
doctors
hospital_appointments
SQL Statements
1. Create Databases
sql
Copy code
CREATE DATABASE project101;
CREATE DATABASE healthcare;
2. Create Tables in project101 Database
admin_loginpage Table

sql
Copy code
USE project101;

CREATE TABLE admin_loginpage (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL
);
book_medicine Table

sql
Copy code
CREATE TABLE book_medicine (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(255) NOT NULL,
    last_name VARCHAR(255) NOT NULL,
    age INT NOT NULL,
    gender VARCHAR(50),
    medicine_name VARCHAR(255) NOT NULL,
    quantity INT NOT NULL,
    submission_date DATETIME
);
first_aid Table

sql
Copy code
CREATE TABLE first_aid (
    id INT AUTO_INCREMENT PRIMARY KEY,
    type_of_accident VARCHAR(255) NOT NULL,
    procedures TEXT NOT NULL,
    image VARCHAR(255)
);
ambulance_request Table

sql
Copy code
CREATE TABLE ambulance_request (
    id INT AUTO_INCREMENT PRIMARY KEY,
    provider_id INT NOT NULL,
    am_provider VARCHAR(255),
    name VARCHAR(255) NOT NULL,
    age INT NOT NULL,
    sex VARCHAR(50),
    location VARCHAR(255),
    phone_number VARCHAR(20),
    submission_date DATETIME,
    time TIME
);
am_pro_username_passw Table

sql
Copy code
CREATE TABLE am_pro_username_passw (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL
);
pharma_upload Table

sql
Copy code
CREATE TABLE pharma_upload (
    id INT AUTO_INCREMENT PRIMARY KEY,
    medicine_name VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    image_pat VARCHAR(255)
);
3. Create Tables in healthcare Database
appointments Table

sql
Copy code
USE healthcare;

CREATE TABLE appointments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    doctor VARCHAR(255) NOT NULL,
    date DATE NOT NULL,
    time TIME NOT NULL,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255),
    phone VARCHAR(20),
    doctor_id INT,
    status VARCHAR(50)
);
blogs Table

sql
Copy code
CREATE TABLE blogs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    created_at DATETIME,
    image VARCHAR(255)
);
doctors Table

sql
Copy code
CREATE TABLE doctors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    doctor_id INT NOT NULL,
    location VARCHAR(255),
    specialties VARCHAR(255),
    description TEXT,
    image_path VARCHAR(255),
    popular BOOLEAN,
    hospital VARCHAR(255)
);
hospital_appointments Table

sql
Copy code
CREATE TABLE hospital_appointments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    hospital VARCHAR(255) NOT NULL,
    hospital_id INT NOT NULL,
    date DATE NOT NULL,
    time TIME NOT NULL,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255),
    phone VARCHAR(20),
    status VARCHAR(50)
);
Instructions
Ensure your MySQL server is running.
Run the above SQL statements to create both databases and the necessary tables.
Update the database connection settings in your project files to match your environment.
Once the databases and tables are set up, your project should run as expected.
