<?php
try{
    $pdo = new PDO("mysql:host=localhost",'root','rabin');
    $pdo->exec("create database IF NOT EXISTS sapo");
    $pdo->exec("use sapo");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch(PDOException $e){
    echo 'connection failed: '.$e->getMessage();
    exit();
}

$tables = [ "CREATE TABLE IF NOT EXISTS department (
                dept_id int NOT NULL,
                dept_name varchar(50) NOT NULL,
                 PRIMARY KEY (dept_id)
            );",
            "CREATE TABLE IF NOT EXISTS role (
                role_id int NOT NULL AUTO_INCREMENT,
                role_name varchar(50) NOT NULL,
                PRIMARY KEY (role_id)
            );",
              "CREATE TABLE IF NOT EXISTS program (
                program_id int NOT NULL,
                program_name varchar(100) NOT NULL,
                dept_id int DEFAULT NULL,
                PRIMARY KEY (program_id),
                KEY pr_did_fk (dept_id),
                CONSTRAINT pr_did_fk FOREIGN KEY (dept_id) REFERENCES department (dept_id)
            );",
              "CREATE TABLE IF NOT EXISTS student (
                std_id int NOT NULL,
                email varchar(255) NOT NULL,
                name varchar(100) NOT NULL,
                semester int NOT NULL,
                program_id int NOT NULL,
                enrollment_year int NOT NULL,
                dob date DEFAULT NULL,
                personal_email varchar(255) DEFAULT NULL,
                contact_number varchar(15) DEFAULT NULL,
                photo_url varchar(255) DEFAULT NULL,
                PRIMARY KEY (std_id),
                UNIQUE KEY st_email_un (email),
                UNIQUE KEY st_pemail_un (personal_email),
                UNIQUE KEY st_contact_un (contact_number),
                KEY st_pid_fk (program_id),
                CONSTRAINT st_pid_fk FOREIGN KEY (program_id) REFERENCES program (program_id)
            );",
                "CREATE TABLE IF NOT EXISTS admin (
                  admin_id int NOT NULL,
                  username varchar(50) NOT NULL,
                  role_id int DEFAULT NULL,
                  password varchar(255) DEFAULT NULL,
                  PRIMARY KEY (admin_id),
                  UNIQUE KEY ad_username_un (username),
                  KEY ad_rid_fk (role_id),
                  CONSTRAINT ad_rid_fk FOREIGN KEY (role_id) REFERENCES role (role_id)
            );",
                "CREATE TABLE IF NOT EXISTS library (
                  book_id int NOT NULL,
                  title varchar(100) NOT NULL,
                  author varchar(100) DEFAULT NULL,
                  publisher varchar(100) DEFAULT NULL,
                  year_published int DEFAULT NULL,
                  available_copies int DEFAULT NULL,
                  total_copies int DEFAULT NULL,
                  PRIMARY KEY (book_id)
            );",
                "CREATE TABLE IF NOT EXISTS attendance (
                attendance_id int NOT NULL AUTO_INCREMENT,
                std_id int DEFAULT NULL,
                semester int DEFAULT NULL,
                date date NOT NULL,
                status enum('Present','Absent','Late') NOT NULL,
                remarks text,
                PRIMARY KEY (attendance_id),
                KEY std_id (std_id),
                KEY course_id (semester),
                CONSTRAINT attendance_ibfk_1 FOREIGN KEY (std_id) REFERENCES student (std_id)
            );",
                "CREATE TABLE IF NOT EXISTS billing (
                  billing_id int NOT NULL,
                  std_id int DEFAULT NULL,
                  amount decimal(10,2) NOT NULL,
                  billing_date date DEFAULT NULL,
                  status enum('Paid','Unpaid') DEFAULT 'Unpaid',
                  PRIMARY KEY (billing_id),
                  KEY bi_sid_fk (std_id),
                  CONSTRAINT bi_sid_fk FOREIGN KEY (std_id) REFERENCES student (std_id)
            );",
                "CREATE TABLE IF NOT EXISTS borrowedbooks (
                  borrow_id int NOT NULL,
                  std_id int DEFAULT NULL,
                  book_id int DEFAULT NULL,
                  borrowed_date date DEFAULT NULL,
                  returned_date date DEFAULT NULL,
                  PRIMARY KEY (borrow_id),
                  KEY bb_sid_fk (std_id),
                  KEY bb_bid_fk (book_id),
                  CONSTRAINT bb_bid_fk FOREIGN KEY (book_id) REFERENCES library (book_id),
                  CONSTRAINT bb_sid_fk FOREIGN KEY (std_id) REFERENCES student (std_id)
                );",
                "CREATE TABLE IF NOT EXISTS course (
                  course_id int NOT NULL,
                  course_name varchar(100) NOT NULL,
                  program_id int DEFAULT NULL,
                  semester int DEFAULT NULL,
                  PRIMARY KEY (course_id),
                  KEY co_oid_fk (program_id),
                  CONSTRAINT co_oid_fk FOREIGN KEY (program_id) REFERENCES program (program_id)
                );",
                "CREATE TABLE IF NOT EXISTS emergency_contact (
                  contact_id int NOT NULL AUTO_INCREMENT,
                  std_id int DEFAULT NULL,
                  emergency_name varchar(100) DEFAULT NULL,
                  relation varchar(50) DEFAULT NULL,
                  contact varchar(15) DEFAULT NULL,
                  guardian_contact varchar(15) DEFAULT NULL,
                  PRIMARY KEY (contact_id),
                  KEY ec_sid_fk (std_id),
                  CONSTRAINT ec_sid_fk FOREIGN KEY (std_id) REFERENCES student (std_id)
                );",
                "CREATE TABLE IF NOT EXISTS event (
                  event_id int NOT NULL,
                  event_name varchar(100) NOT NULL,
                  event_date date DEFAULT NULL,
                  event_description text,
                  created_by int DEFAULT NULL,
                  PRIMARY KEY (event_id),
                  KEY evt_cb_fk (created_by),
                  CONSTRAINT evt_cb_fk FOREIGN KEY (created_by) REFERENCES admin (admin_id)
                );",
                "CREATE TABLE IF NOT EXISTS exam (
                  exam_id int NOT NULL,
                  exam_name varchar(100) NOT NULL,
                  exam_date date DEFAULT NULL,
                  course_id int DEFAULT NULL,
                  PRIMARY KEY (exam_id),
                  KEY ex_cid_fk (course_id),
                  CONSTRAINT ex_cid_fk FOREIGN KEY (course_id) REFERENCES course (course_id)
                );",
                "CREATE TABLE IF NOT EXISTS examform (
                  form_id int NOT NULL,
                  std_id int DEFAULT NULL,
                  exam_id int DEFAULT NULL,
                  submission_date timestamp NULL DEFAULT CURRENT_TIMESTAMP,
                  status enum('Pending','Approved','Rejected') DEFAULT 'Pending',
                  PRIMARY KEY (form_id),
                  KEY ef_sid_fk (std_id),
                  KEY ef_eid_fk (exam_id),
                  CONSTRAINT ef_eid_fk FOREIGN KEY (exam_id) REFERENCES exam (exam_id),
                  CONSTRAINT ef_sid_fk FOREIGN KEY (std_id) REFERENCES student (std_id)
                );",
                "CREATE TABLE IF NOT EXISTS identification (
                  identification_id int NOT NULL AUTO_INCREMENT,
                  std_id int NOT NULL,
                  document_type varchar(50) NOT NULL,
                  identification_number varchar(50) NOT NULL,
                  university varchar(100) NOT NULL,
                  university_registration_number varchar(50) NOT NULL,
                  symbol_num int DEFAULT NULL,
                  PRIMARY KEY (identification_id),
                  KEY std_id (std_id),
                  CONSTRAINT identification_ibfk_1 FOREIGN KEY (std_id) REFERENCES student (std_id)
                );",
                "CREATE TABLE IF NOT EXISTS marks (
                  marks_id int NOT NULL,
                  std_id int DEFAULT NULL,
                  course_id int DEFAULT NULL,
                  marks_obtained decimal(5,2) DEFAULT NULL,
                  exam_id int DEFAULT NULL,
                  PRIMARY KEY (marks_id),
                  KEY ma_sid_fk (std_id),
                  KEY ma_cid_fk (course_id),
                  KEY ma_eid_fk (exam_id),
                  CONSTRAINT ma_cid_fk FOREIGN KEY (course_id) REFERENCES course (course_id),
                  CONSTRAINT ma_eid_fk FOREIGN KEY (exam_id) REFERENCES exam (exam_id),
                  CONSTRAINT ma_sid_fk FOREIGN KEY (std_id) REFERENCES student (std_id)
                );",
                "CREATE TABLE IF NOT EXISTS notification (
                  notification_id int NOT NULL,
                  message text NOT NULL,
                  created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP,
                  std_id int DEFAULT NULL,
                  admin_id int DEFAULT NULL,
                  status enum('read','unread') DEFAULT 'unread',
                  PRIMARY KEY (notification_id),
                  KEY nt_sid_fk (std_id),
                  KEY nt_aid_fk (admin_id),
                  CONSTRAINT nt_aid_fk FOREIGN KEY (admin_id) REFERENCES admin (admin_id),
                  CONSTRAINT nt_sid_fk FOREIGN KEY (std_id) REFERENCES student (std_id)
                );",
                "CREATE TABLE IF NOT EXISTS password_reset_requests (
                  reset_id int NOT NULL AUTO_INCREMENT,
                  email varchar(255) NOT NULL,
                  token varchar(255) NOT NULL,
                  created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP,
                  PRIMARY KEY (reset_id),
                  KEY email (email),
                  CONSTRAINT password_reset_requests_ibfk_1 FOREIGN KEY (email) REFERENCES student (email)
                );",
                "CREATE TABLE IF NOT EXISTS user (
                  user_id int NOT NULL,
                  password varchar(255) NOT NULL,
                  is_2fa_enabled enum('off','on') DEFAULT 'off',
                  PRIMARY KEY (user_id),
                  CONSTRAINT us_uid_fk FOREIGN KEY (user_id) REFERENCES student (std_id)
                );"
            ];

            foreach ($tables as $sql) {
                try {
                    $pdo->exec($sql);
                    echo "Table created or already exists.<br>";
                } catch (PDOException $e) {
                    echo "Error creating table: " . $e->getMessage() . "<br>";
                }
            }

?>