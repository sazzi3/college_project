<?php
require_once __DIR__ . '/bootstrap.php';

$user = require_login();
$logs = array_values(array_filter(read_json('activity-log.json'), fn($log) => ($log['user_id'] ?? '') === $user['id']));

page_header('활동 기록');
?>
<section class="panel">
    <h1>활동 기록</h1>
    <p class="muted">회원가입, 로그인, 작품 저장, 공개 상태 변경, 진행 저장, 장면 저장을 간단히 기록합니다.</p>
</section>

<section class="stack" style="margin-top: 18px;">
    <?php if (!$logs): ?>
        <div class="card">
            <h3>아직 활동 기록이 없습니다.</h3>
            <p class="muted">작품을 저장하거나 서재에 장면을 보관하면 여기에 표시됩니다.</p>
        </div>
    <?php endif; ?>

    <?php foreach ($logs as $log): ?>
        <article class="card">
            <span class="badge"><?= e($log['action'] ?? '') ?></span>
            <h3><?= e($log['target_title'] ?? '') ?></h3>
            <p class="muted"><?= e($log['target_type'] ?? '') ?> · <?= e($log['created_at'] ?? '') ?></p>
        </article>
    <?php endforeach; ?>
</section>
<?php page_footer(); ?>
