<?php
// product_details.php
$pdo = new PDO('mysql:host=127.0.0.1;dbname=lapzone;charset=utf8mb4', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$product = null;
if ($id > 0) {
    $stmt = $pdo->prepare('SELECT * FROM products WHERE id = ?');
    $stmt->execute([$id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
}
if (!$product) {
    echo '<h2 style="color:red;text-align:center;">Product not found.</h2>';
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?> - Details | LapZone</title>
    <link rel="stylesheet" href="./styles.css/productdetailspage.css">
</head>
<body>
    <header class="header">
        <div class="logo" style="cursor:pointer;" onclick="window.location.href='index.php'">Lap<span>Zone</span></div>
        <nav>
            <a href="index.php">Home</a>
            <a href="profile.php">Profile</a>
        </nav>
    </header>
    <div class="product-container">
        <div class="product-image">
            <img src="./image/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
        </div>
        <div class="product-details">
            <h2><?php echo htmlspecialchars($product['name']); ?></h2>
            <div class="model">Brand: <?php echo htmlspecialchars($product['brand']); ?></div>
            <div class="model">Model: <?php echo htmlspecialchars($product['model']); ?></div>
            <div class="price-section">
                <?php if ($product['old_price']): ?><span class="regular-price">$<?php echo htmlspecialchars($product['old_price']); ?></span><?php endif; ?>
                <span class="price">$<?php echo htmlspecialchars($product['price']); ?></span>
            </div>
            <div class="quantity-section">
                <label for="quantity">Quantity:</label>
                <button type="button" id="decreaseQty">-</button>
                <input type="number" id="quantity" value="1" min="1" style="width:40px;text-align:center;">
                <button type="button" id="increaseQty">+</button>
            </div>
            <div class="key-features">
                <h4>Key Features</h4>
                <ul>
                    <li>High performance</li>
                    <li>Latest model</li>
                    <li>Warranty included</li>
                </ul>
            </div>
            <a class="buy-now" id="buyNowBtn" href="#">Buy Now</a>
        </div>
    </div>
    <div class="tabs" style="margin: 30px 40px 0 40px;">
        <button class="tab-btn active" onclick="showTab('desc')">Description</button>
        <button class="tab-btn" onclick="showTab('spec')">Specifications</button>
        <button class="tab-btn" onclick="showTab('review')">Reviews</button>
    </div>
    <div id="tab-desc" class="tab-content" style="padding: 20px 40px;">
        <h3>Description</h3>
        <p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
    </div>
    <div id="tab-spec" class="tab-content" style="display:none;padding: 20px 40px;">
        <h3>Specifications</h3>
        <table border="1" cellpadding="8" style="border-collapse:collapse;width:100%;max-width:600px;">
            <tr><th>Display</th><td>15.6" Full HD</td></tr>
            <tr><th>Processor</th><td>Intel Core i7</td></tr>
            <tr><th>RAM</th><td>16GB DDR4</td></tr>
            <tr><th>Storage</th><td>512GB SSD</td></tr>
            <tr><th>Graphics</th><td>NVIDIA GTX 1650</td></tr>
            <tr><th>Battery</th><td>8 hours</td></tr>
        </table>
    </div>
    <div id="tab-review" class="tab-content" style="display:none;padding: 20px 40px;">
        <h3>Reviews</h3>
        <p>No reviews yet. Be the first to review this product!</p>
    </div>
    <footer>
        <div class="footer-top">
            <ul>
                <li><a href="index.php" style="color:white;">Home</a></li>
                <li><a href="profile.php" style="color:white;">Profile</a></li>
            </ul>
        </div>
    </footer>
<script>
// Quantity selector
document.getElementById('decreaseQty').onclick = function() {
    var qty = document.getElementById('quantity');
    if (parseInt(qty.value) > 1) qty.value = parseInt(qty.value) - 1;
};
document.getElementById('increaseQty').onclick = function() {
    var qty = document.getElementById('quantity');
    qty.value = parseInt(qty.value) + 1;
};
// Buy Now button passes quantity
document.getElementById('buyNowBtn').onclick = function(e) {
    e.preventDefault();
    var qty = document.getElementById('quantity').value;
    window.location.href = 'buy_now.php?id=<?php echo $product['id']; ?>&qty=' + encodeURIComponent(qty);
};
// Tabs
function showTab(tab) {
    document.getElementById('tab-desc').style.display = (tab === 'desc') ? '' : 'none';
    document.getElementById('tab-spec').style.display = (tab === 'spec') ? '' : 'none';
    document.getElementById('tab-review').style.display = (tab === 'review') ? '' : 'none';
    var btns = document.querySelectorAll('.tab-btn');
    btns.forEach(function(btn, i) {
        btn.classList.remove('active');
        if ((tab === 'desc' && i === 0) || (tab === 'spec' && i === 1) || (tab === 'review' && i === 2)) btn.classList.add('active');
    });
}
</script>
</body>
</html>
