<?php

session_start();

// Simple Autoloader
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = __DIR__ . '/src/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

$message = '';
$messageType = '';

// Initialize Score and Streak
if (!isset($_SESSION['score'])) {
    $_SESSION['score'] = 0;
}
if (!isset($_SESSION['streak'])) {
    $_SESSION['streak'] = 0;
}

use App\Game;
use App\ScoreManager;

$scoreManager = new ScoreManager();

// Handle Reset Request
if (isset($_POST['action']) && $_POST['action'] === 'reset') {
    $scoreManager->resetGame();
    // Redirect to clear post data so refresh doesn't re-trigger
    header("Location: ./");
    exit;
}

// Initialize Pending Answers
if (!isset($_SESSION['pending_answers'])) {
    $_SESSION['pending_answers'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['useranswer'])) {
    $useranswer = $_POST['useranswer'] ?? '';
    // Retrieve the token from the form
    $token = $_POST['token'] ?? '';

    // Get the correct answer associated with this specific question token
    $correctAnswer = $_SESSION['pending_answers'][$token] ?? '';

    if ($useranswer == '') {
        $message = '<div class="alert alert-warning">Put something in...</div>';
        $messageType = 'empty';
    } elseif ($useranswer == $correctAnswer) {
        $geniusMessages = [
            "You are a genius!",
            "You are a total genius!",
            "Absolute genius!",
            "Look at you, what a genius.",
            "Clearly, you are a genius.",
            "You are a genius, obviously.",
            "Pure genius!",
            "A true genius indeed!",
            "Behold, a genius!"
        ];
        $selectedGenius = $geniusMessages[array_rand($geniusMessages)];
        $message = '<div class="alert alert-success">' . $selectedGenius . '</div>';
        $messageType = 'success';

        $scoreManager->recordSuccess();
    } else {
        $idiotMessages = [
            "You are an idiot!",
            "You are such an idiot!",
            "Absolute idiot!",
            "Total idiot move.",
            "You are an idiot, clearly.",
            "Don't be such an idiot!",
            "What an idiot!",
            "You complete idiot!",
            "Maximum idiot level reached."
        ];
        $selectedIdiot = $idiotMessages[array_rand($idiotMessages)];
        $message = '<div class="alert alert-danger">' . $selectedIdiot . ' The correct answer was <strong>' . $correctAnswer . '</strong>.</div>';
        $messageType = 'error';

        $scoreManager->recordFailure();
    }

    // Optional: cleanup used token so it can't be replayed
    // unset($_SESSION['pending_answers'][$token]);
}

$score = $scoreManager->getScore();
$streak = $scoreManager->getStreak();


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Genius or Idiot</title>
    <meta name="description"
        content="A fun demo game to demonstrate OOP with PHP. Test your knowledge and see if you are a genius or an idiot!" />
    <link rel="stylesheet" href="/assets/css/main.css">
    <script>
        // Immediately invoke to set theme before render (avoids flash)
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

    <?php
    $rank = $scoreManager->getRank();
    $bestScore = $scoreManager->getHighScore();

    // Check if we just hit a new high score this turn
    $isRecord = ($score > 0 && $score >= $bestScore);
    ?>

    <div class="score-board">
        <div class="score-item">
            <span class="score-label">SCORE</span>
            <span class="score-value"><?php echo $score; ?></span>
        </div>

        <div class="score-item">
            <span class="score-label">BEST</span>
            <span class="score-value"><?php echo $bestScore; ?></span>
        </div>

        <div class="score-item">
            <span class="score-label">RANK</span>
            <span class="score-value rank"><?php echo $rank; ?></span>
        </div>

        <div class="score-item">
            <span class="score-label">STREAK</span>
            <span class="score-value fire"><?php echo $streak > 0 ? '🔥 ' . $streak : '0'; ?></span>
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

    <?php
    $game = new Game();
    $roundData = $game->generateQuestion();

    // Map to variables
    $q1 = $roundData['question'];
    $a1 = $roundData['answer'];

    // Generate unique token for this question
    $token = uniqid();
    $_SESSION['pending_answers'][$token] = $a1;

    // Cleanup old tokens to prevent session bloat
    if (count($_SESSION['pending_answers']) > 20) {
        $_SESSION['pending_answers'] = array_slice($_SESSION['pending_answers'], -20, 20, true);
    }

    // Get choices for UI
    $choices = $roundData['choices'];
    ?>

    <div id="game-content">
        <div class="question-text">
            <h3><?php echo $q1; ?></h3>
        </div>

        <br>

        <!-- Action is empty to submit to same page -->
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
                // If there is a message, hide the game content initially
                if (gameContent) gameContent.style.display = 'none';

                const isError = container.querySelector('.alert-danger') !== null;
                const displayTime = isError ? 1600 : 900; // Give more time to read the answer if wrong

                // Fade out after displayTime
                setTimeout(() => {
                    container.classList.add('fade-out');
                    // Remove from DOM after animation completes (0.3s)
                    setTimeout(() => {
                        container.remove();

                        // Bring the game content back
                        if (gameContent) {
                            gameContent.style.display = 'block';
                            gameContent.style.animation = 'fadeIn 0.3s ease-out both';

                            // Re-focus the input
                            const input = gameContent.querySelector('input');
                            if (input) input.focus();
                        }
                    }, 300);
                }, 900);
            }

            // -- Theme Toggle Logic --
            const toggleBtn = document.getElementById('theme-toggle');
            const themeIcon = document.getElementById('theme-icon');
            const html = document.documentElement;

            // Update icon based on current state
            function updateIcon(theme) {
                themeIcon.textContent = theme === 'dark' ? '🌙' : '☀️';
            }

            // Init icon
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