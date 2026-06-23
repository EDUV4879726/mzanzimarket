<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__.'/config.php';
function isLoggedIn() { return isset($_SESSION['user_id']); }
function userRole() { return $_SESSION['role'] ?? 'guest'; }
function requireLogin() { if (!isLoggedIn()) { header('Location: '.BASE_URL.'/login.php'); exit; } }
function requireRole($role) { requireLogin(); if (userRole() !== $role) { header('Location: '.BASE_URL.'/index.php'); exit; } }
?>