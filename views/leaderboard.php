<div class="nb-box leaderboard-box">
    <h2 class="section-title">🏆 Leaderboard</h2>

    <?php if (empty($highScores)): ?>
        <div class="empty-state">
            <p>No scores yet. Be the first!</p>
            <a href="/" class="btn-small">Play now!</a>
        </div>
    <?php else: ?>
        <div class="table-container">
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
                    foreach ($highScores as $scoreRow):
                        $isCurrentUser = !empty($isLoggedIn) && isset($username) && $scoreRow['username'] === $username;
                        ?>
                        <tr class="<?php echo $isCurrentUser ? 'current-user-row' : ''; ?>">
                            <td class="cell-strong">
                                <?php echo $rank++; ?>
                            </td>
                            <td>
                                <?php echo \htmlspecialchars((string) $scoreRow['username'], ENT_QUOTES, 'UTF-8'); ?>
                            </td>
                            <td class="cell-strong">
                                <?php echo (int) $scoreRow['score']; ?>
                            </td>
                            <td class="cell-muted">
                                <?php echo \date('M j, Y', \strtotime((string) $scoreRow['created_at'])); ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
