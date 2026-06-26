<?php
require_once __DIR__ . '/bootstrap.php';

$user = current_user();
$q = trim($_GET['q'] ?? '');
$projects = read_json('projects.json');

$results = array_values(array_filter($projects, function ($project) use ($q, $user) {
    $visibility = $project['visibility'] ?? 'private';
    $isOwner = $user && (($project['owner_id'] ?? '') === $user['id']);
    $canSee = $visibility === 'public' || ($isOwner && in_array($visibility, ['private', 'unlisted'], true));
    if (!$canSee) {
        return false;
    }
    if ($q === '') {
        return $visibility === 'public';
    }
    $haystack = implode(' ', [
        $project['title'] ?? '',
        $project['genre'] ?? '',
        $project['speaker'] ?? '',
        $project['line'] ?? '',
    ]);
    return stripos($haystack, $q) !== false;
}));

page_header('검색');
?>
<section class="panel">
    <h1>작품 검색</h1>
    <p class="muted">기본적으로 공개 작품만 검색합니다. 로그인하면 내 비공개 초안과 링크 공개 작품도 결과에 포함됩니다.</p>
    <form class="actions" method="get">
        <input name="q" value="<?= e($q) ?>" placeholder="제목, 장르, 캐릭터, 대사 검색">
        <button class="small-button primary" type="submit">검색</button>
    </form>
</section>

<section class="grid" style="margin-top: 18px;">
    <?php if (!$results): ?>
        <div class="card">
            <h3>검색 결과가 없습니다.</h3>
            <p class="muted">공개 작품이 없거나 검색어와 일치하는 작품이 없습니다.</p>
        </div>
    <?php endif; ?>

    <?php foreach ($results as $project): ?>
        <?php $visibility = $project['visibility'] ?? 'private'; ?>
        <article class="card">
            <span class="badge <?= e($visibility) ?>"><?= e(visibility_label($visibility)) ?></span>
            <h3><?= e($project['title'] ?? '제목 없음') ?></h3>
            <p class="muted"><?= e($project['genre'] ?? '') ?> · <?= e($project['speaker'] ?? '') ?></p>
            <p><?= e($project['line'] ?? '') ?></p>
            <div class="actions">
                <a class="small-button" href="search.php?q=<?= urlencode($project['title'] ?? '') ?>">보기</a>
            </div>
        </article>
    <?php endforeach; ?>
</section>
<?php page_footer(); ?>
