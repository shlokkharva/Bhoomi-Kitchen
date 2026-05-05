<?php include APPROOT.'/views/admins/_layout_start.php'; ?>
<div class="page-header"><h1>🪑 Table Management</h1><p>Configure restaurant tables and capacity</p></div>
<?php flash('admin_msg'); ?>
<div style="display:grid;grid-template-columns:320px 1fr;gap:1.5rem;">
  <!-- Add Table Form -->
  <div class="card" style="align-self:start;">
    <h3 style="font-weight:700;margin-bottom:1.2rem;">➕ Add Table</h3>
    <form action="<?= URLROOT ?>/admins/addTable" method="POST">
      <div class="form-group">
        <label>Table Number</label>
        <input type="number" name="table_number" class="form-control" placeholder="e.g. 7" min="1" required>
      </div>
      <div class="form-group">
        <label>Capacity (seats)</label>
        <input type="number" name="capacity" class="form-control" placeholder="e.g. 4" min="1" max="20" required>
      </div>
      <button type="submit" class="btn btn-primary w-100">Add Table</button>
    </form>
  </div>

  <!-- Tables Grid -->
  <div>
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(150px,1fr));gap:1rem;">
      <?php foreach($data['tables'] as $t): ?>
      <div class="card" style="text-align:center;border-color:<?= $t->status==='available' ? 'rgba(34,197,94,.4)' : 'rgba(239,68,68,.4)' ?>;">
        <div style="font-size:2.5rem;margin-bottom:.5rem;">🪑</div>
        <div style="font-weight:800;font-size:1.2rem;">Table <?= $t->table_number ?></div>
        <div style="color:var(--text-muted);font-size:.82rem;margin-bottom:.5rem;"><?= $t->capacity ?> seats</div>
        <span class="badge <?= $t->status==='available' ? 'badge-completed' : 'badge-danger' ?>"><?= ucfirst($t->status) ?></span>
        <div style="margin-top:.8rem;">
          <button type="button" class="btn btn-danger btn-sm" onclick="openConfirmModal('delete-table-<?= $t->id ?>', 'Delete Table <?= $t->table_number ?>?')">
            🗑
          </button>
          <form id="delete-table-<?= $t->id ?>" action="<?= URLROOT ?>/admins/deleteTable/<?= $t->id ?>" method="POST" style="display:none;"></form>
        </div>


      </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>
<?php include APPROOT.'/views/admins/_layout_end.php'; ?>
