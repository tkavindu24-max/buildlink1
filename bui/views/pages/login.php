<div class="form-container">
    <h2>Welcome back</h2>
    <p style="color:var(--text-muted); margin-bottom:28px;">Log in to access your dashboard and projects.</p>
    <form method="POST">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
        <div class="form-group"><label>Email address</label><input type="email" name="email" required></div>
        <div class="form-group"><label>Password</label><input type="password" name="password" required></div>
        <button type="submit" name="login" class="btn" style="width:100%; padding:14px;">Log in to dashboard</button>
    </form>
    <p style="margin-top:24px; text-align:center; color:var(--text-muted); font-size:14px;">New to BuildLink? <a href="index.php?page=register" style="color:var(--primary); font-weight:600;">Create an account</a></p>
</div>