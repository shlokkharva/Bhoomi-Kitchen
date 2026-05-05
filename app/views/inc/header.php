<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title><?= isset($data['title']) ? $data['title'].' | ' : '' ?>Bhoomi's Kitchen</title>
<meta name="description" content="Bhoomi's Kitchen Management System - Order, dine, and enjoy.">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= URLROOT ?>/css/style.css">
</head>
<body>
<!-- Background Visuals -->
<div class="bg-visuals">
  <div class="blob"></div>
  <div class="blob blob-2"></div>
  <div class="shape cube">
    <div class="front"></div><div class="back"></div>
    <div class="right"></div><div class="left"></div>
    <div class="top"></div><div class="bottom"></div>
  </div>
  <div class="shape sphere"></div>
</div>

<!-- Toast Container -->
<div class="toast-container" id="toastContainer"></div>


<!-- Navbar -->
<nav class="navbar">
  <a href="<?= URLROOT ?>" class="navbar-brand">🍽️ <span>Bhoomi's</span>Kitchen</a>
  <ul class="nav-links" id="navLinks">
    <li><a href="<?= URLROOT ?>">Home</a></li>
    <li><a href="<?= URLROOT ?>/orders/menu">Menu</a></li>
    <?php if(isset($_SESSION['user_id'])): ?>
      <li><a href="<?= URLROOT ?>/orders/track">Track Order</a></li>
      <li><a href="<?= URLROOT ?>/users/history">My Orders</a></li>
      <li><span style="color:var(--text-muted);font-size:.85rem;">👋 <?= htmlspecialchars($_SESSION['user_name']) ?></span></li>
      <li><a href="<?= URLROOT ?>/users/logout" class="btn btn-outline btn-sm">Logout</a></li>
    <?php else: ?>
      <li><a href="#" onclick="openAuthModal(event)" class="btn btn-primary btn-sm">Login / Sign up</a></li>
    <?php endif; ?>
  </ul>
</nav>
