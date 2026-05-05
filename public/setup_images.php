<?php
/**
 * One-time setup script: copies generated food images to the uploads folder
 * and fixes broken image URLs in the database.
 * Run once via: http://localhost/restaurant2/public/setup_images.php
 * DELETE this file after running.
 */
require_once '../app/init.php';

$db = new Database();

// ── 1. Copy gulab_jamun image from generated location ──────────────
$src  = 'C:/Users/shlok/.gemini/antigravity/brain/96abe53d-c8f4-4875-a573-ef9b46dfa3ae/gulab_jamun_1777980858691.png';
$dest = __DIR__ . '/uploads/gulab_jamun.png';

if(file_exists($src)){
    copy($src, $dest);
    echo "✅ Copied gulab_jamun.png to uploads/<br>";
} else {
    echo "⚠️ Source image not found at: $src<br>";
}

// ── 2. Fix broken/missing image URLs in DB ──────────────────────────
// Map: item name => reliable image URL
$fixes = [
    'Gulab Jamun'        => URLROOT . '/uploads/gulab_jamun.png',
    'Paneer Tikka'       => 'https://images.unsplash.com/photo-1567188040759-fb8a883dc6d8?w=500&q=80',
    'Veg Spring Rolls'   => 'https://images.unsplash.com/photo-1544025162-d76694265947?w=500&q=80',
    'Chicken Wings'      => 'https://images.unsplash.com/photo-1567620832903-9fc6debc209f?w=500&q=80',
    'Butter Chicken'     => 'https://images.unsplash.com/photo-1603894584373-5ac82b2ae398?w=500&q=80',
    'Dal Makhani'        => 'https://images.unsplash.com/photo-1585937421612-70a008356fbe?w=500&q=80',
    'Grilled Salmon'     => 'https://images.unsplash.com/photo-1467003909585-2f8a72700288?w=500&q=80',
    'Margherita Pizza'   => 'https://images.unsplash.com/photo-1574071318508-1cdbab80d002?w=500&q=80',
    'Chocolate Lava Cake'=> 'https://images.unsplash.com/photo-1563805042-7684c019e1cb?w=500&q=80',
    'Mango Lassi'        => 'https://images.unsplash.com/photo-1546548970-71785318a17b?w=500&q=80',
    'Cold Coffee'        => 'https://images.unsplash.com/photo-1461023058943-07fcbe16d735?w=500&q=80',
    'Fresh Lime Soda'    => 'https://images.unsplash.com/photo-1437418747212-8d9709afab22?w=500&q=80',
];

echo "<h3>Updating image URLs...</h3>";
foreach($fixes as $name => $url){
    $db->query('UPDATE menu_items SET image_url = :url WHERE name = :name');
    $db->bind(':url', $url);
    $db->bind(':name', $name);
    if($db->execute()){
        echo "✅ Updated: <strong>$name</strong><br>";
    } else {
        echo "❌ Failed: $name<br>";
    }
}

echo "<hr><h3>✅ Done! <a href='" . URLROOT . "'>Go to Menu →</a></h3>";
echo "<p style='color:red'><strong>Delete this file (public/setup_images.php) after running!</strong></p>";
?>
