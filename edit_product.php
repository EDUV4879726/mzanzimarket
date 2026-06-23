<?php
require_once '../includes/auth.php';
requireRole('seller');
include '../includes/db.php';
include '../includes/header.php';

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare('SELECT * FROM products WHERE product_id = ? AND seller_id = ?');
$stmt->execute([$id, $_SESSION['user_id']]);
$p = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$p) {
    echo '<div class="alert alert-danger">Product not found or you do not have permission to edit it.</div>';
    include '../includes/footer.php';
    exit;
}

$msg = '';
$msgType = 'success';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productName = trim($_POST['product_name']);
    $description = trim($_POST['description']);
    $price = (float)$_POST['price'];
    $categoryId = (int)$_POST['category_id'];
    $imageName = $p['image'];
    
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        $fileExt = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        if (in_array($fileExt, $allowed)) {
            if ($imageName !== 'default-product.jpg' && file_exists('../uploads/' . $imageName)) {
                unlink('../uploads/' . $imageName);
            }
            
            $imageName = uniqid('product_') . '.' . $fileExt;
            $targetPath = $uploadDir . $imageName;
            
            if (!move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                $msg = 'Failed to upload new image. Keeping old image.';
                $msgType = 'warning';
                $imageName = $p['image'];
            }
        } else {
            $msg = 'Invalid image type. Keeping old image.';
            $msgType = 'warning';
        }
    }
    
    try {
        $stmt = $pdo->prepare('UPDATE products SET product_name = ?, description = ?, price = ?, category_id = ?, image = ?, status = "pending" WHERE product_id = ? AND seller_id = ?');
        $stmt->execute([$productName, $description, $price, $categoryId, $imageName, $id, $_SESSION['user_id']]);
        
        if ($msg === '') {
            $msg = 'Product updated and sent for admin approval.';
        }
        
        $stmt = $pdo->prepare('SELECT * FROM products WHERE product_id = ? AND seller_id = ?');
        $stmt->execute([$id, $_SESSION['user_id']]);
        $p = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $msg = 'Error updating product: ' . $e->getMessage();
        $msgType = 'danger';
    }
}

$cats = $pdo->query('SELECT * FROM categories')->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card p-4">
            <h2>Edit Product</h2>
            <?php if ($msg): ?>
            <div class="alert alert-<?php echo $msgType; ?>"><?php echo htmlspecialchars($msg); ?></div>
            <?php endif; ?>
            
            <div class="mb-3 text-center">
                <img src="../uploads/<?php echo htmlspecialchars($p['image']); ?>" class="img-fluid rounded border" style="max-height:200px;" onerror="this.src='https://via.placeholder.com/400x200?text=No+Image'">
            </div>
            
            <form method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                <div class="mb-3">
                    <label class="form-label">Product Name</label>
                    <input class="form-control" name="product_name" value="<?php echo htmlspecialchars($p['product_name']); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea class="form-control" name="description" rows="3"><?php echo htmlspecialchars($p['description']); ?></textarea>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Price (R)</label>
                        <input class="form-control" type="number" step="0.01" min="0" name="price" value="<?php echo $p['price']; ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Category</label>
                        <select class="form-select" name="category_id" required>
                            <?php foreach ($cats as $c): ?>
                            <option value="<?php echo $c['category_id']; ?>" <?php echo $c['category_id'] == $p['category_id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($c['category_name']); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Change Image (optional)</label>
                    <input class="form-control" type="file" name="image" accept="image/*" id="imageInput">
                    <div class="form-text">Leave empty to keep current image. JPG, PNG, GIF, WEBP accepted.</div>
                    <div class="mt-2">
                        <img id="imagePreview" src="" style="max-height:200px;display:none;" class="img-fluid rounded border">
                    </div>
                </div>
                <button class="btn btn-warning w-100">Update Product</button>
            </form>
            <a href="dashboard.php" class="btn btn-secondary w-100 mt-2">Back to Dashboard</a>
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