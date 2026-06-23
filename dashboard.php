<?php
require_once '../includes/auth.php';
requireRole('seller');
include '../includes/db.php';
include '../includes/header.php';

$stmt = $pdo->prepare('SELECT p.*, c.category_name FROM products p LEFT JOIN categories c ON p.category_id = c.category_id WHERE p.seller_id = ? ORDER BY p.created_at DESC');
$stmt->execute([$_SESSION['user_id']]);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total = count($products);
$approved = count(array_filter($products, fn($p) => $p['status'] === 'approved'));
$pending = count(array_filter($products, fn($p) => $p['status'] === 'pending'));
$rejected = count(array_filter($products, fn($p) => $p['status'] === 'rejected'));
?>
<h2>Seller Dashboard</h2>

<div class="row text-center mb-4">
    <div class="col-md-3">
        <div class="dashboard-box">
            <h3><?php echo $total; ?></h3>
            <p class="mb-0">Total Products</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="dashboard-box">
            <h3 class="text-success"><?php echo $approved; ?></h3>
            <p class="mb-0">Approved</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="dashboard-box">
            <h3 class="text-warning"><?php echo $pending; ?></h3>
            <p class="mb-0">Pending</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="dashboard-box">
            <h3 class="text-danger"><?php echo $rejected; ?></h3>
            <p class="mb-0">Rejected</p>
        </div>
    </div>
</div>

<a href="add_product.php" class="btn btn-primary mb-3">+ Add New Product</a>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Image</th><th>Name</th><th>Category</th><th>Price</th><th>Status</th><th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $p): ?>
                    <tr>
                        <td>
                            <img src="../uploads/<?php echo htmlspecialchars($p['image']); ?>" style="width:60px;height:50px;object-fit:cover;border-radius:6px;" onerror="this.src='https://via.placeholder.com/60x50?text=No+Image'">
                        </td>
                        <td><?php echo htmlspecialchars($p['product_name']); ?></td>
                        <td><?php echo htmlspecialchars($p['category_name'] ?? 'Uncategorized'); ?></td>
                        <td>R<?php echo number_format($p['price'], 2); ?></td>
                        <td>
                            <span class="badge bg-<?php echo $p['status'] === 'approved' ? 'success' : ($p['status'] === 'rejected' ? 'danger' : 'warning'); ?>">
                                <?php echo ucfirst($p['status']); ?>
                            </span>
                        </td>
                        <td>
                            <a href="edit_product.php?id=<?php echo $p['product_id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php include '../includes/footer.php'; ?>