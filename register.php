<?php
include 'includes/db.php';
include 'includes/header.php';

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $role_id = intval($_POST['role_id']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $query = "INSERT INTO users (full_name, email, password, phone, role_id, is_verified)
              VALUES ('$full_name', '$email', '$password', '$phone', $role_id, 1)";

    if (mysqli_query($conn, $query)) {
        $message = "Registration successful. You can now login.";
    } else {
        $message = "Registration failed: " . mysqli_error($conn);
    }
}
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card p-4">
            <h2>Register</h2>

            <?php if ($message != "") { ?>
                <div class="alert alert-info"><?php echo $message; ?></div>
            <?php } ?>

            <form method="POST">
                <input class="form-control mb-3" type="text" name="full_name" placeholder="Full Name" required>
                <input class="form-control mb-3" type="email" name="email" placeholder="Email" required>
                <input class="form-control mb-3" type="text" name="phone" placeholder="Phone Number" required>

                <select class="form-control mb-3" name="role_id" required>
                    <option value="2">Buyer</option>
                    <option value="3">Seller</option>
                </select>

                <input class="form-control mb-3" type="password" name="password" placeholder="Password" required>

                <button class="btn btn-primary w-100" type="submit">Register</button>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>