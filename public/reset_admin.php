<?php
/**
 * Admin Password Resetter
 * Run once: http://localhost/restaurant2/public/reset_admin.php
 */
require_once '../app/init.php';

$db = new Database();
$username = 'admin';
$password = 'admin123';
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Check if admin exists
$db->query('SELECT * FROM admins WHERE username = :user');
$db->bind(':user', $username);
$admin = $db->single();

if($admin){
    // Update existing
    $db->query('UPDATE admins SET password = :pass WHERE username = :user');
    $db->bind(':pass', $hashed_password);
    $db->bind(':user', $username);
    if($db->execute()){
        echo "✅ Admin password reset to <strong>$password</strong> successfully!";
    } else {
        echo "❌ Failed to update admin password.";
    }
} else {
    // Create new
    $db->query('INSERT INTO admins (username, password) VALUES (:user, :pass)');
    $db->bind(':user', $username);
    $db->bind(':pass', $hashed_password);
    if($db->execute()){
        echo "✅ Created new admin user with username: <strong>$username</strong> and password: <strong>$password</strong>";
    } else {
        echo "❌ Failed to create admin user.";
    }
}

echo "<br><br><a href='" . URLROOT . "/admins/login'>Go to Admin Login →</a>";
echo "<p style='color:red'><strong>Delete this file (public/reset_admin.php) after running!</strong></p>";
?>
