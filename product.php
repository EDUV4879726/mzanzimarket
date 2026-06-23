<?php
require_once 'includes/db.php';
include 'includes/header.php';

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT p.*, u.full_name, u.user_id AS seller, u.phone AS seller_phone, c.category_name FROM products p JOIN users u ON p.seller_id = u.user_id LEFT JOIN categories c ON p.category_id = c.category_id WHERE p.product_id = ? AND p.status = 'approved'");
$stmt->execute([$id]);
$p = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$p) {
    echo '<div class="alert alert-warning">Product not found or not yet approved.</div>';
    include 'includes/footer.php';
    exit;
}

$related = $pdo->prepare('SELECT * FROM products WHERE category_id = ? AND product_id != ? AND status = "approved" LIMIT 3');
$related->execute([$p['category_id'], $id]);
$related = $related->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="row">
    <div class="col-md-6">
        <div class="card border-0">
            <img src="<?php echo BASE_URL; ?>/uploads/<?php echo htmlspecialchars($p['image']); ?>" class="img-fluid rounded shadow" style="max-height:450px;width:100%;object-fit:cover;" onerror="this.src='https://via.placeholder.com/600x450?text=MzanziMarket'">
        </div>
    </div>
    <div class="col-md-6">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>/products.php">Products</a></li>
                <li class="breadcrumb-item active"><?php echo htmlspecialchars($p['product_name']); ?></li>
            </ol>
        </nav>
        <h2 class="mb-2"><?php echo htmlspecialchars($p['product_name']); ?></h2>
        <p class="text-muted mb-3">
            <span class="badge bg-secondary"><?php echo htmlspecialchars($p['category_name'] ?? 'Uncategorized'); ?></span>
        </p>
        <h3 class="text-success mb-3">R<?php echo number_format($p['price'], 2); ?></h3>
        <p class="lead"><?php echo nl2br(htmlspecialchars($p['description'])); ?></p>
        
        <div class="card bg-light mb-4">
            <div class="card-body">
                <h6 class="mb-2">Seller Information</h6>
                <p class="mb-1"><strong>Name:</strong> <?php echo htmlspecialchars($p['full_name']); ?></p>
                <p class="mb-0"><strong>Contact:</strong> <?php echo htmlspecialchars($p['seller_phone'] ?? 'Not provided'); ?></p>
            </div>
        </div>
        
        <?php if (isLoggedIn() && userRole() === 'buyer'): ?>
            <div class="d-flex gap-2">
                <a href="<?php echo BASE_URL; ?>/cart.php?add=<?php echo $p['product_id']; ?>" class="btn btn-success btn-lg flex-fill">
                    <i class="bi bi-cart-plus me-2"></i> Add to Cart
                </a>
                <a href="<?php echo BASE_URL; ?>/cart.php" class="btn btn-outline-primary btn-lg">View Cart</a>
            </div>
        <?php elseif (isLoggedIn() && userRole() === 'seller'): ?>
            <div class="alert alert-info">You are logged in as a seller. <a href="<?php echo BASE_URL; ?>/logout.php">Switch to a buyer account</a> to purchase.</div>
        <?php else: ?>
            <div class="d-flex gap-2">
                <a href="<?php echo BASE_URL; ?>/login.php" class="btn btn-success btn-lg flex-fill">Login to Buy</a>
                <a href="<?php echo BASE_URL; ?>/register.php" class="btn btn-outline-primary btn-lg">Register</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php if (!empty($related)): ?>
<hr class="my-5">
<h4 class="mb-4">Related Products</h4>
<div class="row">
    <?php foreach ($related as $r): ?>
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <img src="<?php echo BASE_URL; ?>/uploads/<?php echo htmlspecialchars($r['image']); ?>" class="card-img-top product-img" onerror="this.src='https://via.placeholder.com/400x250?text=MzanziMarket'">
            <div class="card-body">
                <h5><?php echo htmlspecialchars($r['product_name']); ?></h5>
                <p class="fw-bold text-success">R<?php echo number_format($r['price'], 2); ?></p>
                <a class="btn btn-primary btn-sm" href="<?php echo BASE_URL; ?>/product.php?id=<?php echo $r['product_id']; ?>">View Product</a>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
