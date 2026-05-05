<?php include APPROOT.'/views/admins/_layout_start.php'; ?>
<div class="page-header"><h1>👤 Manage Users</h1><p>All registered customers</p></div>
<?php flash('admin_msg'); ?>
<div class="card">
  <div class="table-wrapper">
    <table>
      <thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Registered</th><th>Action</th></tr></thead>
      <tbody>
      <?php if(empty($data['users'])): ?>
        <tr><td colspan="5" style="text-align:center;color:var(--text-muted);padding:2rem;">No users registered yet.</td></tr>
      <?php else: foreach($data['users'] as $u): ?>
      <tr>
        <td>#<?= $u->id ?></td>
        <td><strong><?= htmlspecialchars($u->name) ?></strong></td>
        <td><?= htmlspecialchars($u->email) ?></td>
        <td style="color:var(--text-muted);font-size:.82rem;"><?= date('d M Y',strtotime($u->created_at)) ?></td>
        <td>
          <button type="button" class="btn btn-danger btn-sm" onclick="openConfirmModal('delete-user-<?= $u->id ?>', 'Delete user \'<?= addslashes($u->name) ?>\' and their order history?')">
            🗑 Delete
          </button>
          <form id="delete-user-<?= $u->id ?>" action="<?= URLROOT ?>/admins/deleteUser/<?= $u->id ?>" method="POST" style="display:none;"></form>
        </td>


      </tr>
      <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>
<?php include APPROOT.'/views/admins/_layout_end.php'; ?>
