<?php
require_once '../includes/auth.php';
requireRole('admin');
include '../includes/db.php';
include '../includes/header.php';

$message = '';

if (isset($_GET['approve'])) {
    $pdo->prepare('UPDATE products SET status = "approved" WHERE product_id = ?')->execute([$_GET['approve']]);
    $message = 'Product approved.';
}
if (isset($_GET['reject'])) {
    $pdo->prepare('UPDATE products SET status = "rejected" WHERE product_id = ?')->execute([$_GET['reject']]);
    $message = 'Product rejected.';
}
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $img = $pdo->prepare('SELECT image FROM products WHERE product_id = ?');
    $img->execute([$id]);
    $imgName = $img->fetchColumn();
    if ($imgName && $imgName !== 'default-product.jpg' && file_exists('../uploads/' . $imgName)) {
        unlink('../uploads/' . $imgName);
    }
    $pdo->prepare('DELETE FROM products WHERE product_id = ?')->execute([$id]);
    $message = 'Product deleted.';
}

$products = $pdo->query('SELECT p.*, u.full_name, c.category_name FROM products p JOIN users u ON p.seller_id = u.user_id LEFT JOIN categories c ON p.category_id = c.category_id ORDER BY p.created_at DESC')->fetchAll(PDO::FETCH_ASSOC);
?>
<h2>Manage Products</h2>
<?php if ($message): ?>
<div class="alert alert-info"><?php echo htmlspecialchars($message); ?></div>
<?php endif; ?>
<div class="row">
    <?php foreach ($products as $p): ?>
    <div class="col-md-6 col-lg-4 mb-4">
        <div class="card h-100">
            <img src="../uploads/<?php echo htmlspecialchars($p['image']); ?>" class="card-img-top" style="height:180px;object-fit:cover;" onerror="this.src='https://via.placeholder.com/400x180?text=No+Image'">
            <div class="card-body">
                <h5 class="card-title"><?php echo htmlspecialchars($p['product_name']); ?></h5>
                <p class="card-text small text-muted"><?php echo htmlspecialchars(substr($p['description'], 0, 80)); ?><?php echo strlen($p['description']) > 80 ? '...' : ''; ?></p>
                <p class="mb-1"><strong>R<?php echo number_format($p['price'], 2); ?></strong></p>
                <p class="mb-1 small">Seller: <?php echo htmlspecialchars($p['full_name']); ?></p>
                <p class="mb-1 small">Category: <?php echo htmlspecialchars($p['category_name'] ?? 'Uncategorized'); ?></p>
                <p class="mb-2">
                    <span class="badge bg-<?php echo $p['status'] === 'approved' ? 'success' : ($p['status'] === 'rejected' ? 'danger' : 'warning'); ?>">
                        <?php echo ucfirst($p['status']); ?>
                    </span>
                </p>
                <div class="d-flex gap-2 flex-wrap">
                    <a class="btn btn-sm btn-success" href="?approve=<?php echo $p['product_id']; ?>">Approve</a>
                    <a class="btn btn-sm btn-danger" href="?reject=<?php echo $p['product_id']; ?>">Decline</a>
                    <a class="btn btn-sm btn-outline-danger" href="?delete=<?php echo $p['product_id']; ?>" onclick="return confirmAction('Delete this product permanently?')">Delete</a>
                </div>
            </div>
            <div class="card-footer text-muted small">
                Submitted: <?php echo date('d M Y', strtotime($p['created_at'])); ?>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<div class="mt-3">
    <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
</div>
<?php include '../includes/footer.php'; ?>