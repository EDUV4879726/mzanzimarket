<?php
session_start();
include '../includes/db.php';
include '../includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$query = "SELECT orders.*, users.full_name, products.product_name
          FROM orders
          JOIN users ON orders.buyer_id = users.user_id
          JOIN products ON orders.product_id = products.product_id
          ORDER BY orders.order_id DESC";

$result = mysqli_query($conn, $query);
?>

<div class="container mt-4">
    <h1>Manage Orders</h1>

    <table class="table table-bordered table-striped">
        <tr>
            <th>Order ID</th>
            <th>Buyer</th>
            <th>Product</th>
            <th>Quantity</th>
            <th>Total Amount</th>
            <th>Status</th>
        </tr>

        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?php echo $row['order_id']; ?></td>
                <td><?php echo $row['full_name']; ?></td>
                <td><?php echo $row['product_name']; ?></td>
                <td><?php echo $row['quantity']; ?></td>
                <td>R<?php echo $row['total_amount']; ?></td>
                <td><?php echo $row['order_status']; ?></td>
            </tr>
        <?php } ?>
    </table>
</div>

<?php include '../includes/footer.php'; ?>