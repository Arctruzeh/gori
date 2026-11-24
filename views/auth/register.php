<div class="nb-box auth-box">
    <h2 class="section-title">Register</h2>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger auth-alert">
            <?php echo \htmlspecialchars((string) $error, ENT_QUOTES, 'UTF-8'); ?>
        </div>
    <?php endif; ?>

    <form action="/?action=do_register" method="POST">
        <div class="form-group">
            <label for="username" class="form-label">Username</label>
            <input type="text" id="username" name="username" required autocomplete="username" class="form-input">
        </div>

        <div class="form-group last">
            <label for="password" class="form-label">Password</label>
            <input type="password" id="password" name="password" required autocomplete="new-password"
                class="form-input">
        </div>

        <button type="submit" class="auth-btn">Register</button>
    </form>

    <div class="auth-link-container">
        <p>Already have an account? <a href="/?action=login" class="auth-link">Login</a></p>
    </div>
</div>
