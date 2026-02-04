<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Profile - Genius or Idiot</title>
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
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }

        th,
        td {
            padding: 1rem;
            text-align: left;
            border-bottom: var(--border-width) solid var(--border-color);
        }

        th {
            background-color: var(--primary-color);
            color: #fff;
        }

        tr:last-child td {
            border-bottom: none;
        }
    </style>
</head>

<body>
    <button id="theme-toggle" class="theme-toggle" aria-label="Toggle Theme">
        <span id="theme-icon">☀️</span>
    </button>

    <div style="position: absolute; top: 20px; right: 80px; font-family: sans-serif; z-index: 99;">
        <a href="/" style="margin-right: 15px; text-decoration: none; color: inherit; font-weight: bold;">🏠 Home</a>
        <a href="/?action=leaderboard"
            style="margin-right: 15px; text-decoration: none; color: inherit; font-weight: bold;">🏆 Leaderboard</a>
        <a href="/?action=logout" style="text-decoration: none; color: inherit; font-weight: bold;">Logout</a>
    </div>

    <div class="nb-box" style="padding: 2rem; max-width: 800px; margin: 0 auto; text-align: left;">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 2rem;">
            <h2 style="margin: 0;">👤
                <?php echo htmlspecialchars($user['username']); ?>
            </h2>
            <div class="score-value">
                <?php echo $rank; ?>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 2rem;">
            <div class="score-item" style="width: 100%;">
                <span class="score-label">BEST SCORE</span>
                <span class="score-value">
                    <?php echo $bestScore; ?>
                </span>
            </div>
            <div class="score-item" style="width: 100%;">
                <span class="score-label">JOINED</span>
                <span class="score-value" style="font-size: 1.2rem;">
                    <?php echo date('M Y', strtotime($user['created_at'])); ?>
                </span>
            </div>
        </div>

        <h3>Recent Scores</h3>
        <?php if (empty($recentScores)): ?>
            <p>No games played yet. <a href="/">Play now!</a></p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Score</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentScores as $score): ?>
                        <tr>
                            <td style="font-weight: bold;">
                                <?php echo $score['score']; ?>
                            </td>
                            <td>
                                <?php echo date('M j, Y h:i A', strtotime($score['created_at'])); ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
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
    <script data-slug="g-or-i">
        (function() {
            var slug = document.currentScript.getAttribute('data-slug');
            var link = document.createElement('a');
            link.href = 'https://ajlato.com/projects/' + slug;
            link.innerHTML = '← Back to Portfolio';
            link.style.cssText = 'position: fixed; bottom: 20px; right: 20px; background: #222; color: #fff; padding: 10px 15px; border-radius: 5px; text-decoration: none; font-family: system-ui, sans-serif; font-size: 14px; z-index: 10000; box-shadow: 0 2px 5px rgba(0,0,0,0.2); transition: transform 0.2s;';
            link.onmouseover = function() { link.style.transform = 'scale(1.05)'; };
            link.onmouseout = function() { link.style.transform = 'scale(1)'; };
            document.body.appendChild(link);
        })();
    </script>
</body>

</html>