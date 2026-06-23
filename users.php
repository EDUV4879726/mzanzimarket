<?php
require_once '../includes/auth.php';
requireRole('admin');
include '../includes/db.php';
include '../includes/header.php';

$message = '';

if (isset($_GET['verify'])) {
    $pdo->prepare('UPDATE users SET is_verified = 1 WHERE user_id = ?')->execute([$_GET['verify']]);
    $message = 'User verified successfully.';
}

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    if ($id === (int)$_SESSION['user_id']) {
        $message = 'You cannot delete your own account.';
    } else {
        try {
            $pdo->prepare('DELETE FROM users WHERE user_id = ?')->execute([$id]);
            $message = 'User deleted successfully.';
        } catch (PDOException $e) {
            $message = 'Cannot delete user: they have existing products or orders.';
        }
    }
}

$users = $pdo->query('SELECT u.*, r.role_name FROM users u JOIN roles r ON u.role_id = r.role_id ORDER BY u.created_at DESC')->fetchAll(PDO::FETCH_ASSOC);
?>
<h2>Manage Users</h2>
<?php if ($message): ?>
<div class="alert alert-info"><?php echo htmlspecialchars($message); ?></div>
<?php endif; ?>
<div class="card">
    <div class="card-body p-0">
        <table class="table table-striped mb-0">
            <thead class="table-dark">
                <tr>
                    <th>ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Role</th><th>Verified</th><th>Joined</th><th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $u): ?>
                <tr>
                    <td><?php echo $u['user_id']; ?></td>
                    <td><?php echo htmlspecialchars($u['full_name']); ?></td>
                    <td><?php echo htmlspecialchars($u['email']); ?></td>
                    <td><?php echo htmlspecialchars($u['phone'] ?? 'N/A'); ?></td>
                    <td><span class="badge bg-<?php echo $u['role_name'] === 'admin' ? 'danger' : ($u['role_name'] === 'seller' ? 'warning' : 'info'); ?>"><?php echo ucfirst($u['role_name']); ?></span></td>
                    <td><?php echo $u['is_verified'] ? '<span class="badge bg-success">Yes</span>' : '<span class="badge bg-secondary">No</span>'; ?></td>
                    <td><?php echo date('d M Y', strtotime($u['created_at'])); ?></td>
                    <td>
                        <?php if (!$u['is_verified']): ?><a class="btn btn-sm btn-success" href="?verify=<?php echo $u['user_id']; ?>">Verify</a><?php endif; ?>
                        <?php if ($u['user_id'] !== (int)$_SESSION['user_id']): ?>
                            <a class="btn btn-sm btn-danger" href="?delete=<?php echo $u['user_id']; ?>" onclick="return confirmAction('Delete this user?')">Delete</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">
    <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
</div>
<?php include '../includes/footer.php'; ?>
