<?php include APPROOT.'/views/admins/_layout_start.php'; ?>
<div class="page-header"><h1>🧑‍🍳 Manage Waiters</h1><p>Approve or reject waiter registrations</p></div>
<?php flash('admin_msg'); ?>
<div class="card">
  <div class="table-wrapper">
    <table>
      <thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Status</th><th>Registered</th><th>Actions</th></tr></thead>
      <tbody>
      <?php if(empty($data['waiters'])): ?>
        <tr><td colspan="6" style="text-align:center;color:var(--text-muted);padding:2rem;">No waiter registrations yet.</td></tr>
      <?php else: foreach($data['waiters'] as $w): ?>
      <tr>
        <td>#<?= $w->id ?></td>
        <td><strong><?= htmlspecialchars($w->name) ?></strong></td>
        <td><?= htmlspecialchars($w->email) ?></td>
        <td><span class="badge badge-<?= $w->status ?>"><?= ucfirst($w->status) ?></span></td>
        <td style="color:var(--text-muted);font-size:.82rem;"><?= date('d M Y',strtotime($w->created_at)) ?></td>
        <td style="display:flex;gap:.4rem;flex-wrap:wrap;">
          <?php if($w->status==='pending'): ?>
            <form action="<?= URLROOT ?>/admins/approveWaiter/<?= $w->id ?>" method="POST" style="display:inline;"><button type="submit" class="btn btn-success btn-sm">✅ Approve</button></form>
            <form action="<?= URLROOT ?>/admins/rejectWaiter/<?= $w->id ?>" method="POST" style="display:inline;"><button type="submit" class="btn btn-danger btn-sm">❌ Reject</button></form>
          <?php elseif($w->status==='rejected'): ?>
            <form action="<?= URLROOT ?>/admins/approveWaiter/<?= $w->id ?>" method="POST" style="display:inline;"><button type="submit" class="btn btn-success btn-sm">✅ Approve</button></form>
          <?php elseif($w->status==='approved'): ?>
            <form action="<?= URLROOT ?>/admins/rejectWaiter/<?= $w->id ?>" method="POST" style="display:inline;"><button type="submit" class="btn btn-outline btn-sm">Suspend</button></form>
          <?php endif; ?>
          <button type="button" class="btn btn-danger btn-sm" onclick="openConfirmModal('delete-waiter-<?= $w->id ?>', 'Delete waiter \'<?= addslashes($w->name) ?>\'?')">
            🗑
          </button>
          <form id="delete-waiter-<?= $w->id ?>" action="<?= URLROOT ?>/admins/deleteWaiter/<?= $w->id ?>" method="POST" style="display:none;"></form>
        </td>


      </tr>
      <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>
<?php include APPROOT.'/views/admins/_layout_end.php'; ?>
