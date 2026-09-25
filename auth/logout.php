<?php
require_once dirname(__DIR__) . '/includes/functions.php';
session_destroy();
session_start();
session_regenerate_id(true);
redirect(BASE_URL . '/index.php');
