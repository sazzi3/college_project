<?php
require_once __DIR__ . '/bootstrap.php';

$user = require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect_to('library.php');
}

$projectId = trim($_POST['project_id'] ?? '');
$sceneTitle = trim($_POST['scene_title'] ?? '');
$speaker = trim($_POST['speaker'] ?? '');
$line = trim($_POST['line'] ?? '');

if ($projectId === '' || $sceneTitle === '') {
    redirect_to('library.php');
}

$items = read_json('saved-scenes.json');
$items[] = [
    'id' => make_id('scene'),
    'user_id' => $user['id'],
    'project_id' => $projectId,
    'scene_title' => $sceneTitle,
    'speaker' => $speaker,
    'line' => $line,
    'created_at' => now_text(),
];

write_json('saved-scenes.json', $items);
add_activity_log($user['id'], 'scene_saved', 'scene', $sceneTitle);
redirect_to('library.php');
