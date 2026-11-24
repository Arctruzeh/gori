<?php
$pageTitle = $pageTitle ?? 'Genius or Idiot';
$showScoreboard = !empty($showScoreboard);
$currentAction = $currentAction ?? 'game';
$isLoggedIn = !empty($isLoggedIn);
$layoutSize = $layoutSize ?? 'default';

$bodyClass = $showScoreboard ? 'page-game' : 'page-subpage';
$layoutClass = match ($layoutSize) {
    'wide' => 'page-main--wide',
    'narrow' => 'page-main--narrow',
    default => '',
};
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?php echo \htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8'); ?></title>
    <meta name="description"
        content="A fun demo game to demonstrate OOP with PHP. Test your knowledge and see if you are a genius or an idiot!" />
    <link rel="icon" type="image/svg+xml" href="/assets/images/favicon.svg">
    <link rel="stylesheet" href="/assets/css/main.css">
    <script>
        (function () {
            const savedTheme = localStorage.getItem('theme');
            const systemDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            let theme = 'light';
            if (savedTheme) {
                theme = savedTheme;
            } else if (systemDark) {
                theme = 'dark';
            }
            document.documentElement.setAttribute('data-theme', theme);
        })();
    </script>
</head>

<body class="<?php echo $bodyClass; ?>">

    <header class="game-header">
        <?php if ($showScoreboard): ?>
            <div class="score-board">
                <div class="score-item">
                    <span class="score-label">SCORE</span>
                    <span class="score-value"><?php echo (int) $score; ?></span>
                </div>

                <div class="score-item">
                    <span class="score-label">BEST</span>
                    <span class="score-value"><?php echo (int) $bestScore; ?></span>
                </div>

                <div class="score-item">
                    <span class="score-label">RANK</span>
                    <span class="score-value rank"><?php echo \htmlspecialchars((string) $rank, ENT_QUOTES, 'UTF-8'); ?></span>
                </div>

                <div class="score-item">
                    <span class="score-label">STREAK</span>
                    <span class="score-value fire"><?php echo $streak > 0 ? '🔥 ' . (int) $streak : '0'; ?></span>
                </div>

                <form action="/" method="POST" class="score-item action" data-score="<?php echo (int) $score; ?>"
                    aria-label="Reset Score">
                    <input type="hidden" name="action" value="reset">
                    <button type="submit" class="score-reset-btn" title="Reset Score" aria-label="Reset Score">
                        🔄
                    </button>
                </form>
            </div>
        <?php else: ?>
            <a href="/" class="brand-link" title="Back to Game">
                <span class="brand-icon" aria-hidden="true">🧠</span>
                <span class="brand-text">Genius or Idiot</span>
            </a>
        <?php endif; ?>

        <div class="header-right">
            <nav class="main-nav" aria-label="Main Navigation">
                <a href="/" class="nav-link nav-link-play<?php echo $currentAction === 'game' ? ' active' : ''; ?>"
                    title="Play">
                    <span class="nav-icon" aria-hidden="true">🏠</span>
                    <span class="nav-text">Play</span>
                </a>
                <a href="/?action=leaderboard"
                    class="nav-link<?php echo $currentAction === 'leaderboard' ? ' active' : ''; ?>"
                    title="Leaderboard">
                    <span class="nav-icon" aria-hidden="true">🏆</span>
                    <span class="nav-text">Leaderboard</span>
                </a>
                <?php if ($isLoggedIn): ?>
                    <a href="/?action=profile"
                        class="nav-link<?php echo $currentAction === 'profile' ? ' active' : ''; ?>" title="Profile">
                        <span class="nav-icon" aria-hidden="true">👤</span>
                        <span class="nav-text">Profile</span>
                    </a>
                    <a href="/?action=logout" class="nav-link" title="Logout">
                        <span class="nav-icon" aria-hidden="true">👋</span>
                        <span class="nav-text">Logout</span>
                    </a>
                <?php else: ?>
                    <a href="/?action=login"
                        class="nav-link<?php echo $currentAction === 'login' ? ' active' : ''; ?>" title="Login">
                        <span class="nav-icon" aria-hidden="true">🔑</span>
                        <span class="nav-text">Login</span>
                    </a>
                    <a href="/?action=register"
                        class="nav-link<?php echo $currentAction === 'register' ? ' active' : ''; ?>" title="Register">
                        <span class="nav-icon" aria-hidden="true">📝</span>
                        <span class="nav-text">Register</span>
                    </a>
                <?php endif; ?>
            </nav>

            <button id="theme-toggle" class="theme-toggle" type="button" aria-label="Toggle Theme"
                title="Toggle Theme">
                <span id="theme-icon">☀️</span>
            </button>
        </div>
    </header>

    <main class="page-main <?php echo $layoutClass; ?>">
        <?php echo $content; ?>
    </main>

    <a href="https://ajlato.com/projects/g-or-i" class="portfolio-link">← Back to Portfolio</a>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const toggleBtn = document.getElementById('theme-toggle');
            const themeIcon = document.getElementById('theme-icon');
            const html = document.documentElement;

            function updateIcon(theme) {
                if (themeIcon) {
                    themeIcon.textContent = theme === 'dark' ? '🌙' : '☀️';
                }
            }

            updateIcon(html.getAttribute('data-theme'));

            if (toggleBtn) {
                toggleBtn.addEventListener('click', () => {
                    const current = html.getAttribute('data-theme');
                    const next = current === 'dark' ? 'light' : 'dark';
                    html.setAttribute('data-theme', next);
                    localStorage.setItem('theme', next);
                    updateIcon(next);
                });
            }

            const resetForm = document.querySelector('form.score-item.action');
            if (resetForm) {
                resetForm.addEventListener('submit', (event) => {
                    const currentScore = Number(resetForm.dataset.score || '0');
                    if (currentScore > 0 && !window.confirm('Reset your current score and streak to 0?')) {
                        event.preventDefault();
                    }
                });
            }
        });
    </script>
</body>

</html>
