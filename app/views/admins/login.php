<?php require_once APPROOT . '/views/inc/header.php'; ?>
<div class="auth-page">
  <div class="auth-box">
    <div class="card">
      <div class="auth-header">
        <div class="auth-logo">🛠️</div>
        <h1>Admin Login</h1>
        <p>Secure access to management panel</p>
      </div>
      <?php if(!empty($data['err'])): ?><div class="toast toast-danger" style="margin-bottom:1rem;"><?= $data['err'] ?></div><?php endif; ?>
      <form action="<?= URLROOT ?>/admins/login" method="POST">
        <div class="form-group">
          <label>Username</label>
          <input type="text" name="username" class="form-control" value="<?= $data['username'] ?>" placeholder="admin" required>
        </div>
        <div class="form-group">
          <label>Password</label>
          <input type="password" name="password" class="form-control" placeholder="••••••••" required>
        </div>

        <button type="submit" class="btn btn-success w-100 btn-lg">Access Panel</button>
      </form>
    </div>
  </div>
</div>
<?php require_once APPROOT . '/views/inc/footer.php'; ?>
