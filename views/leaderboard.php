<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Leaderboard - Genius or Idiot</title>
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

        tr:hover {
            background-color: rgba(0, 0, 0, 0.05);
        }

        [data-theme="dark"] tr:hover {
            background-color: rgba(255, 255, 255, 0.05);
        }
    </style>
</head>

<body>
    <button id="theme-toggle" class="theme-toggle" aria-label="Toggle Theme">
        <span id="theme-icon">☀️</span>
    </button>

    <div style="position: absolute; top: 20px; right: 80px; font-family: sans-serif; z-index: 99;">
        <a href="/" style="margin-right: 15px; text-decoration: none; color: inherit; font-weight: bold;">🏠 Home</a>
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="/?action=profile"
                style="margin-right: 15px; text-decoration: none; color: inherit; font-weight: bold;">👤 Profile</a>
            <a href="/?action=logout" style="text-decoration: none; color: inherit; font-weight: bold;">Logout</a>
        <?php else: ?>
            <a href="/?action=login"
                style="margin-right: 10px; text-decoration: none; color: inherit; font-weight: bold;">Login</a>
        <?php endif; ?>
    </div>

    <div class="nb-box" style="padding: 2rem; max-width: 800px; margin: 0 auto; text-align: left;">
        <h2 style="text-align: center; margin-bottom: 2rem;">🏆 Leaderboard</h2>

        <?php if (empty($highScores)): ?>
            <p style="text-align: center;">No scores yet. Be the first!</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Rank</th>
                        <th>Player</th>
                        <th>Score</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $rank = 1;
                    foreach ($highScores as $score): ?>
                        <tr>
                            <td style="font-weight: bold;">
                                <?php echo $rank++; ?>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($score['username']); ?>
                            </td>
                            <td style="font-weight: bold;">
                                <?php echo $score['score']; ?>
                            </td>
                            <td style="font-size: 0.9em;">
                                <?php echo date('M j, Y', strtotime($score['created_at'])); ?>
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
</body>

</html>