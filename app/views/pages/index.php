<?php require_once APPROOT . '/views/inc/header.php'; ?>

<!-- Hero Section -->
<section class="hero-section" style="min-height:90vh; display:flex; align-items:center; justify-content:center; text-align:center; position:relative; padding:4rem 5%;">
  <div style="max-width:900px; position:relative; z-index:10;">
    <span class="badge badge-preparing" style="margin-bottom:1.5rem; animation:fadeIn 1s both;">✨ Exquisite Dining Experience</span>
    <h1 style="font-size:clamp(3rem, 8vw, 5.5rem); font-weight:900; line-height:1.1; margin-bottom:1.5rem; letter-spacing:-2px; animation:fadeIn 1.2s both 0.2s;">
      Taste the Magic at <br><span class="text-primary" style="filter:drop-shadow(0 0 20px var(--primary-glow));">Bhoomi's Kitchen</span>
    </h1>
    <p style="font-size:1.25rem; color:var(--text-muted); margin-bottom:2.5rem; max-width:700px; margin-left:auto; margin-right:auto; line-height:1.6; animation:fadeIn 1.4s both 0.4s;">
      From our garden to your plate, we serve authentic flavors crafted with passion and precision. Join us for a culinary journey you'll never forget.
    </p>
    <div style="display:flex; gap:1.5rem; justify-content:center; animation:fadeIn 1.6s both 0.6s;">
      <a href="<?= URLROOT ?>/orders/menu" class="btn btn-primary btn-lg" style="padding:1.2rem 3rem; font-size:1.1rem; border-radius:100px;">🍽️ Explore Menu</a>
      <a href="#" onclick="openAuthModal(event)" class="btn btn-outline btn-lg" style="padding:1.2rem 3rem; font-size:1.1rem; border-radius:100px;">Join the Club</a>
    </div>
  </div>

  <!-- Decorative Hero Image (Floating) -->
  <div style="position:absolute; bottom:-10%; right:5%; width:400px; height:400px; background:url('https://images.unsplash.com/photo-1559339352-11d035aa65de?auto=format&fit=crop&q=80&w=800') center/cover; border-radius:30% 70% 70% 30% / 30% 30% 70% 70%; animation: morph 10s infinite alternate, float-shape 6s infinite ease-in-out; z-index:1; border:5px solid var(--glass-border); box-shadow:var(--shadow);"></div>
</section>

<!-- Features Section -->
<section style="padding:8rem 5%; background:rgba(255,255,255,0.01);">
  <div style="text-align:center; margin-bottom:5rem;">
    <h2 style="font-size:2.8rem; font-weight:900; margin-bottom:1rem;">Why Dine With Us?</h2>
    <p style="color:var(--text-muted);">The ingredients for a perfect meal are right here</p>
  </div>

  <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(300px, 1fr)); gap:3rem;">
    <!-- Feature 1 -->
    <div class="card" style="text-align:center; padding:3.5rem 2rem;">
      <div style="font-size:3.5rem; margin-bottom:1.5rem;">🥗</div>
      <h3 style="font-size:1.4rem; margin-bottom:1rem; font-weight:800;">Fresh Ingredients</h3>
      <p style="color:var(--text-muted); line-height:1.6;">We source our produce daily from local organic farms to ensure the highest quality and taste in every bite.</p>
    </div>

    <!-- Feature 2 -->
    <div class="card" style="text-align:center; padding:3.5rem 2rem; border-color:var(--primary);">
      <div style="font-size:3.5rem; margin-bottom:1.5rem;">👨‍🍳</div>
      <h3 style="font-size:1.4rem; margin-bottom:1rem; font-weight:800;">Expert Chefs</h3>
      <p style="color:var(--text-muted); line-height:1.6;">Our culinary masters bring decades of experience and a creative touch to traditional recipes.</p>
    </div>

    <!-- Feature 3 -->
    <div class="card" style="text-align:center; padding:3.5rem 2rem;">
      <div style="font-size:3.5rem; margin-bottom:1.5rem;">🍷</div>
      <h3 style="font-size:1.4rem; margin-bottom:1rem; font-weight:800;">Elegant Ambience</h3>
      <p style="color:var(--text-muted); line-height:1.6;">Enjoy your meal in a sophisticated environment designed to make every occasion feel special.</p>
    </div>
  </div>
</section>

<!-- About Section -->
<section style="padding:8rem 5%; display:grid; grid-template-columns:1fr 1fr; gap:5rem; align-items:center;">
  <div style="position:relative;">
    <div style="width:100%; height:500px; background:url('https://images.unsplash.com/photo-1514362545857-3bc16c4c7d1b?auto=format&fit=crop&q=80&w=800') center/cover; border-radius:var(--radius); box-shadow:var(--shadow);"></div>
    <div style="position:absolute; -bottom:3rem; -right:3rem; background:var(--primary); padding:2rem; border-radius:var(--radius); color:#fff; box-shadow:var(--shadow);">
      <div style="font-size:2.5rem; font-weight:900;">15+</div>
      <div style="font-size:.9rem; font-weight:600; opacity:.9;">Years of <br>Excellence</div>
    </div>
  </div>
  <div>
    <h2 style="font-size:2.8rem; font-weight:900; margin-bottom:1.5rem; line-height:1.2;">A Legacy of <span class="text-primary">Fine Taste</span> & Quality</h2>
    <p style="color:var(--text-muted); margin-bottom:1.5rem; line-height:1.8; font-size:1.1rem;">
      Bhoomi's Kitchen started as a small family dream in 2009. Today, we stand as a beacon of culinary excellence, known for our innovative approach to traditional cooking.
    </p>
    <p style="color:var(--text-muted); margin-bottom:2.5rem; line-height:1.8; font-size:1.1rem;">
      We believe that food is more than just nourishment; it's an experience that brings people together. That's why every dish we serve is a piece of art, designed to delight all your senses.
    </p>
    <a href="<?= URLROOT ?>/orders/menu" class="btn btn-outline btn-lg" style="border-radius:100px;">Learn Our Story</a>
  </div>
</section>

<style>
@keyframes morph {
  0% { border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%; }
  100% { border-radius: 70% 30% 30% 70% / 70% 70% 30% 30%; }
}
@media (max-width: 992px) {
  section { flex-direction: column !important; grid-template-columns: 1fr !important; text-align: center !important; }
  .hero-section div[style*="absolute"] { display: none; }
}
</style>

<?php require_once APPROOT . '/views/inc/footer.php'; ?>
