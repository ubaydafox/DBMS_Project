<?php
// buy_now.php
$pdo = new PDO('mysql:host=127.0.0.1;dbname=lapzone;charset=utf8mb4', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$qty = isset($_GET['qty']) && intval($_GET['qty']) > 0 ? intval($_GET['qty']) : 1;
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
    <title>Buy Now - <?php echo htmlspecialchars($product['name']); ?> | LapZone</title>
    <link rel="stylesheet" href="./styles.css/cheackoutpage.css">
</head>
<body>
    <header class="header">
        <div class="logo" style="cursor:pointer;" onclick="window.location.href='index.php'">Lap<span>Zone</span></div>
        <nav class="header-right">
            <a href="index.php">Home</a>
            <a href="profile.php">Profile</a>
        </nav>
    </header>
    <div class="checkout-container">
        <div class="checkout-left">
            <h2>Shipping Information</h2>
            <form id="checkoutForm">
                <input type="text" name="fullname" placeholder="Full Name" required>
                <input type="email" name="email" placeholder="Email Address" required>
                <input type="text" name="address" placeholder="Shipping Address" required>
                <input type="text" name="phone" placeholder="Phone Number" required>
                <button type="submit" class="confirm">Place Order</button>
            </form>
        </div>
        <div class="checkout-right">
            <div class="order-overview">
                <h3>Order Overview</h3>
                <table>
                    <tr>
                        <th>Product</th>
                        <th>Qty</th>
                        <th>Price</th>
                        <th>Total</th>
                    </tr>
                    <tr>
                        <td>
                            <img src="./image/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" style="width:60px;vertical-align:middle;"> <br>
                            <?php echo htmlspecialchars($product['name']); ?>
                        </td>
                        <td>
                            <button type="button" id="decreaseQty">-</button>
                            <input type="number" id="qty" name="qty" value="<?php echo $qty; ?>" min="1" style="width:40px;text-align:center;">
                            <button type="button" id="increaseQty">+</button>
                        </td>
                        <td>$<span id="unitPrice"><?php echo htmlspecialchars($product['price']); ?></span></td>
                        <td>$<span id="totalPrice"><?php echo htmlspecialchars($product['price']); ?></span></td>
                    </tr>
                </table>
                <div class="summary">
                    <p><span>Subtotal:</span> <span id="subtotal">$<?php echo htmlspecialchars($product['price']); ?></span></p>
                    <p><span>Shipping:</span> <span id="shipping">$10.00</span></p>
                    <p class="total"><span>Total:</span> <span id="grandTotal">$<?php echo htmlspecialchars($product['price']) + 10; ?></span></p>
                </div>
            </div>
        </div>
    </div>
    <script>
    // Update total price on quantity change
    var qtyInput = document.getElementById('qty');
    var unitPrice = parseFloat(document.getElementById('unitPrice').textContent);
    var totalPrice = document.getElementById('totalPrice');
    var subtotal = document.getElementById('subtotal');
    var grandTotal = document.getElementById('grandTotal');
    var shipping = 10.00;
    function updateTotals() {
        var qty = parseInt(qtyInput.value) || 1;
        if (qty < 1) qty = 1;
        qtyInput.value = qty;
        var total = (unitPrice * qty).toFixed(2);
        totalPrice.textContent = total;
        subtotal.textContent = '$' + total;
        grandTotal.textContent = '$' + (parseFloat(total) + shipping).toFixed(2);
    }
    qtyInput.addEventListener('input', updateTotals);
    document.getElementById('decreaseQty').onclick = function() {
        qtyInput.value = Math.max(1, parseInt(qtyInput.value) - 1);
        updateTotals();
    };
    document.getElementById('increaseQty').onclick = function() {
        qtyInput.value = parseInt(qtyInput.value) + 1;
        updateTotals();
    };
    // Set initial totals based on qty from URL
    updateTotals();
    // Handle form submit
    document.getElementById('checkoutForm').onsubmit = function(e) {
        e.preventDefault();
        alert('Order placed successfully! (Demo only)');
        window.location.href = 'index.php';
    };
    </script>
</body>
</html>
