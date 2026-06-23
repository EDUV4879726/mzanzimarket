<?php
require_once '../includes/auth.php';
requireRole('seller');
include '../includes/db.php';
include '../includes/header.php';

$cats = $pdo->query('SELECT * FROM categories')->fetchAll(PDO::FETCH_ASSOC);
$msg = '';
$msgType = 'success';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productName = trim($_POST['product_name']);
    $description = trim($_POST['description']);
    $price = (float)$_POST['price'];
    $categoryId = (int)$_POST['category_id'];
    
    $imageName = 'default-product.jpg';
    
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        $fileExt = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        if (in_array($fileExt, $allowed)) {
            $imageName = uniqid('product_') . '.' . $fileExt;
            $targetPath = $uploadDir . $imageName;
            
            if (!move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                $msg = 'Failed to upload image. Product saved with default image.';
                $msgType = 'warning';
                $imageName = 'default-product.jpg';
            }
        } else {
            $msg = 'Invalid image type. Only JPG, PNG, GIF, WEBP allowed. Product saved with default image.';
            $msgType = 'warning';
        }
    }
    
    try {
        $stmt = $pdo->prepare('INSERT INTO products(seller_id, category_id, product_name, description, price, image, status) VALUES(?, ?, ?, ?, ?, ?, "pending")');
        $stmt->execute([$_SESSION['user_id'], $categoryId, $productName, $description, $price, $imageName]);
        
        if ($msg === '') {
            $msg = 'Product submitted for admin approval.';
        }
    } catch (PDOException $e) {
        $msg = 'Error saving product: ' . $e->getMessage();
        $msgType = 'danger';
    }
}
?>
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card p-4">
            <h2>Add New Product</h2>
            <?php if ($msg): ?>
            <div class="alert alert-<?php echo $msgType; ?>"><?php echo htmlspecialchars($msg); ?></div>
            <?php endif; ?>
            <form method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                <div class="mb-3">
                    <label class="form-label">Product Name</label>
                    <input class="form-control" name="product_name" placeholder="e.g. Handmade Beaded Necklace" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea class="form-control" name="description" rows="3" placeholder="Describe your product..."></textarea>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Price (R)</label>
                        <input class="form-control" type="number" step="0.01" min="0" name="price" placeholder="0.00" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Category</label>
                        <select class="form-select" name="category_id" required>
                            <?php foreach ($cats as $c): ?>
                            <option value="<?php echo $c['category_id']; ?>"><?php echo htmlspecialchars($c['category_name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Product Image</label>
                    <input class="form-control" type="file" name="image" accept="image/*" id="imageInput">
                    <div class="form-text">JPG, PNG, GIF, WEBP accepted. Max 5MB.</div>
                    <div class="mt-2">
                        <img id="imagePreview" src="" style="max-height:200px;display:none;" class="img-fluid rounded border">
                    </div>
                </div>
                <button class="btn btn-primary w-100">Save Product</button>
            </form>
            <a href="dashboard.php" class="btn btn-secondary w-100 mt-2">Cancel</a>
        </div>
    </div>
</div>

<script>
document.getElementById('imageInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('imagePreview');
            preview.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
});
</script>

<?php include '../includes/footer.php'; ?>