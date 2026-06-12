<?php
session_start();
include '../includes/db.php';
include '../includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_name = mysqli_real_escape_string($conn, $_POST['product_name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price = $_POST['price'];
    $category_id = $_POST['category_id'];
    $seller_id = $_SESSION['user_id'];

    $query = "INSERT INTO products
              (seller_id, category_id, product_name, description, price, status)
              VALUES
              ($seller_id, $category_id, '$product_name', '$description', '$price', 'pending')";

    if (mysqli_query($conn, $query)) {
        $message = "Product added successfully.";
    }
}

$categories = mysqli_query($conn, "SELECT * FROM categories");
?>

<div class="container mt-4">
    <h1>Add Product</h1>

    <?php if ($message != "") { ?>
        <div class="alert alert-success"><?php echo $message; ?></div>
    <?php } ?>

    <form method="POST">
        <input class="form-control mb-3" type="text" name="product_name" placeholder="Product Name" required>

        <textarea class="form-control mb-3" name="description" placeholder="Description" required></textarea>

        <input class="form-control mb-3" type="number" step="0.01" name="price" placeholder="Price" required>

        <select class="form-control mb-3" name="category_id" required>
            <?php while($cat = mysqli_fetch_assoc($categories)){ ?>
                <option value="<?php echo $cat['category_id']; ?>">
                    <?php echo $cat['category_name']; ?>
                </option>
            <?php } ?>
        </select>

        <button class="btn btn-primary">Add Product</button>
    </form>
</div>

<?php include '../includes/footer.php'; ?>