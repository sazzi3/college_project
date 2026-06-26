<?php
require_once __DIR__ . '/bootstrap.php';

$user = require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect_to('studio.php');
}

$title = trim($_POST['title'] ?? '');
$genre = trim($_POST['genre'] ?? '');
$speaker = trim($_POST['speaker'] ?? '');
$line = trim($_POST['line'] ?? '');
$choice1 = trim($_POST['choice_1'] ?? '');
$choice2 = trim($_POST['choice_2'] ?? '');
$visibility = $_POST['visibility'] ?? 'private';
$allowedVisibility = ['private', 'public', 'unlisted'];

$errors = [];
if ($title === '') $errors[] = '작품 제목을 입력해주세요.';
if ($genre === '') $errors[] = '장르를 선택해주세요.';
if ($speaker === '') $errors[] = '캐릭터 이름을 입력해주세요.';
if ($line === '') $errors[] = '대사를 입력해주세요.';
if ($choice1 === '' || $choice2 === '') $errors[] = '선택지 2개를 모두 입력해주세요.';
if (!in_array($visibility, $allowedVisibility, true)) $visibility = 'private';

if ($errors) {
    page_header('저장 오류');
    echo '<section class="panel result-page"><h1>저장하지 못했습니다</h1>';
    foreach ($errors as $error) {
        echo '<p class="error">' . e($error) . '</p>';
    }
    echo '<div class="actions"><a class="small-button" href="studio.php">다시 작성하기</a></div></section>';
    page_footer();
    exit;
}

$projects = read_json('projects.json');
$project = [
    'id' => make_id('p'),
    'owner_id' => $user['id'],
    'title' => $title,
    'genre' => $genre,
    'speaker' => $speaker,
    'line' => $line,
    'choices' => [$choice1, $choice2],
    'visibility' => $visibility,
    'status' => 'draft',
    'created_at' => now_text(),
    'updated_at' => now_text(),
];
$projects[] = $project;
write_json('projects.json', $projects);
add_activity_log($user['id'], 'project_saved', 'project', $title);

page_header('저장 완료');
?>
<section class="panel result-page">
    <p class="success">장면이 저장되었습니다.</p>
    <h1><?= e($title) ?></h1>
    <p class="muted"><?= e(visibility_label($visibility)) ?> · <?= e($genre) ?></p>
    <p><?= e($speaker) ?>: <?= e($line) ?></p>
    <div class="actions">
        <a class="small-button primary" href="my-projects.php">내 창작물 보기</a>
        <a class="small-button" href="studio.php">계속 수정하기</a>
        <a class="small-button" href="search.php?q=<?= urlencode($title) ?>">공개 작품 보기</a>
    </div>
</section>
<?php page_footer(); ?>
