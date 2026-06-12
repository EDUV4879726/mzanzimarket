<?php
include 'includes/db.php';
include 'includes/header.php';

if (!isset($_GET['id'])) {
    echo "<div class='alert alert-danger'>No product selected.</div>";
    include 'includes/footer.php';
    exit();
}

$product_id = intval($_GET['id']);

$query = "SELECT products.*, users.full_name, categories.category_name
          FROM products
          JOIN users ON products.seller_id = users.user_id
          LEFT JOIN categories ON products.category_id = categories.category_id
          WHERE products.product_id = $product_id
          LIMIT 1";

$result = mysqli_query($conn, $query);
$product = mysqli_fetch_assoc($result);

if (!$product) {
    echo "<div class='alert alert-danger'>Product not found.</div>";
    include 'includes/footer.php';
    exit();
}
?>

<div class="container mt-4">
    <div class="card p-4">
        <h1><?php echo $product['product_name']; ?></h1>

        <p><?php echo $product['description']; ?></p>

        <p><strong>Category:</strong> <?php echo $product['category_name']; ?></p>
        <p><strong>Seller:</strong> <?php echo $product['full_name']; ?></p>
        <p><strong>Price:</strong> R<?php echo $product['price']; ?></p>
        <p><strong>Status:</strong> <?php echo $product['status']; ?></p>

        <a href="products.php" class="btn btn-secondary">Back to Products</a>
    </div>
</div>

<?php include 'includes/footer.php'; ?>