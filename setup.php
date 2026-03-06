<?php
require 'db.php';

echo "<style>*{font-family:monospace;font-size:14px}body{background:#050e0a;color:#37d67a;padding:20px}.ok{color:#37d67a}.err{color:#ff5a6a}.warn{color:#ffc107}</style>";
echo "<h2>🔧 AgriConnect DB Setup & Fix</h2>";

$fixes = [];

// 1. Add delivery_id to orders if missing
$cols = mysqli_query($conn,"SHOW COLUMNS FROM orders LIKE 'delivery_id'");
if(mysqli_num_rows($cols)==0){
  $r=mysqli_query($conn,"ALTER TABLE orders ADD COLUMN delivery_id INT DEFAULT NULL AFTER shop_id");
  $fixes[]=$r?"<span class='ok'>✅ Added delivery_id column to orders</span>":"<span class='err'>❌ Failed: ".mysqli_error($conn)."</span>";
} else {
  $fixes[]="<span class='ok'>✅ delivery_id column already exists</span>";
}

// 2. Fix status ENUM to include out_for_delivery
$r=mysqli_query($conn,"ALTER TABLE orders MODIFY status ENUM('pending','accepted','rejected','out_for_delivery','delivered') DEFAULT 'pending'");
$fixes[]=$r?"<span class='ok'>✅ Status ENUM updated</span>":"<span class='warn'>⚠️ ENUM: ".mysqli_error($conn)."</span>";

// 3. Create delivery_locations if not exists
$r=mysqli_query($conn,"CREATE TABLE IF NOT EXISTS delivery_locations (
  id            INT AUTO_INCREMENT PRIMARY KEY,
  delivery_id   INT NOT NULL,
  order_id      INT NOT NULL,
  lat           DECIMAL(10,8) NOT NULL,
  lng           DECIMAL(11,8) NOT NULL,
  delivery_name  VARCHAR(100) DEFAULT NULL,
  delivery_phone VARCHAR(15)  DEFAULT NULL,
  updated_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY uq (delivery_id, order_id)
)");
$fixes[]=$r?"<span class='ok'>✅ delivery_locations table OK</span>":"<span class='err'>❌ ".mysqli_error($conn)."</span>";

// 4. Create contacts table if not exists
$r=mysqli_query($conn,"CREATE TABLE IF NOT EXISTS contacts (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT DEFAULT NULL,
  role ENUM('farmer','shop','guest') DEFAULT 'guest',
  name VARCHAR(100) NOT NULL,
  phone VARCHAR(15) NOT NULL,
  email VARCHAR(150) DEFAULT NULL,
  subject VARCHAR(200) NOT NULL,
  message TEXT NOT NULL,
  status ENUM('new','read','resolved') DEFAULT 'new',
  admin_reply TEXT DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");
$fixes[]=$r?"<span class='ok'>✅ contacts table OK</span>":"<span class='warn'>⚠️ ".mysqli_error($conn)."</span>";

// 5. Create admins table and default admin
$r=mysqli_query($conn,"CREATE TABLE IF NOT EXISTS admins (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL
)");
$fixes[]=$r?"<span class='ok'>✅ admins table OK</span>":"<span class='warn'>⚠️ ".mysqli_error($conn)."</span>";

$adm=mysqli_fetch_assoc(mysqli_query($conn,"SELECT id FROM admins WHERE username='admin'"));
if(!$adm){
  $hash=password_hash('admin123',PASSWORD_DEFAULT);
  mysqli_query($conn,"INSERT INTO admins (username,password) VALUES ('admin','$hash')");
  $fixes[]="<span class='ok'>✅ Default admin created (admin/admin123)</span>";
} else {
  $fixes[]="<span class='ok'>✅ Admin account exists</span>";
}


// 7. Add delivery_code to orders
$dc=mysqli_query($conn,"SHOW COLUMNS FROM orders LIKE 'delivery_code'");
if(mysqli_num_rows($dc)==0){
  $r=mysqli_query($conn,"ALTER TABLE orders ADD COLUMN delivery_code VARCHAR(6) DEFAULT NULL");
  $fixes[]=$r?"<span class='ok'>✅ Added delivery_code column</span>":"<span class='err'>❌ ".mysqli_error($conn)."</span>";
} else {
  $fixes[]="<span class='ok'>✅ delivery_code column exists</span>";
}

// 8. Add description column to products (if missing)
$dd=mysqli_query($conn,"SHOW COLUMNS FROM products LIKE 'description'");
if(mysqli_num_rows($dd)==0){
  $r=mysqli_query($conn,"ALTER TABLE products ADD COLUMN description TEXT DEFAULT NULL AFTER name");
  $fixes[]=$r?"<span class='ok'>✅ Added description column to products</span>":"<span class='err'>❌ ".mysqli_error($conn)."</span>";
} else {
  $fixes[]="<span class='ok'>✅ products.description column exists</span>";
}

// 9. Add vehicle_number to users (for delivery boys)
$vn=mysqli_query($conn,"SHOW COLUMNS FROM users LIKE 'vehicle_number'");
if(mysqli_num_rows($vn)==0){
  $r=mysqli_query($conn,"ALTER TABLE users ADD COLUMN vehicle_number VARCHAR(20) DEFAULT NULL");
  $fixes[]=$r?"<span class='ok'>✅ Added vehicle_number column to users</span>":"<span class='err'>❌ ".mysqli_error($conn)."</span>";
} else {
  $fixes[]="<span class='ok'>✅ vehicle_number column exists</span>";
}

// 6. Add language column to users if missing
$lc=mysqli_query($conn,"SHOW COLUMNS FROM users LIKE 'language'");
if(mysqli_num_rows($lc)==0){
  $r=mysqli_query($conn,"ALTER TABLE users ADD COLUMN language VARCHAR(5) DEFAULT 'en' AFTER role");
  $fixes[]=$r?"<span class='ok'>✅ Added language column</span>":"<span class='warn'>⚠️ ".mysqli_error($conn)."</span>";
} else {
  $fixes[]="<span class='ok'>✅ language column OK</span>";
}

// 7. Show current orders
echo "<h3>Results:</h3>";
foreach($fixes as $f) echo $f."<br>";

echo "<h3>📊 Current Orders:</h3>";
$orders=mysqli_query($conn,"SELECT o.id,o.status,o.delivery_id,f.name fname,s.shop_name FROM orders o JOIN users f ON f.id=o.farmer_id JOIN users s ON s.id=o.shop_id ORDER BY o.id DESC LIMIT 10");
if(mysqli_num_rows($orders)==0){
  echo "<span class='warn'>No orders yet</span><br>";
} else {
  echo "<table border=1 cellpadding=5 style='border-color:#37d67a;color:#eee'>";
  echo "<tr><th>ID</th><th>Farmer</th><th>Shop</th><th>Status</th><th>Delivery ID</th></tr>";
  while($o=mysqli_fetch_assoc($orders)){
    echo "<tr><td>#".$o['id']."</td><td>".htmlspecialchars($o['fname'])."</td><td>".htmlspecialchars($o['shop_name'])."</td><td style='color:".($o['status']==='accepted'?'#37d67a':'#ffc107')."'>".$o['status']."</td><td>".($o['delivery_id']??'—')."</td></tr>";
  }
  echo "</table>";
}

echo "<br><h3>🔗 Go To:</h3>";
echo "<a href='delivery/dashboard.php' style='color:#37d67a'>🛵 Delivery Dashboard</a><br>";
echo "<a href='farmer/dashboard.php'  style='color:#37d67a'>👨‍🌾 Farmer Dashboard</a><br>";
echo "<a href='shop/dashboard.php'    style='color:#37d67a'>🏪 Shop Dashboard</a><br>";
?>
