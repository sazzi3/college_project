<?php
require_once __DIR__ . '/bootstrap.php';

$errors = [];
$nickname = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nickname = trim($_POST['nickname'] ?? '');
    $email = strtolower(trim($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';
    $passwordConfirm = $_POST['password_confirm'] ?? '';

    if ($nickname === '') {
        $errors[] = '닉네임을 입력해주세요.';
    }
    if (strpos($email, '@') === false) {
        $errors[] = '이메일 형식을 확인해주세요.';
    }
    if (strlen($password) < 8) {
        $errors[] = '비밀번호는 8자 이상이어야 합니다.';
    }
    if ($password !== $passwordConfirm) {
        $errors[] = '비밀번호 확인이 일치하지 않습니다.';
    }
    if (find_user_by_email($email)) {
        $errors[] = '이미 가입된 이메일입니다.';
    }

    if (!$errors) {
        $users = read_json('users.json');
        $user = [
            'id' => make_id('u'),
            'nickname' => $nickname,
            'email' => $email,
            'password_hash' => password_hash($password, PASSWORD_DEFAULT),
            'role' => 'reader',
            'created_at' => now_text(),
        ];
        $users[] = $user;
        write_json('users.json', $users);
        $_SESSION['user_id'] = $user['id'];
        add_activity_log($user['id'], 'signup', 'user', $nickname);
        redirect_to('index.php');
    }
}

page_header('회원가입');
?>
<section class="panel">
    <h1>varchar 시작하기</h1>
    <p class="muted">계정을 만들면 이어보기, 엔딩 기록, 저장한 장면과 창작물을 보관할 수 있어요.</p>

    <?php foreach ($errors as $error): ?>
        <p class="error"><?= e($error) ?></p>
    <?php endforeach; ?>

    <form class="form-grid" method="post">
        <label>닉네임
            <input name="nickname" value="<?= e($nickname) ?>" required>
        </label>
        <label>이메일
            <input type="email" name="email" value="<?= e($email) ?>" required>
        </label>
        <label>비밀번호
            <input type="password" name="password" minlength="8" required>
        </label>
        <label>비밀번호 확인
            <input type="password" name="password_confirm" minlength="8" required>
        </label>
        <button class="small-button primary" type="submit">무료로 시작하기</button>
    </form>
</section>
<?php page_footer(); ?>
