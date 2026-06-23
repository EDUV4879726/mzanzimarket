<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'includes/db.php'; 
include 'includes/header.php';

$error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
  $stmt=$pdo->prepare('SELECT u.*, r.role_name FROM users u JOIN roles r ON u.role_id=r.role_id WHERE email=?');
  $stmt->execute([trim($_POST['email'])]); 
  $user=$stmt->fetch(PDO::FETCH_ASSOC);
  if($user && password_verify($_POST['password'],$user['password'])){
    $_SESSION['user_id']=$user['user_id']; 
    $_SESSION['name']=$user['full_name']; 
    $_SESSION['role']=$user['role_name'];
    if($user['role_name']==='admin') header('Location: '.BASE_URL.'/admin/dashboard.php'); 
    else if($user['role_name']==='seller') header('Location: '.BASE_URL.'/seller/dashboard.php'); 
    else header('Location: '.BASE_URL.'/products.php'); 
    exit;
  } else { 
    $error='Invalid email or password.'; 
  }
}
?>
<div class="row justify-content-center">
  <div class="col-md-5">
    <div class="card p-4">
      <h2>Login</h2>
      <?php if($error): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
      <?php endif; ?>
      <form method="post">
        <input class="form-control mb-3" type="email" name="email" placeholder="Email" required>
        <input class="form-control mb-3" type="password" name="password" placeholder="Password" required>
        <button class="btn btn-primary w-100">Login</button>
      </form>
      <p class="mt-3 small">Demo logins: admin@mzanzimarket.co.za, seller@mzanzimarket.co.za, buyer@mzanzimarket.co.za. Password: password123</p>
    </div>
  </div>
</div>
<?php include 'includes/footer.php'; ?>