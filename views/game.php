<?php

use App\Domain\Feedback;

/** @var Feedback|null $feedback */
?>
<?php if ($feedback instanceof Feedback): ?>
    <div class="message-container" id="message-container">
        <div class="alert alert-<?php echo \htmlspecialchars($feedback->variant, ENT_QUOTES, 'UTF-8'); ?>">
            <?php echo \htmlspecialchars($feedback->text, ENT_QUOTES, 'UTF-8'); ?>
            <?php if ($feedback->correctAnswer !== null): ?>
                The correct answer was <strong><?php echo $feedback->correctAnswer; ?></strong>.
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>

<div id="game-content">
    <div class="question-text">
        <h3><?php echo \htmlspecialchars((string) $question, ENT_QUOTES, 'UTF-8'); ?></h3>
    </div>

    <form name="form1" action="/" method="POST" class="choices-grid">
        <input type="hidden" name="token" value="<?php echo \htmlspecialchars((string) $token, ENT_QUOTES, 'UTF-8'); ?>">
        <?php foreach (\array_values($choices) as $index => $choice): ?>
            <button type="submit" name="useranswer" value="<?php echo (int) $choice; ?>"
                class="choice-btn" data-key="<?php echo $index + 1; ?>">
                <span class="choice-key" aria-hidden="true"><?php echo $index + 1; ?></span>
                <span class="choice-value"><?php echo (int) $choice; ?></span>
            </button>
        <?php endforeach; ?>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const container = document.getElementById('message-container');
        const gameContent = document.getElementById('game-content');

        if (container && gameContent) {
            gameContent.style.display = 'none';

            const isError = container.querySelector('.alert-danger') !== null;
            const displayTime = isError ? 1600 : 900;

            setTimeout(() => {
                container.classList.add('fade-out');
                setTimeout(() => {
                    container.remove();
                    gameContent.style.display = '';
                    gameContent.style.animation = 'fadeIn 0.3s ease-out both';
                }, 300);
            }, displayTime);
        }

        document.addEventListener('keydown', (event) => {
            if (event.metaKey || event.ctrlKey || event.altKey) {
                return;
            }
            const tag = event.target && event.target.tagName ? event.target.tagName.toLowerCase() : '';
            if (tag === 'input' || tag === 'textarea' || tag === 'select') {
                return;
            }
            if (!gameContent || gameContent.style.display === 'none') {
                return;
            }
            if (event.key >= '1' && event.key <= '4') {
                const btn = gameContent.querySelector('.choice-btn[data-key="' + event.key + '"]');
                if (btn) {
                    event.preventDefault();
                    btn.click();
                }
            }
        });
    });
</script>
