<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
function isLoggedIn() { return isset($_SESSION['user_id']); }
function userRole() { return $_SESSION['role'] ?? 'guest'; }
function requireLogin() { if (!isLoggedIn()) { header('Location: /MzanziMarket_Project/login.php'); exit; } }
function requireRole($role) { requireLogin(); if (userRole() !== $role) { header('Location: /MzanziMarket_Project/index.php'); exit; } }
?>
