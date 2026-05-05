<?php include APPROOT.'/views/admins/_layout_start.php'; ?>
<div class="page-header"><h1>🍕 Menu Management</h1><p>Add, edit, and organize your menu items</p></div>
<?php flash('admin_msg'); ?>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;margin-bottom:2rem;">
  <!-- Add Item Form -->
  <div class="card">
    <h3 style="font-weight:700;margin-bottom:1.2rem;">➕ Add Menu Item</h3>
    <form action="<?= URLROOT ?>/admins/addItem" method="POST" enctype="multipart/form-data">
      <div class="form-group">
        <label>Category</label>
        <select name="category_id" class="form-control" required>
          <?php foreach($data['categories'] as $cat): ?>
          <option value="<?= $cat->id ?>"><?= htmlspecialchars($cat->name) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="form-group">
        <label>Item Name</label>
        <input type="text" name="name" class="form-control" placeholder="e.g. Paneer Tikka" required>
      </div>
      <div class="form-group">
        <label>Description</label>
        <textarea name="description" class="form-control" rows="2" placeholder="Short description..."></textarea>
      </div>
      <div class="form-group">
        <label>Price (₹)</label>
        <input type="number" name="price" class="form-control" step="0.01" min="0" placeholder="299.00" required>
      </div>
      <div class="form-group">
        <label>Image Upload</label>
        <input type="file" name="image" class="form-control" accept="image/*">
      </div>
      <div class="form-group">
        <label>— OR — Image URL</label>
        <input type="url" name="image_url" class="form-control" placeholder="https://...">
      </div>
      <button type="submit" class="btn btn-primary w-100">Add Item</button>
    </form>
  </div>

  <!-- Add Category -->
  <div>
    <div class="card" style="margin-bottom:1.2rem;">
      <h3 style="font-weight:700;margin-bottom:1.2rem;">📂 Add Category</h3>
      <form action="<?= URLROOT ?>/admins/addCategory" method="POST" style="display:flex;gap:.6rem;">
        <input type="text" name="category_name" class="form-control" placeholder="Category name" required>
        <button type="submit" class="btn btn-secondary" style="flex-shrink:0;">Add</button>
      </form>
    </div>
    <div class="card">
      <h3 style="font-weight:700;margin-bottom:1rem;">📋 Categories</h3>
      <ul style="list-style:none;display:flex;flex-wrap:wrap;gap:.5rem;">
        <?php foreach($data['categories'] as $cat): ?>
        <li style="display:flex;align-items:center;gap:.4rem;background:var(--bg-card);border:1px solid var(--border);border-radius:50px;padding:.3rem .8rem;font-size:.85rem;">
          <?= htmlspecialchars($cat->name) ?>
          <button type="button" onclick="openConfirmModal('delete-cat-<?= $cat->id ?>', 'Delete category \'<?= addslashes($cat->name) ?>\'?')" style="background:none;border:none;color:var(--danger);font-weight:700;cursor:pointer;padding:0 .2rem;">×</button>
          <form id="delete-cat-<?= $cat->id ?>" action="<?= URLROOT ?>/admins/deleteCategory/<?= $cat->id ?>" method="POST" style="display:none;"></form>
        </li>


        <?php endforeach; ?>


      </ul>
    </div>
  </div>
</div>

<!-- Menu Items Table -->
<div class="card">
  <h3 style="font-weight:700;margin-bottom:1rem;">🍽️ All Menu Items (<?= count($data['items']) ?>)</h3>
  <div class="table-wrapper">
    <table>
      <thead><tr><th>Image</th><th>Name</th><th>Category</th><th>Price</th><th>Description</th><th>Action</th></tr></thead>
      <tbody>
      <?php foreach($data['items'] as $item): ?>
      <tr>
        <td>
          <?php if($item->image_url): ?>
            <img src="<?= htmlspecialchars($item->image_url) ?>" style="width:50px;height:50px;border-radius:8px;object-fit:cover;" onerror="this.style.display='none'">
          <?php else: ?><span style="font-size:2rem;">🍽️</span><?php endif; ?>
        </td>
        <td><strong><?= htmlspecialchars($item->name) ?></strong></td>
        <td><span class="badge badge-pending"><?= htmlspecialchars($item->category_name ?? '–') ?></span></td>
        <td style="color:var(--primary);font-weight:700;">₹<?= number_format($item->price,2) ?></td>
        <td style="color:var(--text-muted);font-size:.82rem;max-width:200px;"><?= htmlspecialchars(substr($item->description,0,60)) ?>...</td>
        <td>
          <button type="button" class="btn btn-danger btn-sm" onclick="openConfirmModal('delete-form-<?= $item->id ?>', 'Delete \'<?= addslashes($item->name) ?>\' from the menu?')">
            🗑 Delete
          </button>
          <form id="delete-form-<?= $item->id ?>" action="<?= URLROOT ?>/admins/deleteItem/<?= $item->id ?>" method="POST" style="display:none;"></form>
        </td>




      </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<?php include APPROOT.'/views/admins/_layout_end.php'; ?>
