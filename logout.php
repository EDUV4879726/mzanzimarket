<?php 
session_start(); 
session_destroy(); 
require_once 'includes/config.php';
header('Location: '.BASE_URL.'/login.php'); 
exit; 
?>