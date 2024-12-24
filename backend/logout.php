<?php
session_start();

    session_destroy();
    header('Location: ../authentication/sign-in.php');
    
