<?php
require_once __DIR__ . '/bootstrap.php';

$user = require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect_to('library.php');
}

$projectId = trim($_POST['project_id'] ?? '');
$sceneTitle = trim($_POST['scene_title'] ?? '');
$sceneKey = trim($_POST['scene_key'] ?? '');

if ($projectId === '' || $sceneTitle === '' || $sceneKey === '') {
    redirect_to('library.php');
}

$items = read_json('progress.json');
$updated = false;
foreach ($items as &$item) {
    if (($item['user_id'] ?? '') === $user['id'] && ($item['project_id'] ?? '') === $projectId) {
        $item['scene_title'] = $sceneTitle;
        $item['scene_key'] = $sceneKey;
        $item['updated_at'] = now_text();
        $updated = true;
        break;
    }
}
unset($item);

if (!$updated) {
    $items[] = [
        'id' => make_id('progress'),
        'user_id' => $user['id'],
        'project_id' => $projectId,
        'scene_title' => $sceneTitle,
        'scene_key' => $sceneKey,
        'created_at' => now_text(),
        'updated_at' => now_text(),
    ];
}

write_json('progress.json', $items);
add_activity_log($user['id'], 'progress_saved', 'progress', $sceneTitle);
redirect_to('library.php');
