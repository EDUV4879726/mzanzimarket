<?php
session_start();
include '../includes/db.php';
include '../includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM users"))['total'];
$product_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM products"))['total'];
$order_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM orders"))['total'];
?>

<div class="container mt-4">
    <h1>Admin Dashboard</h1>
    <p>Welcome, <?php echo $_SESSION['name']; ?>. This area is used to manage the MzanziMarket platform.</p>

    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card p-4 text-center">
                <h3><?php echo $user_count; ?></h3>
                <p>Total Users</p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card p-4 text-center">
                <h3><?php echo $product_count; ?></h3>
                <p>Total Products</p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card p-4 text-center">
                <h3><?php echo $order_count; ?></h3>
                <p>Total Orders</p>
            </div>
        </div>
    </div>

    <h2 class="mt-5">Registered Users</h2>
    <table class="table table-bordered table-striped mt-3">
        <tr>
            <th>User ID</th>
            <th>Full Name</th>
            <th>Email</th>
            <th>Role ID</th>
            <th>Verified</th>
        </tr>

        <?php
        $users = mysqli_query($conn, "SELECT * FROM users");
        while ($row = mysqli_fetch_assoc($users)) {
        ?>
            <tr>
                <td><?php echo $row['user_id']; ?></td>
                <td><?php echo $row['full_name']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td><?php echo $row['role_id']; ?></td>
                <td><?php echo $row['is_verified']; ?></td>
            </tr>
        <?php } ?>
    </table>

    <h2 class="mt-5">Product Listings</h2>
    <table class="table table-bordered table-striped mt-3">
        <tr>
            <th>Product ID</th>
            <th>Product Name</th>
            <th>Price</th>
            <th>Status</th>
        </tr>

        <?php
        $products = mysqli_query($conn, "SELECT * FROM products");
        while ($row = mysqli_fetch_assoc($products)) {
        ?>
            <tr>
                <td><?php echo $row['product_id']; ?></td>
                <td><?php echo $row['product_name']; ?></td>
                <td>R<?php echo $row['price']; ?></td>
                <td><?php echo $row['status']; ?></td>
            </tr>
        <?php } ?>
    </table>
</div>

<?php include '../includes/footer.php'; ?>