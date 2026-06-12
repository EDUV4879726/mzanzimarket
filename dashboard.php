<?php
session_start();
include '../includes/db.php';
include '../includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$seller_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_name = mysqli_real_escape_string($conn, $_POST['product_name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price = $_POST['price'];
    $category_id = $_POST['category_id'];

    $query = "INSERT INTO products (seller_id, category_id, product_name, description, price, status)
              VALUES ($seller_id, $category_id, '$product_name', '$description', '$price', 'pending')";

    mysqli_query($conn, $query);
}

$products = mysqli_query($conn, "SELECT products.*, categories.category_name
                                 FROM products
                                 LEFT JOIN categories ON products.category_id = categories.category_id
                                 WHERE seller_id = $seller_id");
$categories = mysqli_query($conn, "SELECT * FROM categories");
?>

<div class="container mt-4">
    <h1>Seller Dashboard</h1>
    <p>This page allows sellers to add and view their product listings.</p>

    <div class="card p-4 mb-4">
        <h3>Add New Product</h3>

        <form method="POST">
            <input class="form-control mb-3" type="text" name="product_name" placeholder="Product Name" required>
            <textarea class="form-control mb-3" name="description" placeholder="Product Description" required></textarea>
            <input class="form-control mb-3" type="number" step="0.01" name="price" placeholder="Price" required>

            <select class="form-control mb-3" name="category_id" required>
                <?php while ($cat = mysqli_fetch_assoc($categories)) { ?>
                    <option value="<?php echo $cat['category_id']; ?>">
                        <?php echo $cat['category_name']; ?>
                    </option>
                <?php } ?>
            </select>

            <button class="btn btn-primary" type="submit">Add Product</button>
        </form>
    </div>

    <h3>My Products</h3>

    <table class="table table-bordered">
        <tr>
            <th>Product</th>
            <th>Category</th>
            <th>Price</th>
            <th>Status</th>
        </tr>

        <?php while ($row = mysqli_fetch_assoc($products)) { ?>
            <tr>
                <td><?php echo $row['product_name']; ?></td>
                <td><?php echo $row['category_name']; ?></td>
                <td>R<?php echo $row['price']; ?></td>
                <td><?php echo $row['status']; ?></td>
            </tr>
        <?php } ?>
    </table>
</div>

<?php include '../includes/footer.php'; ?>