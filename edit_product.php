<?php
session_start();
include '../includes/db.php';
include '../includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

if (!isset($_GET['id'])) {
    die("Product ID missing.");
}

$product_id = intval($_GET['id']);

$product_query = mysqli_query($conn,
    "SELECT * FROM products WHERE product_id = $product_id");

$product = mysqli_fetch_assoc($product_query);

if (!$product) {
    die("Product not found.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $product_name = mysqli_real_escape_string($conn, $_POST['product_name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price = $_POST['price'];
    $category_id = $_POST['category_id'];

    $update = "UPDATE products SET
                product_name = '$product_name',
                description = '$description',
                price = '$price',
                category_id = '$category_id'
               WHERE product_id = $product_id";

    mysqli_query($conn, $update);

    header("Location: dashboard.php");
    exit();
}

$categories = mysqli_query($conn, "SELECT * FROM categories");
?>

<div class="container mt-4">
    <h1>Edit Product</h1>

    <form method="POST">

        <input
            class="form-control mb-3"
            type="text"
            name="product_name"
            value="<?php echo $product['product_name']; ?>"
            required>

        <textarea
            class="form-control mb-3"
            name="description"
            required><?php echo $product['description']; ?></textarea>

        <input
            class="form-control mb-3"
            type="number"
            step="0.01"
            name="price"
            value="<?php echo $product['price']; ?>"
            required>

        <select class="form-control mb-3" name="category_id">

            <?php while($cat = mysqli_fetch_assoc($categories)){ ?>

                <option
                    value="<?php echo $cat['category_id']; ?>"
                    <?php if($cat['category_id'] == $product['category_id']) echo "selected"; ?>>

                    <?php echo $cat['category_name']; ?>

                </option>

            <?php } ?>

        </select>

        <button class="btn btn-success">
            Update Product
        </button>

    </form>
</div>

<?php include '../includes/footer.php'; ?>