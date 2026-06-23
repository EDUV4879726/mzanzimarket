<?php
include 'includes/db.php'; 
include 'includes/header.php';
$message='';
if($_SERVER['REQUEST_METHOD']==='POST'){
  $name=trim($_POST['full_name']); 
  $email=trim($_POST['email']); 
  $phone=trim($_POST['phone']); 
  $role=$_POST['role'];
  $password=password_hash($_POST['password'], PASSWORD_DEFAULT);
  $stmt=$pdo->prepare('SELECT role_id FROM roles WHERE role_name=?'); 
  $stmt->execute([$role]); 
  $role_id=$stmt->fetchColumn();
  try{ 
    $stmt=$pdo->prepare('INSERT INTO users(full_name,email,password,phone,role_id,is_verified) VALUES(?,?,?,?,?,?)'); 
    $stmt->execute([$name,$email,$password,$phone,$role_id,$role==='buyer'?1:0]); 
    $message='Registration successful. You can now login.'; 
  }
  catch(PDOException $e){ 
    $message='Email already exists or registration failed.'; 
  }
}
?>
<div class="row justify-content-center">
  <div class="col-md-6">
    <div class="card p-4">
      <h2>Register</h2>
      <?php if($message): ?>
        <div class="alert alert-info"><?php echo htmlspecialchars($message); ?></div>
      <?php endif; ?>
      <form method="post" class="needs-validation" novalidate>
        <input class="form-control mb-3" name="full_name" placeholder="Full Name" required>
        <input class="form-control mb-3" type="email" name="email" placeholder="Email" required>
        <input class="form-control mb-3" name="phone" placeholder="Phone Number" required>
        <select class="form-select mb-3" name="role">
          <option value="buyer">Buyer</option>
          <option value="seller">Seller</option>
        </select>
        <input class="form-control mb-3" type="password" name="password" placeholder="Password" required>
        <button class="btn btn-primary w-100">Create Account</button>
      </form>
    </div>
  </div>
</div>
<?php include 'includes/footer.php'; ?>
