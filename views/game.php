<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Genius or Idiot</title>
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

<body>

    <button id="theme-toggle" class="theme-toggle" aria-label="Toggle Theme">
        <span id="theme-icon">☀️</span>
    </button>

    <div class="score-board">
        <div class="score-item">
            <span class="score-label">SCORE</span>
            <span class="score-value">
                <?php echo $score; ?>
            </span>
        </div>

        <div class="score-item">
            <span class="score-label">BEST</span>
            <span class="score-value">
                <?php echo $bestScore; ?>
            </span>
        </div>

        <div class="score-item">
            <span class="score-label">RANK</span>
            <span class="score-value rank">
                <?php echo $rank; ?>
            </span>
        </div>

        <div class="score-item">
            <span class="score-label">STREAK</span>
            <span class="score-value fire">
                <?php echo $streak > 0 ? "🔥 $streak" : '0'; ?>
            </span>
        </div>

        <form action="" method="POST" class="score-item action" aria-label="Reset Score">
            <input type="hidden" name="action" value="reset">
            <button type="submit" class="score-reset-btn" title="Reset Score">
                🔄
            </button>
        </form>
    </div>

    <?php if ($message): ?>
        <div class="message-container" id="message-container">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <div id="game-content">
        <div class="question-text">
            <h3>
                <?php echo $question; ?>
            </h3>
        </div>

        <br>

        <form name="form1" action="" method="POST" class="choices-grid">
            <input type="hidden" name="token" value="<?php echo $token; ?>">
            <?php foreach ($choices as $choice): ?>
                <button type="submit" name="useranswer" value="<?php echo $choice; ?>" class="choice-btn">
                    <?php echo $choice; ?>
                </button>
            <?php endforeach; ?>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const container = document.getElementById('message-container');
            const gameContent = document.getElementById('game-content');

            if (container) {
                if (gameContent) gameContent.style.display = 'none';

                const isError = container.querySelector('.alert-danger') !== null;
                const displayTime = isError ? 1600 : 900;

                setTimeout(() => {
                    container.classList.add('fade-out');
                    setTimeout(() => {
                        container.remove();
                        if (gameContent) {
                            gameContent.style.display = 'block';
                            gameContent.style.animation = 'fadeIn 0.3s ease-out both';
                            const input = gameContent.querySelector('input');
                            if (input) input.focus();
                        }
                    }, 300);
                }, 900);
            }

            const toggleBtn = document.getElementById('theme-toggle');
            const themeIcon = document.getElementById('theme-icon');
            const html = document.documentElement;

            function updateIcon(theme) {
                themeIcon.textContent = theme === 'dark' ? '🌙' : '☀️';
            }

            updateIcon(html.getAttribute('data-theme'));

            toggleBtn.addEventListener('click', () => {
                const current = html.getAttribute('data-theme');
                const next = current === 'dark' ? 'light' : 'dark';

                html.setAttribute('data-theme', next);
                localStorage.setItem('theme', next);
                updateIcon(next);
            });
        });

    </script>
</body>

</html>