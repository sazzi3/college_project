<?php
require_once __DIR__ . '/bootstrap.php';

$user = require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect_to('my-projects.php');
}

$projectId = $_POST['project_id'] ?? '';
$visibility = $_POST['visibility'] ?? 'private';
if (!in_array($visibility, ['private', 'public', 'unlisted'], true)) {
    $visibility = 'private';
}

$projects = read_json('projects.json');
foreach ($projects as &$project) {
    if (($project['id'] ?? '') === $projectId && ($project['owner_id'] ?? '') === $user['id']) {
        $project['visibility'] = $visibility;
        $project['updated_at'] = now_text();
        add_activity_log($user['id'], 'visibility_changed', 'project', $project['title'] ?? $projectId);
        break;
    }
}
unset($project);

write_json('projects.json', $projects);
redirect_to('my-projects.php');
