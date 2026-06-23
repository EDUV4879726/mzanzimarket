<?php
require_once '../includes/auth.php';
requireRole('admin');
include '../includes/db.php';
include '../includes/header.php';

$users = $pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
$products = $pdo->query('SELECT COUNT(*) FROM products')->fetchColumn();
$orders = $pdo->query('SELECT COUNT(*) FROM orders')->fetchColumn();
$pendingProducts = $pdo->query('SELECT COUNT(*) FROM products WHERE status = "pending"')->fetchColumn();
$pendingOrders = $pdo->query('SELECT COUNT(*) FROM orders WHERE order_status = "pending"')->fetchColumn();
?>
<h2>Admin Dashboard</h2>
<div class="row text-center mb-4">
  <div class="col-md-3">
    <div class="dashboard-box">
      <h3><?php echo $users; ?></h3>
      <p class="mb-0">Total Users</p>
    </div>
  </div>
  <div class="col-md-3">
    <div class="dashboard-box">
      <h3><?php echo $products; ?></h3>
      <p class="mb-0">Total Products</p>
    </div>
  </div>
  <div class="col-md-3">
    <div class="dashboard-box">
      <h3><?php echo $orders; ?></h3>
      <p class="mb-0">Total Orders</p>
    </div>
  </div>
  <div class="col-md-3">
    <div class="dashboard-box" style="border-left:4px solid #ffc107;">
      <h3 class="text-warning"><?php echo $pendingProducts; ?></h3>
      <p class="mb-0">Pending Products</p>
    </div>
  </div>
</div>

<div class="row g-3">
  <div class="col-md-4">
    <div class="card h-100 text-center p-4">
      <i class="bi bi-people fs-1 text-primary mb-2"></i>
      <h5>Manage Users</h5>
      <p class="text-muted small">View, verify, and delete user accounts.</p>
      <a href="users.php" class="btn btn-primary">Go to Users</a>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card h-100 text-center p-4">
      <i class="bi bi-box-seam fs-1 text-success mb-2"></i>
      <h5>Manage Products</h5>
      <p class="text-muted small">Approve, decline, or delete product listings. <?php echo $pendingProducts > 0 ? "<span class='badge bg-warning text-dark'>$pendingProducts pending</span>" : ''; ?></p>
      <a href="products.php" class="btn btn-success">Go to Products</a>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card h-100 text-center p-4">
      <i class="bi bi-receipt fs-1 text-secondary mb-2"></i>
      <h5>View Orders</h5>
      <p class="text-muted small">Track and update order statuses. <?php echo $pendingOrders > 0 ? "<span class='badge bg-warning text-dark'>$pendingOrders pending</span>" : ''; ?></p>
      <a href="orders.php" class="btn btn-secondary">Go to Orders</a>
    </div>
  </div>
</div>
<?php include '../includes/footer.php'; ?>