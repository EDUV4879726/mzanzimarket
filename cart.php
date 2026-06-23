<?php
session_start();
require_once 'includes/auth.php';
require_once 'includes/db.php';
include 'includes/header.php';

$message = '';

if (isset($_GET['add']) && is_numeric($_GET['add'])) {
    $productId = (int)$_GET['add'];
    $stmt = $pdo->prepare('SELECT * FROM products WHERE product_id = ? AND status = "approved"');
    $stmt->execute([$productId]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($product) {
        if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
        
        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId]['quantity']++;
        } else {
            $_SESSION['cart'][$productId] = [
                'name' => $product['product_name'],
                'price' => $product['price'],
                'image' => $product['image'],
                'seller_id' => $product['seller_id'],
                'quantity' => 1
            ];
        }
        $message = 'Product added to cart!';
    }
}

if (isset($_GET['remove'])) {
    $id = (int)$_GET['remove'];
    if (isset($_SESSION['cart'][$id])) {
        unset($_SESSION['cart'][$id]);
        $message = 'Item removed from cart.';
    }
}

if (isset($_POST['update'])) {
    foreach ($_POST['quantity'] as $id => $qty) {
        $qty = max(1, (int)$qty);
        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]['quantity'] = $qty;
        }
    }
    $message = 'Cart updated.';
}

if (isset($_GET['clear'])) {
    $_SESSION['cart'] = [];
    $message = 'Cart cleared.';
}

$cart = $_SESSION['cart'] ?? [];
$total = 0;
?>
<h2>Shopping Cart</h2>

<?php if ($message): ?>
<div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
<?php endif; ?>

<?php if (empty($cart)): ?>
<div class="alert alert-info text-center py-5">
    <h4>Your cart is empty</h4>
    <p>Browse our products and add items to your cart.</p>
    <a href="<?php echo BASE_URL; ?>/products.php" class="btn btn-primary">Browse Products</a>
</div>
<?php else: ?>
<form method="post" action="<?php echo BASE_URL; ?>/cart.php">
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped mb-0 align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Subtotal</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cart as $id => $item): 
                            $subtotal = $item['price'] * $item['quantity'];
                            $total += $subtotal;
                        ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <img src="<?php echo BASE_URL; ?>/uploads/<?php echo htmlspecialchars($item['image']); ?>" style="width:60px;height:50px;object-fit:cover;border-radius:6px;" onerror="this.src='https://via.placeholder.com/60x50?text=No+Image'">
                                    <span><?php echo htmlspecialchars($item['name']); ?></span>
                                </div>
                            </td>
                            <td>R<?php echo number_format($item['price'], 2); ?></td>
                            <td>
                                <input type="number" name="quantity[<?php echo $id; ?>]" value="<?php echo $item['quantity']; ?>" min="1" class="form-control" style="width:80px;">
                            </td>
                            <td class="fw-bold">R<?php echo number_format($subtotal, 2); ?></td>
                            <td>
                                <a href="<?php echo BASE_URL; ?>/cart.php?remove=<?php echo $id; ?>" class="btn btn-sm btn-danger" onclick="return confirmAction('Remove this item?')">Remove</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot class="table-group-divider">
                        <tr>
                            <td colspan="3" class="text-end fw-bold fs-5">Total:</td>
                            <td class="fw-bold fs-5 text-success">R<?php echo number_format($total, 2); ?></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    
    <div class="d-flex justify-content-between mt-3">
        <div>
            <button type="submit" name="update" class="btn btn-outline-primary">Update Quantities</button>
            <a href="<?php echo BASE_URL; ?>/cart.php?clear=1" class="btn btn-outline-danger" onclick="return confirmAction('Clear entire cart?')">Clear Cart</a>
        </div>
        <div>
            <a href="<?php echo BASE_URL; ?>/products.php" class="btn btn-secondary">Continue Shopping</a>
            <?php if (isLoggedIn() && userRole() === 'buyer'): ?>
                <a href="<?php echo BASE_URL; ?>/checkout.php" class="btn btn-success btn-lg">Proceed to Checkout</a>
            <?php else: ?>
                <a href="<?php echo BASE_URL; ?>/login.php" class="btn btn-success btn-lg">Login to Checkout</a>
            <?php endif; ?>
        </div>
    </div>
</form>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>