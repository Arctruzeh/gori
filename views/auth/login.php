<div class="nb-box auth-box">
    <h2 class="section-title">Login</h2>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger auth-alert">
            <?php echo \htmlspecialchars((string) $error, ENT_QUOTES, 'UTF-8'); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div class="alert alert-success auth-alert">
            <?php echo \htmlspecialchars((string) $success, ENT_QUOTES, 'UTF-8'); ?>
        </div>
    <?php endif; ?>

    <form action="/?action=do_login" method="POST">
        <div class="form-group">
            <label for="username" class="form-label">Username</label>
            <input type="text" id="username" name="username" required autocomplete="username" class="form-input">
        </div>

        <div class="form-group last">
            <label for="password" class="form-label">Password</label>
            <input type="password" id="password" name="password" required autocomplete="current-password"
                class="form-input">
        </div>

        <button type="submit" class="auth-btn">Login</button>
    </form>

    <div class="auth-link-container">
        <p>Don't have an account? <a href="/?action=register" class="auth-link">Register</a></p>
    </div>
</div>
