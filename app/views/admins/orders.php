<?php include APPROOT.'/views/admins/_layout_start.php'; ?>
<div class="page-header"><h1>🧾 All Orders</h1><p>Track and monitor all customer orders</p></div>
<div class="card">
  <div class="table-wrapper">
    <table>
      <thead><tr><th>ID</th><th>Customer</th><th>Waiter</th><th>Table</th><th>Subtotal</th><th>Tax</th><th>Tip</th><th>Total</th><th>Status</th><th>Date</th></tr></thead>
      <tbody>
      <?php if(empty($data['orders'])): ?>
        <tr><td colspan="10" style="text-align:center;color:var(--text-muted);padding:2rem;">No orders placed yet.</td></tr>
      <?php else: foreach($data['orders'] as $o): ?>
      <tr>
        <td><strong>#<?= $o->id ?></strong></td>
        <td><?= htmlspecialchars($o->customer_name) ?></td>
        <td><?= htmlspecialchars($o->waiter_name??'-') ?></td>
        <td>T<?= $o->table_number ?></td>
        <td>₹<?= number_format($o->total_amount - $o->tax_amount - $o->service_charge - $o->tip_amount, 2) ?></td>
        <td style="color:var(--text-muted);">₹<?= number_format($o->tax_amount + $o->service_charge, 2) ?></td>
        <td style="color:var(--success);">₹<?= number_format($o->tip_amount,2) ?></td>
        <td style="color:var(--primary);font-weight:700;">₹<?= number_format($o->total_amount,2) ?></td>
        <td><span class="badge badge-<?= $o->status ?>"><?= ucfirst($o->status) ?></span></td>
        <td style="color:var(--text-muted);font-size:.8rem;"><?= date('d M, h:i A',strtotime($o->created_at)) ?></td>
      </tr>
      <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>
<?php include APPROOT.'/views/admins/_layout_end.php'; ?>
