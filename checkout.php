<?php
require_once 'includes/auth.php';
requireRole('buyer');
require_once 'includes/db.php';
include 'includes/header.php';

$cart = $_SESSION['cart'] ?? [];

if (empty($cart)) {
    header('Location: '.BASE_URL.'/cart.php');
    exit;
}

$message = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo->beginTransaction();
        
        foreach ($cart as $productId => $item) {
            $total = $item['price'] * $item['quantity'];
            $stmt = $pdo->prepare('INSERT INTO orders (buyer_id, product_id, quantity, total_amount, order_status) VALUES (?, ?, ?, ?, "pending")');
            $stmt->execute([$_SESSION['user_id'], $productId, $item['quantity'], $total]);
        }
        
        $pdo->commit();
        $_SESSION['cart'] = [];
        $success = true;
        $message = 'Order placed successfully! Thank you for your purchase.';
    } catch (PDOException $e) {
        $pdo->rollBack();
        $message = 'Error placing order: ' . $e->getMessage();
    }
}

$total = 0;
foreach ($cart as $item) {
    $total += $item['price'] * $item['quantity'];
}
?>
<h2>Checkout</h2>

<?php if ($success): ?>
<div class="alert alert-success text-center py-5">
    <h3><?php echo htmlspecialchars($message); ?></h3>
    <p>You can view your orders in My Orders.</p>
    <a href="<?php echo BASE_URL; ?>/my_orders.php" class="btn btn-primary">View My Orders</a>
    <a href="<?php echo BASE_URL; ?>/products.php" class="btn btn-secondary">Continue Shopping</a>
</div>
<?php else: ?>

<?php if ($message): ?>
<div class="alert alert-danger"><?php echo htmlspecialchars($message); ?></div>
<?php endif; ?>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0">Order Summary</h5>
            </div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead>
                        <tr><th>Product</th><th>Qty</th><th>Price</th><th>Subtotal</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cart as $item): 
                            $subtotal = $item['price'] * $item['quantity'];
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['name']); ?></td>
                            <td><?php echo $item['quantity']; ?></td>
                            <td>R<?php echo number_format($item['price'], 2); ?></td>
                            <td class="fw-bold">R<?php echo number_format($subtotal, 2); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot class="table-group-divider">
                        <tr>
                            <td colspan="3" class="text-end fw-bold fs-5">Total Amount:</td>
                            <td class="fw-bold fs-5 text-success">R<?php echo number_format($total, 2); ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0">Payment Details</h5>
            </div>
            <div class="card-body">
                <p class="text-muted small">This is a demo checkout. In production, integrate with PayFast, Yoco, or Stripe.</p>
                <form method="post">
                    <div class="mb-3">
                        <label class="form-label">Payment Method</label>
                        <select class="form-select" disabled>
                            <option>Pay on Delivery (COD)</option>
                        </select>
                        <div class="form-text">Only Cash on Delivery available for demo.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Delivery Address</label>
                        <textarea class="form-control" rows="3" placeholder="Enter your delivery address..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-success w-100 btn-lg">Place Order (R<?php echo number_format($total, 2); ?>)</button>
                </form>
                <a href="<?php echo BASE_URL; ?>/cart.php" class="btn btn-outline-secondary w-100 mt-2">Back to Cart</a>
            </div>
        </div>
    </div>
</div>

<?php endif; ?>

<?php include 'includes/footer.php'; ?>