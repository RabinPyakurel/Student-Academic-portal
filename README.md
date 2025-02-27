# **Student Academic Portal**  
A comprehensive student management system designed to facilitate students, administrators, and educators in managing academic activities efficiently.  

---

## **Table of Contents**  
1. [Project Overview](#project-overview)  
2. [Key Features](#key-features)  
3. [Tech Stack](#tech-stack)  
4. [Setup and Installation](#setup-and-installation)  
5. [Usage](#usage)   
6. [Contributors](#contributors)    
7. [Future Enhancements](#future-enhancements)  

---

## **Project Overview**  
The **Student Academic Portal** is a web-based application built as part of our BCA 4th semester coursework. It streamlines academic processes such as attendance tracking, fee payment, examination form submission, library book management, and event participation for students. For administrators, it provides tools to manage academic data, student records, and generate reports.  

---

## **Key Features**  

### **For Students**:  
- View attendance records.  
- Apply for event participation and track application status.  
- View fee status and make fee payments online.  
- Fill up examination forms and view exam schedules.  
- View examination results and detailed marks.  
- Borrow or return library books online by selecting specific dates (auto-return after 14 days).  

### **For Admins**:  
- Manage student academic and personal data.  
- View and generate reports.  
- Manage event applications and academic records.  

---

## **Tech Stack**  

### **Frontend**:  
- HTML  
- CSS  
- JavaScript  
- AJAX with jQuery  

### **Backend**:  
- PHP  
- MySQL  

### **Additional Tools**:  
- **PHPmailer**: Used for sending email notifications (e.g., reminders, confirmations).  
- **MySQL Triggers**: Dynamically generates notifications for students.  

---

## **Setup and Installation**  

### **Requirements**:  
- PHP 7.x or higher  
- MySQL 5.x or higher  
- Web server (e.g., Apache or XAMPP/WAMP)  
- Modern web browser  

### **Steps to Run the Project**:  
1. Clone the repository:  
   ```bash
   git clone https://github.com/RabinPyakurel/Student-Academic-portal.git
   ```
2. Move the project folder to your web server directory (e.g., `htdocs` for XAMPP).  
3. Import the database:  
   - Open phpMyAdmin and create a database (e.g., `student_portal`).  
   - Run the config.php file in your server to execute table creation and trigger creation. 
4. Configure database connection:  
   - Open `db_config.php` and update the database credentials:  
     ```php
     <?php
     $servername = "localhost";
     $username = "root";
     $password = "";
     $dbname = "sapo";
     ?>
     ```
5. Start your web server and navigate to `http://localhost/student-academic-portal/`.  

---

## **Usage**  

1. **Login**:  
   - Students and admins can log in using their credentials.  
   - Default credentials for admin:  
     - Username: `admin`  
     - Password: `admin123`  

2. **Student Dashboard**:  
   - Access attendance, fee status, exam forms, and library module.  

3. **Admin Dashboard**:  
   - Manage students, update records, and generate reports.  

---

## **Contributors**  

### **1. Rabin Babu Pyakurel**  
- Role: Full-stack Developer  
- Contributions:  
  - Designed and developed core modules like attendance, fee management, and exam schedules.  
  - Configured backend functionalities using PHP and MySQL.  
  - Utilized triggers for dynamic notifications.  
  - Implemented AJAX with jQuery for smooth UI interaction.  

### **2. Ritika Suwal**  
- Role: Full-stack Developer, UI/UX Designer  
- Contributions:  
  - Designed and developed modules like library management and event participation.  
  - Focused on improving user experience and front-end design.  
  - Collaborated on backend integration and database design.  

---

## **Future Enhancements**  
- Add a mobile-responsive design.  
- Integrate API-based email notifications for scalability.  
- Implement role-based access control for better security.  
- Expand the portal to be usable for other colleges under different universities.  

---
