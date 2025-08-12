<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LapZone - Create Account</title>
    <link rel="stylesheet" href="./styles.css/singup.css">
</head>

<body>
    <div class="container">

        <!-- Left Side - Form -->
        <div class="form-section">
            <!-- Logo at Top -->
            <div class="logo">Lap<span>Zone</span></div>

            <!-- Form Content -->
            <div class="form-content">
                <h1>Create Your Account</h1>
                <p class="sub-text">Register Account</p>



                <form id="signupForm">
                    <input type="hidden" name="action" value="register">

                    <label>Name</label>
                    <input type="text" name="name" placeholder="Enter your full Name" required>

                    <label>Email Address</label>
                    <input type="email" name="email" placeholder="Enter your email" required>

                    <label>Phone / Telephone</label>
                    <input type="tel" name="phone" placeholder="Enter your phone number">

                    <label>Password</label>
                    <input type="password" name="password" placeholder="Enter your password" required>

                    <button type="submit" class="btn">SIGN UP</button>
                </form>
                <div id="signupMessage" style="margin-top:15px;font-weight:bold;"></div>

                <p class="login-link">
                    If You Already Have an Account! <a href="./login.php">Login page</a>
                </p>
            </div>
        </div>

        <!-- Right Side - Image -->
        <div class="image-section">
            <img src="./image/Untitled 2.png" alt="Illustration">
        </div>
    </div>

<script>
// Handle signup form submission via AJAX
document.getElementById('signupForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);
    const params = new URLSearchParams();
    for (const [key, value] of formData.entries()) {
        params.append(key, value);
    }
    const msgDiv = document.getElementById('signupMessage');
    msgDiv.textContent = '';
    try {
        const res = await fetch('server.php', {
            method: 'POST',
            headers: { 'Accept': 'application/json' },
            body: params
        });
        const data = await res.json();
        if (data.ok) {
            msgDiv.style.color = 'green';
            msgDiv.textContent = 'Signup successful! You can now login.';
            form.reset();
        } else {
            msgDiv.style.color = 'red';
            msgDiv.textContent = data.data && data.data.message ? data.data.message : 'Signup failed.';
        }
    } catch (err) {
        msgDiv.style.color = 'red';
        msgDiv.textContent = 'Network or server error.';
    }
});
</script>
</body>

</html>