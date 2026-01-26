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

    <div style="position: absolute; top: 20px; right: 80px; font-family: sans-serif; z-index: 99;">
        <a href="/" style="margin-right: 15px; text-decoration: none; color: inherit; font-weight: bold;">🏠 Home</a>
        <a href="/?action=leaderboard" style="text-decoration: none; color: inherit; font-weight: bold;">🏆
            Leaderboard</a>
    </div>

    <div class="nb-box" style="padding: 2rem; max-width: 400px; margin: 0 auto; text-align: left;">
        <h2 style="text-align: center; margin-bottom: 2rem;">Register</h2>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger" style="margin-bottom: 1rem; min-width: auto;">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form action="/?action=do_register" method="POST">
            <div style="margin-bottom: 1rem;">
                <label for="username" style="display: block; font-weight: bold; margin-bottom: 0.5rem;">Username</label>
                <input type="text" id="username" name="username" required
                    style="width: 100%; padding: 0.5rem; border: var(--border-width) solid var(--border-color); border-radius: var(--border-radius); background: var(--input-bg); color: var(--text-color); font-size: 1rem;">
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label for="password" style="display: block; font-weight: bold; margin-bottom: 0.5rem;">Password</label>
                <input type="password" id="password" name="password" required
                    style="width: 100%; padding: 0.5rem; border: var(--border-width) solid var(--border-color); border-radius: var(--border-radius); background: var(--input-bg); color: var(--text-color); font-size: 1rem;">
            </div>

            <button type="submit" class="choice-btn"
                style="width: 100%; font-size: 1.5rem; aspect-ratio: unset; padding: 0.5rem;">Register</button>
        </form>

        <div style="text-align: center; margin-top: 1.5rem;">
            <p>Already have an account? <a href="/?action=login"
                    style="color: var(--primary-color); font-weight: bold;">Login</a></p>
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