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

</head>

<body>
    <button id="theme-toggle" class="theme-toggle" aria-label="Toggle Theme">
        <span id="theme-icon">☀️</span>
    </button>

    <div class="nav-header">
        <a href="/" class="nav-link">🏠 Home</a>
        <a href="/?action=leaderboard" class="nav-link">🏆 Leaderboard</a>
        <a href="/?action=logout" class="nav-link">Logout</a>
    </div>

    <div class="leaderboard-box">
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
            <p>No games played yet. <a href="/" class="btn-small">Play now!</a></p>
        <?php else: ?>
            <div class="table-container">
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
            </div>
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
</body>

</html>