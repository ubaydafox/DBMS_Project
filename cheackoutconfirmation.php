<?php
// cheackoutconfirmation.php
$order = null;
if (isset($_GET['name'], $_GET['email'], $_GET['address'], $_GET['phone'], $_GET['product'], $_GET['qty'], $_GET['total'])) {
    $order = [
        'name' => $_GET['name'],
        'email' => $_GET['email'],
        'address' => $_GET['address'],
        'phone' => $_GET['phone'],
        'product' => $_GET['product'],
        'qty' => intval($_GET['qty']),
        'total' => $_GET['total'],
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation | LapZone</title>
    <link rel="stylesheet" href="./styles.css/cheackoutconfirmation.css">
</head>
<body>
    <header class="header">
        <div class="logo" style="cursor:pointer;" onclick="window.location.href='index.php'">Lap<span>Zone</span></div>
        <nav class="header-right">
            <a href="index.php">Home</a>
            <a href="profile.php">Profile</a>
        </nav>
    </header>
    <div class="success-container">
        <?php if ($order): ?>
        <h2>Order Confirmed!</h2>
        <h3>Thank you, <?php echo htmlspecialchars($order['name']); ?>!</h3>
        <p>Your order for <strong><?php echo htmlspecialchars($order['product']); ?></strong> (Qty: <?php echo $order['qty']; ?>) has been placed.</p>
        <p>Total Amount: <strong>$<?php echo htmlspecialchars($order['total']); ?></strong></p>
        <p>Shipping to: <?php echo htmlspecialchars($order['address']); ?></p>
        <p>Confirmation sent to: <span class="phone"><?php echo htmlspecialchars($order['email']); ?></span></p>
        <button class="continue-btn" onclick="window.location.href='index.php'">Continue Shopping</button>
        <?php else: ?>
        <h2>Order Not Found</h2>
        <p>No order details available.</p>
        <button class="continue-btn" onclick="window.location.href='index.php'">Back to Home</button>
        <?php endif; ?>
    </div>
    <footer>
        <div class="footer-section">
            <ul>
                <li><a href="index.php" style="color:white;">Home</a></li>
                <li><a href="profile.php" style="color:white;">Profile</a></li>
            </ul>
        </div>
    </footer>
</body>
</html>
