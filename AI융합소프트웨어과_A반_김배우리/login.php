<?php
require_once __DIR__ . '/bootstrap.php';

$errors = [];
$email = '';
$next = $_GET['next'] ?? $_POST['next'] ?? 'index.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = strtolower(trim($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';

    if ($email === '') {
        $errors[] = '이메일을 입력해주세요.';
    }
    if ($password === '') {
        $errors[] = '비밀번호를 입력해주세요.';
    }

    $user = find_user_by_email($email);
    if (!$errors && (!$user || !password_verify($password, $user['password_hash'] ?? ''))) {
        $errors[] = '이메일 또는 비밀번호가 올바르지 않습니다.';
    }

    if (!$errors && $user) {
        $_SESSION['user_id'] = $user['id'];
        add_activity_log($user['id'], 'login', 'user', $user['nickname']);
        redirect_to($next ?: 'index.php');
    }
}

page_header('로그인');
?>
<section class="panel">
    <h1>로그인</h1>
    <p class="muted">저장한 장면과 내 창작물을 이어서 관리합니다.</p>

    <?php foreach ($errors as $error): ?>
        <p class="error"><?= e($error) ?></p>
    <?php endforeach; ?>

    <form class="form-grid" method="post">
        <input type="hidden" name="next" value="<?= e($next) ?>">
        <label>이메일
            <input type="email" name="email" value="<?= e($email) ?>" required>
        </label>
        <label>비밀번호
            <input type="password" name="password" required>
        </label>
        <button class="small-button primary" type="submit">로그인</button>
    </form>
    <div class="actions">
        <a class="small-button" href="signup.php">회원가입</a>
        <a class="small-button" href="studio.php">비회원으로 샘플 보기</a>
    </div>
</section>
<?php page_footer(); ?>
