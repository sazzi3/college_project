<?php
// varchar JSON 기반 프로토타입 공통 부트스트랩입니다.
session_start();

date_default_timezone_set('Asia/Seoul');

const DATA_DIR = __DIR__ . '/data';
const JSON_FLAGS = JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT;

function ensure_data_file(string $filename): string
{
    if (!is_dir(DATA_DIR)) {
        mkdir(DATA_DIR, 0775, true);
    }

    $path = DATA_DIR . '/' . basename($filename);
    if (!file_exists($path)) {
        file_put_contents($path, json_encode([], JSON_FLAGS), LOCK_EX);
    }

    return $path;
}

function read_json(string $filename): array
{
    $path = ensure_data_file($filename);
    $json = file_get_contents($path);
    $data = json_decode($json ?: '[]', true);
    return is_array($data) ? $data : [];
}

function write_json(string $filename, array $data): void
{
    $path = ensure_data_file($filename);
    file_put_contents($path, json_encode(array_values($data), JSON_FLAGS), LOCK_EX);
}

function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function now_text(): string
{
    return date('Y-m-d H:i:s');
}

function make_id(string $prefix): string
{
    return $prefix . '_' . str_replace('.', '', uniqid('', true));
}

function find_user_by_id(?string $id): ?array
{
    if (!$id) {
        return null;
    }

    foreach (read_json('users.json') as $user) {
        if (($user['id'] ?? '') === $id) {
            return $user;
        }
    }

    return null;
}

function find_user_by_email(string $email): ?array
{
    $email = strtolower(trim($email));
    foreach (read_json('users.json') as $user) {
        if (strtolower($user['email'] ?? '') === $email) {
            return $user;
        }
    }

    return null;
}

function current_user(): ?array
{
    return find_user_by_id($_SESSION['user_id'] ?? null);
}

function is_logged_in(): bool
{
    return current_user() !== null;
}

function require_login(): array
{
    $user = current_user();
    if (!$user) {
        header('Location: login.php?next=' . urlencode($_SERVER['REQUEST_URI'] ?? 'index.php'));
        exit;
    }

    return $user;
}

function add_activity_log(string $userId, string $action, string $targetType, string $targetTitle): void
{
    $logs = read_json('activity-log.json');
    array_unshift($logs, [
        'id' => make_id('log'),
        'user_id' => $userId,
        'action' => $action,
        'target_type' => $targetType,
        'target_title' => $targetTitle,
        'created_at' => now_text(),
    ]);
    write_json('activity-log.json', $logs);
}

function visibility_label(string $visibility): string
{
    if ($visibility === 'public') {
        return '공개 작품';
    }
    if ($visibility === 'unlisted') {
        return '링크 공개';
    }
    return '비공개 초안';
}

function redirect_to(string $url): void
{
    header('Location: ' . $url);
    exit;
}

function page_header(string $title): void
{
    $user = current_user();
    ?>
    <!DOCTYPE html>
    <html lang="ko">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?= e($title) ?> - varchar</title>
        <link rel="stylesheet" href="styles.css">
    </head>
    <body>
        <header class="site-header">
            <a class="brand" href="index.php">varchar</a>
            <nav class="site-nav">
                <a href="index.php">홈</a>
                <a href="search.php">랭킹/검색</a>
                <a href="studio.php">창작하기</a>
                <?php if ($user): ?>
                    <a href="library.php">내 서재</a>
                    <a href="my-projects.php">내 창작물</a>
                    <a href="activity-log.php">활동 기록</a>
                    <a href="logout.php">로그아웃</a>
                <?php else: ?>
                    <a href="login.php">로그인</a>
                    <a href="signup.php">회원가입</a>
                <?php endif; ?>
            </nav>
            <?php if ($user): ?>
                <div class="user-chip"><?= e($user['nickname']) ?>님</div>
            <?php endif; ?>
        </header>
        <main class="page-shell">
    <?php
}

function page_footer(): void
{
    ?>
        </main>
    </body>
    </html>
    <?php
}
