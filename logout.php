<?php
require_once __DIR__ . '/config/koneksi.php';
require_once __DIR__ . '/includes/fungsi.php';
$_SESSION = [];
session_destroy();
redirect('login.php');
