<?php
include 'db_connection.php';

$fname = $_POST['full_name'];
$dob = $_POST['dob'];
$program = (int)$_POST['program'];
$enroll_year =(int) $_POST['enroll_year'];
$phone = $_POST['phone'];
$personal_email = $_POST['personal_email'];
$email = $_POST['email'];
$id_num = (int) $_POST['id_num'];
$password = $_POST['password'];

try{
    $stmt= $pdo->prepare("select * from student where  std_id = :id_num and name= :fname and program_id = :program and enrollment_year= :enroll_year and email = :email");

    $stmt->bindParam(':id_num',$id_num);
    $stmt->bindParam(':fname',$fname);
    $stmt->bindParam(':program',$program);
    $stmt->bindParam(':enroll_year',$enroll_year);
    $stmt->bindParam(':email',$email);

    $stmt->execute();

    if($stmt->rowCount()>0){
        $stmt=$pdo->prepare("select * from user where user_id = :id_num");
        $stmt->bindParam(':id_num',$id_num);
        $stmt->execute();
        if($stmt->rowCount()>0){
            echo "user already exists";
            exit();
        }else{
            $pass_hash = password_hash($password,PASSWORD_DEFAULT);
            $sql = $pdo->prepare( "insert into user(user_id,password) values(:id_num,:pass)");
            $sql->bindParam(':id_num',$id_num);
            $sql->bindParam(':pass',$pass_hash);
            $sql->execute();

           
            $update_dob = $pdo->prepare("update student set dob = :dob where std_id = :id_num");
            $update_dob->bindParam(':dob',$dob);
            $update_dob->bindParam(':id_num',$id_num);
            $update_dob->execute();
            if(!empty($phone)){
                $update_phone = $pdo->prepare("update student set contact_number = :phone where std_id = :id_num ");
                $update_phone->bindParam(':phone',$phone);
                $update_phone->bindParam(':id_num',$id_num);
                $update_phone->execute();
            }

            if(!empty($personal_email)){
                $update_pEmail = $pdo->prepare("update student set personal_email = :pEmail where std_id = :id_num ");
                $update_pEmail->bindParam(':pEmail',$personal_email);
                $update_pEmail->bindParam(':id_num',$id_num);
                $update_pEmail->execute();
            }

            echo 'Registration successful.';
        }
    }else{
        echo 'Student data is not valid, please enter correct data';
    }
}catch(PDOException $e){
    echo 'Error: '.$e->getMessage();
}