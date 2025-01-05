<?php
try{
    $pdo = new PDO("mysql:host=localhost",'root','rabin');
    $pdo->exec("create database IF NOT EXISTS sapo_test");
    $pdo->exec("use sapo_test");
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
                semester varchar(20) NOT NULL,
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
                  password varchar(255) DEFAULT NULL,
                  PRIMARY KEY (admin_id),
                  UNIQUE KEY ad_username_un (username)
            );",
                "CREATE TABLE IF NOT EXISTS library (
                  book_id int NOT NULL,
                  title varchar(100) NOT NULL,
                  author varchar(100) DEFAULT NULL,
                  publisher varchar(100) DEFAULT NULL,
                  year_published int DEFAULT NULL,
                  available_copies int DEFAULT NULL,
                  total_copies int DEFAULT NULL,
                  book_image varchar(200),
                  PRIMARY KEY (book_id)
            );",
                "CREATE TABLE IF NOT EXISTS attendance (
                attendance_id int NOT NULL AUTO_INCREMENT,
                std_id int DEFAULT NULL,
                semester varchar(20) DEFAULT NULL,
                date date NOT NULL,
                status enum('Present','Absent','Late') NOT NULL,
                remarks text,
                PRIMARY KEY (attendance_id),
                KEY std_id (std_id),
                CONSTRAINT attendance_ibfk_1 FOREIGN KEY (std_id) REFERENCES student (std_id)
            );",
                "CREATE TABLE IF NOT EXISTS billing (
                  billing_id INT AUTO_INCREMENT PRIMARY KEY,
                  std_id INT NOT NULL,
                  billing_date date,
                  semester VARCHAR(50) NOT NULL,
                  total_fee DECIMAL(10, 2),
                  amount_paid DECIMAL(10, 2) DEFAULT 0.00,
                  payment_status VARCHAR(20) DEFAULT 'Unpaid',
                  payment_method VARCHAR(50) DEFAULT NULL,
                  payment_date DATE DEFAULT NULL,
                  FOREIGN KEY (std_id) REFERENCES student(std_id)
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
                  course_id varchar(20) NOT NULL,
                  course_name varchar(100) NOT NULL,
                  program_id int DEFAULT NULL,
                  semester varchar(20) DEFAULT NULL,
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
                  event_image VARCHAR(255),
                  PRIMARY KEY (event_id),
                  KEY evt_cb_fk (created_by),
                  CONSTRAINT evt_cb_fk FOREIGN KEY (created_by) REFERENCES admin (admin_id)
                );",
                "CREATE TABLE IF NOT EXISTS exam (
                  exam_id int NOT NULL,
                  semester varchar(20) DEFAULT NULL,
                  program_id int DEFAULT NULL,
                  exam_name varchar(100) NOT NULL,
                  exam_date date DEFAULT NULL,
                  course_id varchar(20) DEFAULT NULL,
                  PRIMARY KEY (exam_id),
                  KEY ex_pid_fk (program_id),
                  KEY ex_cid_fk (course_id),
                  CONSTRAINT ex_pid_fk FOREIGN KEY (program_id) REFERENCES program (program_id),
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
                  marks_id int NOT NULL AUTO_INCREMENT,
                  std_id int DEFAULT NULL,
                  course_id varchar(20) DEFAULT NULL,
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
                  notification_id INT NOT NULL AUTO_INCREMENT,
                  message TEXT NOT NULL,
                  link VARCHAR(255) NOT NULL,
                  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                  expires_at TIMESTAMP NULL, 
                  std_id INT DEFAULT NULL, 
                  semester VARCHAR(20) DEFAULT NULL,
                  admin_id INT DEFAULT NULL,
                  status ENUM('read', 'unread') DEFAULT 'unread',
                  is_seen ENUM('yes', 'no') DEFAULT 'no',
                  PRIMARY KEY (notification_id),
                  KEY nt_sid_fk (std_id),
                  KEY nt_semester_idx (semester),
                  KEY nt_aid_fk (admin_id),
                  CONSTRAINT nt_sid_fk FOREIGN KEY (std_id) REFERENCES student (std_id),
                  CONSTRAINT nt_aid_fk FOREIGN KEY (admin_id) REFERENCES admin (admin_id)
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
                );",
                "CREATE TRIGGER billing_insert_trigger
                  BEFORE INSERT ON billing
                  FOR EACH ROW
                  BEGIN
                      SET NEW.billing_date = CURDATE();
                      IF NEW.total_fee IS NULL THEN
                          SET NEW.payment_status = 'No Fee';
                      ELSEIF NEW.amount_paid IS NULL OR NEW.amount_paid = 0 THEN
                          SET NEW.payment_status = 'Unpaid';
                      ELSEIF NEW.amount_paid < NEW.total_fee THEN
                          SET NEW.payment_status = 'Partially Paid';
                      ELSEIF NEW.amount_paid >= NEW.total_fee THEN
                          SET NEW.payment_status = 'Paid';
                      END IF;
                  END;",
              "CREATE TRIGGER billing_update_trigger
                BEFORE UPDATE ON billing
                FOR EACH ROW
                BEGIN
                    IF NEW.total_fee IS NULL THEN
                        SET NEW.payment_status = 'No Fee';
                    ELSEIF NEW.amount_paid IS NULL OR NEW.amount_paid = 0 THEN
                        SET NEW.payment_status = 'Unpaid';
                    ELSEIF NEW.amount_paid < NEW.total_fee THEN
                        SET NEW.payment_status = 'Partially Paid';
                    ELSEIF NEW.amount_paid >= NEW.total_fee THEN
                        SET NEW.payment_status = 'Paid';
                        SET NEW.payment_date = CURRENT_TIMESTAMP;
                    END IF;
                END;",
               "CREATE TRIGGER after_attendance_insert
                AFTER INSERT ON attendance
                FOR EACH ROW
                BEGIN
                    INSERT INTO notification (message, link, std_id, semester, expires_at)
                    VALUES (
                        CONCAT('Attendance updated for Student ID: ', NEW.std_id, ' on ', NEW.date),
                        'attendance.php',
                        NEW.std_id,
                        NEW.semester,
                        NOW() + INTERVAL 7 DAY
                    );
                END;",
                "CREATE TRIGGER after_marks_insert
                  AFTER INSERT ON marks
                  FOR EACH ROW
                  BEGIN
                      INSERT INTO notification (message, link, std_id, semester, expires_at)
                      VALUES (
                          CONCAT('Marks updated for Student ID: ', NEW.std_id),
                          'marks.php',
                          NEW.std_id,
                          (SELECT semester FROM student WHERE std_id = NEW.std_id),
                          NOW() + INTERVAL 30 DAY
                      );
                  END;",
                  "CREATE TRIGGER after_billing_insert
                    AFTER INSERT ON billing
                    FOR EACH ROW
                    BEGIN
                        INSERT INTO notification (message, link, std_id, semester, expires_at)
                        VALUES (
                            CONCAT('Billing update for Semester: ', NEW.semester),
                            'billing.php',
                            NEW.std_id,
                            NEW.semester,
                            NOW() + INTERVAL 14 DAY
                        );
                    END;",
                  "CREATE TRIGGER after_exam_insert
                    AFTER INSERT ON exam
                    FOR EACH ROW
                    BEGIN
                        INSERT INTO notification (message, link, semester, expires_at)
                        VALUES (
                            CONCAT('New Exam: ', NEW.exam_name, ' scheduled on ', NEW.exam_date),
                            'exam.php',
                            (SELECT semester FROM course WHERE course_id = NEW.course_id),
                            NOW() + INTERVAL 7 DAY
                        );
                    END;",
                    "CREATE TRIGGER after_event_insert
                      AFTER INSERT ON event
                      FOR EACH ROW
                      BEGIN
                          INSERT INTO notification (message, link, semester, admin_id, expires_at)
                          VALUES (
                              CONCAT('New Event: ', NEW.event_name, ' scheduled on ', NEW.event_date),
                              'event.php',
                              NULL, 
                              NEW.created_by,
                              NOW() + INTERVAL 14 DAY
                          );
                      END;"];

            foreach ($tables as $sql) {
                try {
                    $pdo->exec($sql);
                    echo "Table created or already exists.<br>";
                } catch (PDOException $e) {
                    echo "Error creating table: " . $e->getMessage() . "<br>";
                }
            }

?>