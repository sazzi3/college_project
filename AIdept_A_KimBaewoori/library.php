<?php
require_once __DIR__ . '/bootstrap.php';

$user = require_login();
$progressItems = array_values(array_filter(read_json('progress.json'), fn($item) => ($item['user_id'] ?? '') === $user['id']));
$sceneItems = array_values(array_filter(read_json('saved-scenes.json'), fn($item) => ($item['user_id'] ?? '') === $user['id']));
$projects = read_json('projects.json');

function project_title_by_id(array $projects, string $id): string
{
    foreach ($projects as $project) {
        if (($project['id'] ?? '') === $id) {
            return $project['title'] ?? $id;
        }
    }
    return $id ?: '샘플 작품';
}

page_header('내 서재');
?>
<section class="panel">
    <h1>내 서재</h1>
    <p class="muted">진행 기록과 저장한 장면을 확인합니다. 아직 실제 뷰어와 완전히 연결하지 않은 학습용 프로토타입입니다.</p>
    <div class="actions">
        <form method="post" action="save-progress.php">
            <input type="hidden" name="project_id" value="sample_project">
            <input type="hidden" name="scene_title" value="샘플 장면">
            <input type="hidden" name="scene_key" value="sample_scene">
            <button class="small-button" type="submit">샘플 진행 저장</button>
        </form>
        <form method="post" action="save-scene.php">
            <input type="hidden" name="project_id" value="sample_project">
            <input type="hidden" name="scene_title" value="저장한 샘플 장면">
            <input type="hidden" name="speaker" value="리아">
            <input type="hidden" name="line" value="이 장면을 내 서재에 보관할게요.">
            <button class="small-button" type="submit">샘플 장면 저장</button>
        </form>
    </div>
</section>

<section class="grid" style="margin-top: 18px;">
    <div class="panel">
        <h2>이어보기 기록</h2>
        <?php if (!$progressItems): ?>
            <p class="muted">아직 이어보기 기록이 없습니다.</p>
        <?php endif; ?>
        <div class="stack">
            <?php foreach ($progressItems as $item): ?>
                <div class="card">
                    <h3><?= e(project_title_by_id($projects, $item['project_id'] ?? '')) ?></h3>
                    <p class="muted"><?= e($item['scene_title'] ?? '') ?> · <?= e($item['updated_at'] ?? '') ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="panel">
        <h2>저장한 장면</h2>
        <?php if (!$sceneItems): ?>
            <p class="muted">아직 저장한 장면이 없습니다.</p>
        <?php endif; ?>
        <div class="stack">
            <?php foreach ($sceneItems as $item): ?>
                <div class="card">
                    <h3><?= e($item['scene_title'] ?? '') ?></h3>
                    <p class="muted"><?= e(project_title_by_id($projects, $item['project_id'] ?? '')) ?> · <?= e($item['created_at'] ?? '') ?></p>
                    <p><?= e($item['speaker'] ?? '') ?>: <?= e($item['line'] ?? '') ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php page_footer(); ?>
