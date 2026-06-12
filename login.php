<?php
session_start();
include 'includes/db.php';
include 'includes/header.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $query = "SELECT users.*, roles.role_name 
              FROM users 
              JOIN roles ON users.role_id = roles.role_id 
              WHERE users.email = '$email' 
              LIMIT 1";

    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['name'] = $user['full_name'];
        $_SESSION['role'] = $user['role_name'];

        if ($user['role_name'] == 'Admin') {
            header("Location: admin/dashboard.php");
            exit();
        } elseif ($user['role_name'] == 'Seller') {
            header("Location: seller/dashboard.php");
            exit();
        } else {
            header("Location: index.php");
            exit();
        }
    } else {
        $error = "Invalid email or password.";
    }
}
?>

<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card p-4">
            <h2>Login</h2>

            <?php if ($error != "") { ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php } ?>

            <form method="POST">
                <input class="form-control mb-3" type="email" name="email" placeholder="Email" required>
                <input class="form-control mb-3" type="password" name="password" placeholder="Password" required>
                <button class="btn btn-primary w-100" type="submit">Login</button>
            </form>

            <p class="mt-3 small">
                Demo logins:<br>
                Admin: admin@mzanzimarket.co.za<br>
                Seller: seller@mzanzimarket.co.za<br>
                Buyer: buyer@mzanzimarket.co.za<br>
                Password: password
            </p>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>