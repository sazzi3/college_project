<?php
require_once __DIR__ . '/bootstrap.php';

$projects = array_filter(read_json('projects.json'), fn($project) => ($project['visibility'] ?? '') === 'public');

page_header('장면이 살아나는 웹소설');
?>
<section class="panel hero">
    <p class="badge">인터랙티브 웹소설</p>
    <h1>읽는 순간, 장면이 되는 웹소설</h1>
    <p class="muted">선택지와 엔딩, 저장한 장면까지 이어지는 varchar의 PHP 프로토타입입니다.</p>
    <div class="actions">
        <a class="small-button primary" href="search.php">공개 작품 둘러보기</a>
        <a class="small-button" href="studio.php">장면 만들기</a>
        <?php if (!is_logged_in()): ?>
            <a class="small-button" href="signup.php">무료로 시작하기</a>
        <?php endif; ?>
    </div>
</section>

<section class="stack" style="margin-top: 22px;">
    <div class="panel">
        <h2>공개 작품</h2>
        <p class="muted">검색과 홈에는 공개 상태인 작품만 표시됩니다.</p>
    </div>

    <div class="grid">
        <?php if (!$projects): ?>
            <div class="card">
                <h3>아직 공개 작품이 없습니다.</h3>
                <p class="muted">로그인 후 장면을 만들고 공개로 저장하면 여기에 나타납니다.</p>
            </div>
        <?php endif; ?>

        <?php foreach ($projects as $project): ?>
            <article class="card">
                <span class="badge public"><?= e(visibility_label($project['visibility'] ?? 'private')) ?></span>
                <h3><?= e($project['title'] ?? '제목 없음') ?></h3>
                <p class="muted"><?= e($project['genre'] ?? '장르 없음') ?> · <?= e($project['speaker'] ?? '화자 없음') ?></p>
                <p><?= e($project['line'] ?? '') ?></p>
                <div class="actions">
                    <a class="small-button" href="search.php?q=<?= urlencode($project['title'] ?? '') ?>">보기</a>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</section>
<?php page_footer(); ?>
