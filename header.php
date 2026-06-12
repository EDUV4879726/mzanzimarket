<?php require_once __DIR__.'/auth.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>MzanziMarket</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="/MzanziMarket_Project/assets/css/style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand fw-bold" href="/MzanziMarket_Project/index.php">MzanziMarket</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav"><span class="navbar-toggler-icon"></span></button>
    <div class="collapse navbar-collapse" id="nav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="/MzanziMarket_Project/products.php">Products</a></li>
        <?php if (isLoggedIn()): ?>
          <?php if (userRole()==='seller'): ?><li class="nav-item"><a class="nav-link" href="/MzanziMarket_Project/seller/dashboard.php">Seller Dashboard</a></li><?php endif; ?>
          <?php if (userRole()==='admin'): ?><li class="nav-item"><a class="nav-link" href="/MzanziMarket_Project/admin/dashboard.php">Admin</a></li><?php endif; ?>
          <li class="nav-item"><a class="nav-link" href="/MzanziMarket_Project/logout.php">Logout</a></li>
        <?php else: ?>
          <li class="nav-item"><a class="nav-link" href="/MzanziMarket_Project/login.php">Login</a></li>
          <li class="nav-item"><a class="nav-link" href="/MzanziMarket_Project/register.php">Register</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
<main class="container py-4">
