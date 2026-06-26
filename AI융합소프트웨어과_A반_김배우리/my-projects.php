<?php
require_once __DIR__ . '/bootstrap.php';

$user = require_login();
$projects = array_values(array_filter(read_json('projects.json'), fn($project) => ($project['owner_id'] ?? '') === $user['id']));

page_header('내 창작물');
?>
<section class="panel">
    <h1>내 창작물</h1>
    <p class="muted">내가 만든 공개 작품, 비공개 초안, 링크 공개 작품을 모두 확인합니다.</p>
    <div class="actions">
        <a class="small-button primary" href="studio.php">새 장면 만들기</a>
    </div>
</section>

<section class="grid" style="margin-top: 18px;">
    <?php if (!$projects): ?>
        <div class="card">
            <h3>아직 저장한 창작물이 없습니다.</h3>
            <p class="muted">장면 만들기에서 첫 장면을 저장해보세요.</p>
        </div>
    <?php endif; ?>

    <?php foreach ($projects as $project): ?>
        <?php $visibility = $project['visibility'] ?? 'private'; ?>
        <article class="card">
            <span class="badge <?= e($visibility) ?>"><?= e(visibility_label($visibility)) ?></span>
            <h3><?= e($project['title'] ?? '제목 없음') ?></h3>
            <p class="muted"><?= e($project['genre'] ?? '') ?> · <?= e($project['updated_at'] ?? '') ?></p>
            <p><?= e($project['speaker'] ?? '') ?>: <?= e($project['line'] ?? '') ?></p>
            <div class="actions">
                <a class="small-button" href="studio.php">수정하기</a>
                <form method="post" action="update-project-visibility.php">
                    <input type="hidden" name="project_id" value="<?= e($project['id'] ?? '') ?>">
                    <input type="hidden" name="visibility" value="public">
                    <button class="small-button" type="submit">공개로 전환</button>
                </form>
                <form method="post" action="update-project-visibility.php">
                    <input type="hidden" name="project_id" value="<?= e($project['id'] ?? '') ?>">
                    <input type="hidden" name="visibility" value="private">
                    <button class="small-button" type="submit">비공개로 전환</button>
                </form>
            </div>
        </article>
    <?php endforeach; ?>
</section>
<?php page_footer(); ?>
