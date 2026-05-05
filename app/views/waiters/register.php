<?php require_once APPROOT . '/views/inc/header.php'; ?>
<div class="auth-page">
  <div class="auth-box">
    <div class="card">
      <div class="auth-header">
        <div class="auth-logo">🧑‍🍳</div>
        <h1>Join Our Team</h1>
        <p>Apply as a waiter — pending admin approval</p>
      </div>
      <form action="<?= URLROOT ?>/waiters/register" method="POST">
        <div class="form-group">
          <label>Full Name</label>
          <input type="text" name="name" class="form-control" value="<?= $data['name'] ?>" placeholder="Your name" required>
          <?php if($data['name_err']): ?><div class="invalid-feedback"><?= $data['name_err'] ?></div><?php endif; ?>
        </div>
        <div class="form-group">
          <label>Email Address</label>
          <input type="email" name="email" class="form-control" value="<?= $data['email'] ?>" placeholder="waiter@email.com" required>
          <?php if($data['email_err']): ?><div class="invalid-feedback"><?= $data['email_err'] ?></div><?php endif; ?>
        </div>
        <div class="form-group">
          <label>Password</label>
          <input type="password" name="password" class="form-control" placeholder="Min 6 characters" required>
          <?php if($data['pass_err']): ?><div class="invalid-feedback"><?= $data['pass_err'] ?></div><?php endif; ?>
        </div>
        <div style="background:rgba(249,115,22,.1);border:1px solid rgba(249,115,22,.3);border-radius:var(--radius-sm);padding:.8rem;margin-bottom:1.2rem;font-size:.84rem;color:var(--text-muted);">
          ⚠️ Your account will be reviewed and approved by an admin before you can log in.
        </div>
        <button type="submit" class="btn btn-secondary w-100 btn-lg">Submit Application</button>
      </form>
      <hr class="divider">
      <p class="text-center text-muted" style="font-size:.88rem;">Already registered? <a href="<?= URLROOT ?>/waiters/login" style="color:var(--secondary);">Login here</a></p>
    </div>
  </div>
</div>
<?php require_once APPROOT . '/views/inc/footer.php'; ?>
