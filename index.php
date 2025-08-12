<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LapZone - Home</title>
    <link rel="stylesheet" href="./styles.css/style.css">
</head>
<body>
    <header class="header">
        <div class="logo" style="cursor:pointer;" onclick="window.location.href='index.php'">Lap<span>Zone</span></div>
        <div class="search-bar">
            <input type="text" id="searchInput" placeholder="Search laptops, accessories...">
            <button id="searchBtn" style="background:none;border:none;cursor:pointer;">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor"><circle cx="11" cy="11" r="8" stroke-width="2"/><line x1="21" y1="21" x2="16.65" y2="16.65" stroke-width="2"/></svg>
            </button>
        </div>
        <nav class="header-right">
            <a href="login.php">Login</a>
            <a href="singup.php">Signup</a>
            <a href="profile.php" title="Profile" style="margin-left:10px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor"><circle cx="12" cy="8" r="4" stroke-width="2"/><path d="M4 20c0-4 4-7 8-7s8 3 8 7" stroke-width="2"/></svg>
            </a>
        </nav>
    </header>
    <section class="banner">
        <h1>Welcome to LapZone</h1>
        <p>Your one-stop shop for Laptops & Accessories</p>
    </section>
    <?php
    // Connect to the lapzone database
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=lapzone;charset=utf8mb4', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch laptops
    $laptops = $pdo->query("SELECT * FROM products WHERE category='laptop' ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
    // Fetch accessories
    $accessories = $pdo->query("SELECT * FROM products WHERE category='accessory' ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
    ?>
    <main>
        <section class="product-section">
            <div class="section-header">
                <h2>Laptops</h2>
            </div>
            <div class="product-grid">
                <?php foreach ($laptops as $p): ?>
                <div class="product-card">
                    <img src="./image/<?php echo htmlspecialchars($p['image']); ?>" alt="<?php echo htmlspecialchars($p['name']); ?>">
                    <h4><?php echo htmlspecialchars($p['name']); ?></h4>
                    <div class="model">Brand: <?php echo htmlspecialchars($p['brand']); ?></div>
                    <div class="model">Model: <?php echo htmlspecialchars($p['model']); ?></div>
                    <?php if ($p['old_price']): ?><div class="old-price">$<?php echo htmlspecialchars($p['old_price']); ?></div><?php endif; ?>
                    <div class="new-price">$<?php echo htmlspecialchars($p['price']); ?></div>
                    <a class="btn-primary" href="product_details.php?id=<?php echo $p['id']; ?>">View Details</a>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
        <section class="product-section">
            <div class="section-header">
                <h2>Accessories</h2>
            </div>
            <div class="product-grid">
                <?php foreach ($accessories as $p): ?>
                <div class="product-card">
                    <img src="./image/<?php echo htmlspecialchars($p['image']); ?>" alt="<?php echo htmlspecialchars($p['name']); ?>">
                    <h4><?php echo htmlspecialchars($p['name']); ?></h4>
                    <div class="model">Brand: <?php echo htmlspecialchars($p['brand']); ?></div>
                    <div class="model">Model: <?php echo htmlspecialchars($p['model']); ?></div>
                    <?php if ($p['old_price']): ?><div class="old-price">$<?php echo htmlspecialchars($p['old_price']); ?></div><?php endif; ?>
                    <div class="new-price">$<?php echo htmlspecialchars($p['price']); ?></div>
                    <button class="btn-primary">View Details</button>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
</html>
<script>
// Optional: Search bar functionality (basic filter)
document.getElementById('searchBtn').onclick = function(e) {
    e.preventDefault();
    var val = document.getElementById('searchInput').value.toLowerCase();
    var cards = document.querySelectorAll('.product-card');
    cards.forEach(function(card) {
        var text = card.textContent.toLowerCase();
        card.style.display = text.includes(val) ? '' : 'none';
    });
};
</script>
    </main>
    <footer class="footer">
        <div class="footer-column">
            <h2>LapZone</h2>
            <ul>
                <li><a href="index.php" style="color:white;">Home</a></li>
                <li><a href="login.php" style="color:white;">Login</a></li>
                <li><a href="singup.php" style="color:white;">Signup</a></li>
            </ul>
        </div>
        <div class="footer-column">
            <h3>Contact</h3>
            <ul>
                <li>Email: support@lapzone.com</li>
                <li>Phone: +1 234 567 890</li>
            </ul>
        </div>
    </footer>
</body>
</html>
