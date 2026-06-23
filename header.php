<?php 
require_once __DIR__.'/auth.php'; 
require_once __DIR__.'/config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>MzanziMarket</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand fw-bold" href="<?php echo BASE_URL; ?>/index.php">MzanziMarket</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav"><span class="navbar-toggler-icon"></span></button>
    <div class="collapse navbar-collapse" id="nav">
      <ul class="navbar-nav me-auto">
        <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>/products.php">Products</a></li>
        <?php if (isLoggedIn() && userRole()==='buyer'): ?>
          <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>/my_orders.php">My Orders</a></li>
        <?php endif; ?>
        <?php if (isLoggedIn() && userRole()==='seller'): ?>
          <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>/seller/dashboard.php">Seller Dashboard</a></li>
        <?php endif; ?>
        <?php if (isLoggedIn() && userRole()==='admin'): ?>
          <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>/admin/dashboard.php">Admin</a></li>
        <?php endif; ?>
      </ul>
      <ul class="navbar-nav ms-auto align-items-center">
        <?php if (isLoggedIn() && userRole()==='buyer'): ?>
          <li class="nav-item me-3">
            <a class="nav-link position-relative" href="<?php echo BASE_URL; ?>/cart.php">
              <i class="bi bi-cart3 fs-5"></i>
              <?php 
              $cartCount = 0;
              if (isset($_SESSION['cart'])) {
                  foreach ($_SESSION['cart'] as $item) {
                      $cartCount += $item['quantity'];
                  }
              }
              if ($cartCount > 0):
              ?>
              <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size:0.6rem;">
                <?php echo $cartCount; ?>
              </span>
              <?php endif; ?>
            </a>
          </li>
        <?php endif; ?>
        <?php if (isLoggedIn()): ?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
              <i class="bi bi-person-circle me-1"></i><?php echo htmlspecialchars($_SESSION['name'] ?? 'User'); ?>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
              <?php if (userRole()==='buyer'): ?>
                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/my_orders.php"><i class="bi bi-bag me-2"></i>My Orders</a></li>
              <?php endif; ?>
              <?php if (userRole()==='seller'): ?>
                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/seller/dashboard.php"><i class="bi bi-shop me-2"></i>Seller Dashboard</a></li>
              <?php endif; ?>
              <?php if (userRole()==='admin'): ?>
                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/admin/dashboard.php"><i class="bi bi-speedometer2 me-2"></i>Admin Panel</a></li>
              <?php endif; ?>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item text-danger" href="<?php echo BASE_URL; ?>/logout.php"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
            </ul>
          </li>
        <?php else: ?>
          <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>/login.php">Login</a></li>
          <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>/register.php">Register</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
<main class="container py-4">