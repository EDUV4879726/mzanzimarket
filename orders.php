<?php
require_once '../includes/auth.php';
requireRole('admin');
include '../includes/db.php';
include '../includes/header.php';

$message = '';

if (isset($_GET['update']) && isset($_GET['status'])) {
    $valid = ['pending', 'paid', 'completed', 'cancelled'];
    if (in_array($_GET['status'], $valid)) {
        $pdo->prepare('UPDATE orders SET order_status = ? WHERE order_id = ?')->execute([$_GET['status'], $_GET['update']]);
        $message = 'Order status updated to ' . ucfirst($_GET['status']) . '.';
    }
}

$orders = $pdo->query('SELECT o.*, u.full_name AS buyer, p.product_name, s.full_name AS seller_name FROM orders o JOIN users u ON o.buyer_id = u.user_id JOIN products p ON o.product_id = p.product_id JOIN users s ON p.seller_id = s.user_id ORDER BY o.created_at DESC')->fetchAll(PDO::FETCH_ASSOC);
?>
<h2>Manage Orders</h2>
<?php if ($message): ?>
<div class="alert alert-info"><?php echo htmlspecialchars($message); ?></div>
<?php endif; ?>
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Order #</th><th>Buyer</th><th>Seller</th><th>Product</th><th>Qty</th><th>Total</th><th>Status</th><th>Date</th><th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $o): ?>
                    <tr>
                        <td>#<?php echo $o['order_id']; ?></td>
                        <td><?php echo htmlspecialchars($o['buyer']); ?></td>
                        <td><?php echo htmlspecialchars($o['seller_name']); ?></td>
                        <td><?php echo htmlspecialchars($o['product_name']); ?></td>
                        <td><?php echo $o['quantity']; ?></td>
                        <td>R<?php echo number_format($o['total_amount'], 2); ?></td>
                        <td>
                            <span class="badge bg-<?php echo $o['order_status'] === 'completed' ? 'success' : ($o['order_status'] === 'cancelled' ? 'danger' : ($o['order_status'] === 'paid' ? 'info' : 'warning')); ?>">
                                <?php echo ucfirst($o['order_status']); ?>
                            </span>
                        </td>
                        <td><?php echo date('d M Y H:i', strtotime($o['created_at'])); ?></td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown">Update</button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="?update=<?php echo $o['order_id']; ?>&status=pending">Pending</a></li>
                                    <li><a class="dropdown-item" href="?update=<?php echo $o['order_id']; ?>&status=paid">Paid</a></li>
                                    <li><a class="dropdown-item" href="?update=<?php echo $o['order_id']; ?>&status=completed">Completed</a></li>
                                    <li><a class="dropdown-item text-danger" href="?update=<?php echo $o['order_id']; ?>&status=cancelled">Cancelled</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="mt-3">
    <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
</div>
<?php include '../includes/footer.php'; ?>