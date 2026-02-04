<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Register - Genius or Idiot</title>
    <link rel="icon" type="image/svg+xml" href="/assets/images/favicon.svg">
    <link rel="stylesheet" href="/assets/css/main.css">
    <script>
        (function () {
            const savedTheme = localStorage.getItem('theme');
            const systemDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            let theme = 'light';
            if (savedTheme) theme = savedTheme;
            else if (systemDark) theme = 'dark';
            document.documentElement.setAttribute('data-theme', theme);
        })();
    </script>
</head>

<body>
    <button id="theme-toggle" class="theme-toggle" aria-label="Toggle Theme">
        <span id="theme-icon">☀️</span>
    </button>

    <div class="nav-header">
        <a href="/" class="nav-link">🏠 Home</a>
        <a href="/?action=leaderboard" class="nav-link">🏆 Leaderboard</a>
    </div>

    <div class="nb-box auth-box">
        <h2 style="text-align: center; margin-bottom: 2rem;">Register</h2>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger" style="margin-bottom: 1rem; min-width: auto;">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form action="/?action=do_register" method="POST">
            <div class="form-group">
                <label for="username" class="form-label">Username</label>
                <input type="text" id="username" name="username" required class="form-input">
            </div>

            <div class="form-group last">
                <label for="password" class="form-label">Password</label>
                <input type="password" id="password" name="password" required class="form-input">
            </div>

            <button type="submit" class="choice-btn auth-btn">Register</button>
        </form>

        <div class="auth-link-container">
            <p>Already have an account? <a href="/?action=login" class="auth-link">Login</a></p>
        </div>
    </div>

    <script>
        const toggleBtn = document.getElementById('theme-toggle');
        const themeIcon = document.getElementById('theme-icon');
        const html = document.documentElement;
        function updateIcon(theme) { themeIcon.textContent = theme === 'dark' ? '🌙' : '☀️'; }
        updateIcon(html.getAttribute('data-theme'));
        toggleBtn.addEventListener('click', () => {
            const current = html.getAttribute('data-theme');
            const next = current === 'dark' ? 'light' : 'dark';
            html.setAttribute('data-theme', next);
            localStorage.setItem('theme', next);
            updateIcon(next);
        });
    </script>
</body>

</html>