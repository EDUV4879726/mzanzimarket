<?php
include 'includes/db.php';
include 'includes/header.php';

$query = "SELECT products.*, users.full_name, categories.category_name
          FROM products
          JOIN users ON products.seller_id = users.user_id
          LEFT JOIN categories ON products.category_id = categories.category_id
          ORDER BY products.product_id DESC";

$result = mysqli_query($conn, $query);
?>

<div class="container mt-4">
    <h1 class="mb-4">Available Products</h1>

    <div class="row">

        <?php while($row = mysqli_fetch_assoc($result)){ ?>

        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm p-3">

                <h4><?php echo $row['product_name']; ?></h4>

                <p>
                    <?php echo $row['description']; ?>
                </p>

                <p>
                    <strong>Category:</strong>
                    <?php echo $row['category_name']; ?>
                </p>

                <p>
                    <strong>Seller:</strong>
                    <?php echo $row['full_name']; ?>
                </p>

                <p>
                    <strong>Price:</strong>
                    R<?php echo $row['price']; ?>
                </p>

                <p>
                    <strong>Status:</strong>
                    <?php echo $row['status']; ?>
                </p>

                <a href="product.php?id=<?php echo $row['product_id']; ?>" class="btn btn-primary">
                    View Product
                </a>

            </div>
        </div>

        <?php } ?>

    </div>
</div>

<?php include 'includes/footer.php'; ?>