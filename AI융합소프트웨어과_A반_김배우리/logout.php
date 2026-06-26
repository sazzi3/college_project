<?php
require_once __DIR__ . '/bootstrap.php';

session_unset();
session_destroy();
redirect_to('index.php');
