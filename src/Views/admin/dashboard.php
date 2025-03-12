<div class="container">
    <h1>Welcome to your Dashboard, <?= htmlspecialchars($username) ?>!</h1>

    <p>This is your personal dashboard where you can manage your account and see your details.</p>

    <ul>
        <li><a href="/profile">View Profile</a></li>
        <li><a href="/settings">Account Settings</a></li>
        <li><a href="/logout">Logout</a></li>
    </ul>
</div>