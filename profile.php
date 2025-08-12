<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile - LapZone</title>
    <link rel="stylesheet" href="./styles.css/style.css">
  <style>
    .profile-container {
      max-width: 500px;
      margin: 60px auto;
      background: #fff;
      border-radius: 16px;
      box-shadow: 0 2px 16px rgba(30,144,255,0.08);
      padding: 40px 30px 30px 30px;
      text-align: center;
    }
    .profile-avatar {
      width: 110px;
      height: 110px;
      border-radius: 50%;
      object-fit: cover;
      border: 4px solid #1E90FF;
      margin-bottom: 18px;
      background: #e6f2ff;
      display: inline-block;
    }
    .profile-container h2 {
      color: #1E90FF;
      margin-bottom: 8px;
    }
    .profile-details {
      margin: 0 auto 20px auto;
      text-align: left;
      max-width: 320px;
    }
    .profile-details label {
      font-weight: bold;
      color: #008ed6;
      display: block;
      margin-top: 12px;
    }
    .profile-details span {
      color: #222;
      display: block;
      margin-top: 2px;
    }
  </style>

  <header class="header">
      <div class="logo" style="cursor:pointer;" onclick="window.location.href='index.php'">Lap<span>Zone</span></div>
      <nav class="header-right">
          <a href="index.php">Home</a>
          <a href="#" id="logoutBtn">Logout</a>
      </nav>
  </header>
  <div class="profile-container">
    <img src="./image/profile-avatar.png" alt="User Avatar" class="profile-avatar" id="profileAvatar" onerror="this.src='https://ui-avatars.com/api/?background=1E90FF&color=fff&name=User'">
    <h2 id="welcomeMsg">Welcome, User!</h2>
    <div class="profile-details">
      <label>Name</label>
      <span id="userName">-</span>
      <label>Email</label>
      <span id="userEmail">-</span>
      <label>Phone</label>
      <span id="userPhone">-</span>
      <label>Role</label>
      <span id="userRole">-</span>
      <label>Status</label>
      <span id="userStatus"><span class="profile-status">-</span></span>
      <label>Joined</label>
      <span id="userJoined">-</span>
    </div>
    <div class="profile-actions">
      <button id="logoutBtn">Logout</button>
    </div>
  </div>
<script>
document.addEventListener('DOMContentLoaded', function() {
  fetch('server.php?action=me', { credentials: 'include' })
    .then(r => r.json())
    .then(data => {
      if(data.ok && data.data) {
        const u = data.data;
        document.getElementById('welcomeMsg').textContent = 'Welcome, ' + u.name + '!';
        document.getElementById('userName').textContent = u.name;
        document.getElementById('userEmail').textContent = u.email;
        document.getElementById('userPhone').textContent = u.phone || '-';
        document.getElementById('userRole').textContent = u.role.charAt(0).toUpperCase() + u.role.slice(1);
        let statusClass = 'active';
        if(u.status === 'inactive') statusClass = 'inactive';
        if(u.status === 'banned') statusClass = 'banned';
        document.getElementById('userStatus').innerHTML = `<span class="profile-status ${statusClass}">${u.status.charAt(0).toUpperCase() + u.status.slice(1)}</span>`;
        document.getElementById('userJoined').textContent = u.created_at ? new Date(u.created_at).toLocaleDateString() : '-';
      } else {
        window.location.href = 'login.php';
      }
    })
    .catch(() => window.location.href = 'login.php');
  document.getElementById('logoutBtn').onclick = function() {
    fetch('server.php?action=logout', { credentials: 'include' })
      .then(() => window.location.href = 'login.php');
  };
});
</script>
</body>
</html>
