<?php
require_once 'includes/db.php';
include 'includes/header.php';

$search = $_GET['search'] ?? '';
$category = $_GET['category'] ?? '';

$sql = "SELECT p.*, u.full_name, c.category_name FROM products p JOIN users u ON p.seller_id = u.user_id LEFT JOIN categories c ON p.category_id = c.category_id WHERE p.status = 'approved'";
$params = [];

if ($search) {
    $sql .= " AND (p.product_name LIKE ? OR p.description LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}
if ($category) {
    $sql .= " AND p.category_id = ?";
    $params[] = $category;
}

$sql .= " ORDER BY p.created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

$categories = $pdo->query('SELECT * FROM categories')->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="row mb-4">
    <div class="col-md-8">
        <h2>Approved Products</h2>
        <p class="text-muted"><?php echo count($products); ?> product<?php echo count($products) !== 1 ? 's' : ''; ?> available</p>
    </div>
    <div class="col-md-4">
        <form method="get" class="d-flex gap-2">
            <input type="text" name="search" class="form-control" placeholder="Search products..." value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit" class="btn btn-primary">Search</button>
        </form>
    </div>
</div>

<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex gap-2 flex-wrap">
            <a href="<?php echo BASE_URL; ?>/products.php" class="btn btn-sm <?php echo $category === '' ? 'btn-dark' : 'btn-outline-dark'; ?>">All</a>
            <?php foreach ($categories as $c): ?>
            <a href="<?php echo BASE_URL; ?>/products.php?category=<?php echo $c['category_id']; ?>" class="btn btn-sm <?php echo $category == $c['category_id'] ? 'btn-dark' : 'btn-outline-dark'; ?>">
                <?php echo htmlspecialchars($c['category_name']); ?>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php if (empty($products)): ?>
<div class="alert alert-info text-center py-5">
    <h4>No products found</h4>
    <p>Try adjusting your search or browse all categories.</p>
</div>
<?php else: ?>
<div class="row">
    <?php foreach ($products as $p): ?>
    <div class="col-md-4 col-lg-3 mb-4">
        <div class="card h-100">
            <img src="<?php echo BASE_URL; ?>/uploads/<?php echo htmlspecialchars($p['image']); ?>" class="card-img-top product-img" onerror="this.src='https://via.placeholder.com/400x250?text=MzanziMarket'">
            <div class="card-body d-flex flex-column">
                <h5 class="card-title"><?php echo htmlspecialchars($p['product_name']); ?></h5>
                <p class="card-text small text-muted flex-grow-1"><?php echo htmlspecialchars(substr($p['description'], 0, 60)); ?><?php echo strlen($p['description']) > 60 ? '...' : ''; ?></p>
                <p class="fw-bold text-success mb-1">R<?php echo number_format($p['price'], 2); ?></p>
                <p class="small text-muted mb-2">Seller: <?php echo htmlspecialchars($p['full_name']); ?></p>
                <div class="d-flex gap-2">
                    <a class="btn btn-primary btn-sm flex-fill" href="<?php echo BASE_URL; ?>/product.php?id=<?php echo $p['product_id']; ?>">View</a>
                    <?php if (isLoggedIn() && userRole() === 'buyer'): ?>
                    <a class="btn btn-success btn-sm" href="<?php echo BASE_URL; ?>/cart.php?add=<?php echo $p['product_id']; ?>">+ Cart</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>