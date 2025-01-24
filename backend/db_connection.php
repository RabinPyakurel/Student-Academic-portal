<?php
require_once '../secret.php';
try{
    $pdo = new PDO("mysql:host=localhost",'root',$pass);
    $pdo->exec("use sapo");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch(PDOException $e){
    echo 'connection failed: '.$e->getMessage();
    exit();
}
