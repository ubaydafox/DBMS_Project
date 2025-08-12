<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>LapZone Login</title>
  <link rel="stylesheet" href="./styles.css/login.css" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
</head>
<body>
  <div class="container">
    <div class="left-side">
      <h1 class="logo">LapZone</h1>
      <h2>Welcome Back</h2>
      <p class="subtitle">Login to your account</p>
      <form id="loginForm">
        <input type="hidden" name="action" value="login">
        <label for="email">Email Address</label>
        <input type="email" id="email" name="email" placeholder="Enter your email" required />
        
        <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="Enter your password" required />
        
        <button type="submit">LOGIN</button>
      </form>
      <div id="loginMessage" style="margin-top:15px;font-weight:bold;"></div>
      <a href="#" class="forgot">Forgot Password</a>
  <p class="signup-text">Don't have an account? <a href="./singup.php" class="signup-link">Signup</a></p>
    </div>
    <div class="right-side">
      <img src="./image/Untitled (720 x 1024 px).png" alt="Login Illustration" />
    </div>
  </div>
<script>
// Handle login form submission via AJAX
document.getElementById('loginForm').addEventListener('submit', async function(e) {
  e.preventDefault();
  const form = e.target;
  const formData = new FormData(form);
  const params = new URLSearchParams();
  for (const [key, value] of formData.entries()) {
    params.append(key, value);
  }
  const msgDiv = document.getElementById('loginMessage');
  msgDiv.textContent = '';
  try {
    const res = await fetch('server.php', {
      method: 'POST',
      headers: { 'Accept': 'application/json' },
      body: params
    });
    const data = await res.json();
    if (data.ok && data.data && data.data.user) {
      msgDiv.style.color = 'green';
      msgDiv.textContent = 'Login successful! Redirecting...';
      setTimeout(function() {
        window.location.href = 'index.php';
      }, 1000);
    } else {
      msgDiv.style.color = 'red';
      msgDiv.textContent = data.data && data.data.message ? data.data.message : 'Login failed.';
    }
  } catch (err) {
    msgDiv.style.color = 'red';
    msgDiv.textContent = 'Network or server error.';
  }
});
</script>
</body>
</html>
