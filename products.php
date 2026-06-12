<?php
include '../includes/db.php';
include '../includes/header.php';

$query = "SELECT products.*, users.full_name, categories.category_name
          FROM products
          JOIN users ON products.seller_id = users.user_id
          LEFT JOIN categories ON products.category_id = categories.category_id
          ORDER BY products.product_id DESC";

$result = mysqli_query($conn, $query);
?>

<div class="container mt-4">
    <h2>Manage Products</h2>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Product Name</th>
                <th>Category</th>
                <th>Seller</th>
                <th>Price</th>
                <th>Status</th>
            </tr>
        </thead>

        <tbody>
            <?php while($row = mysqli_fetch_assoc($result)){ ?>
            <tr>
                <td><?php echo $row['product_id']; ?></td>
                <td><?php echo $row['product_name']; ?></td>
                <td><?php echo $row['category_name']; ?></td>
                <td><?php echo $row['full_name']; ?></td>
                <td>R<?php echo $row['price']; ?></td>
                <td><?php echo $row['status']; ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<?php include '../includes/footer.php'; ?>