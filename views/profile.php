<div class="nb-box leaderboard-box">
    <div class="profile-header">
        <h2 class="profile-username">👤 <?php echo \htmlspecialchars((string) $user['username'], ENT_QUOTES, 'UTF-8'); ?></h2>
        <div class="score-value profile-rank">
            <?php echo \htmlspecialchars((string) $rank, ENT_QUOTES, 'UTF-8'); ?>
        </div>
    </div>

    <div class="profile-stats">
        <div class="profile-stat-card">
            <span class="score-label">BEST SCORE</span>
            <span class="score-value"><?php echo (int) $bestScore; ?></span>
        </div>
        <div class="profile-stat-card">
            <span class="score-label">JOINED</span>
            <span class="score-value profile-joined">
                <?php echo \date('M Y', \strtotime((string) $user['created_at'])); ?>
            </span>
        </div>
    </div>

    <h3 class="section-subtitle">Recent Scores</h3>
    <?php if (empty($recentScores)): ?>
        <div class="empty-state">
            <p>No games played yet.</p>
            <a href="/" class="btn-small">Play now!</a>
        </div>
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
                    <?php foreach ($recentScores as $scoreRow): ?>
                        <tr>
                            <td class="cell-strong">
                                <?php echo (int) $scoreRow['score']; ?>
                            </td>
                            <td>
                                <?php echo \date('M j, Y h:i A', \strtotime((string) $scoreRow['created_at'])); ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
