<?php
session_start();
include '../includes/db.php';
include '../includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$query = "SELECT users.*, roles.role_name
          FROM users
          JOIN roles ON users.role_id = roles.role_id
          ORDER BY users.user_id ASC";

$result = mysqli_query($conn, $query);
?>

<div class="container mt-4">
    <h1>Manage Users</h1>

    <table class="table table-bordered table-striped">
        <tr>
            <th>User ID</th>
            <th>Full Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Role</th>
            <th>Verified</th>
        </tr>

        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?php echo $row['user_id']; ?></td>
                <td><?php echo $row['full_name']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td><?php echo $row['phone']; ?></td>
                <td><?php echo $row['role_name']; ?></td>
                <td><?php echo $row['is_verified']; ?></td>
            </tr>
        <?php } ?>
    </table>
</div>

<?php include '../includes/footer.php'; ?>