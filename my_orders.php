<?php
require_once 'includes/auth.php';
requireRole('buyer');
require_once 'includes/db.php';
include 'includes/header.php';

$orders = $pdo->prepare('SELECT o.*, p.product_name, p.image, p.description, u.full_name AS seller_name FROM orders o JOIN products p ON o.product_id = p.product_id JOIN users u ON p.seller_id = u.user_id WHERE o.buyer_id = ? ORDER BY o.created_at DESC');
$orders->execute([$_SESSION['user_id']]);
$orders = $orders->fetchAll(PDO::FETCH_ASSOC);
?>
<h2>My Orders</h2>

<?php if (empty($orders)): ?>
<div class="alert alert-info text-center py-5">
    <h4>No orders yet</h4>
    <p>You haven't placed any orders. Start shopping now!</p>
    <a href="<?php echo BASE_URL; ?>/products.php" class="btn btn-primary">Browse Products</a>
</div>
<?php else: ?>
<div class="row">
    <?php foreach ($orders as $o): ?>
    <div class="col-md-6 col-lg-4 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="fw-bold">Order #<?php echo $o['order_id']; ?></span>
                <span class="badge bg-<?php echo $o['order_status'] === 'completed' ? 'success' : ($o['order_status'] === 'cancelled' ? 'danger' : ($o['order_status'] === 'paid' ? 'info' : 'warning')); ?>">
                    <?php echo ucfirst($o['order_status']); ?>
                </span>
            </div>
            <img src="<?php echo BASE_URL; ?>/uploads/<?php echo htmlspecialchars($o['image']); ?>" class="card-img-top" style="height:180px;object-fit:cover;" onerror="this.src='https://via.placeholder.com/400x180?text=No+Image'">
            <div class="card-body">
                <h5 class="card-title"><?php echo htmlspecialchars($o['product_name']); ?></h5>
                <p class="card-text small text-muted"><?php echo htmlspecialchars(substr($o['description'], 0, 80)); ?><?php echo strlen($o['description']) > 80 ? '...' : ''; ?></p>
                <p class="mb-1"><strong>Quantity:</strong> <?php echo $o['quantity']; ?></p>
                <p class="mb-1"><strong>Total:</strong> R<?php echo number_format($o['total_amount'], 2); ?></p>
                <p class="mb-1"><strong>Seller:</strong> <?php echo htmlspecialchars($o['seller_name']); ?></p>
                <p class="small text-muted mb-0">Ordered: <?php echo date('d M Y H:i', strtotime($o['created_at'])); ?></p>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<div class="mt-3">
    <a href="<?php echo BASE_URL; ?>/products.php" class="btn btn-primary">Continue Shopping</a>
</div>

<?php include 'includes/footer.php'; ?>