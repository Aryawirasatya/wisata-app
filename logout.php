<?php
session_start();

// Menghapus sesi
session_unset();
session_destroy();

// Mengarahkan ke halaman login
header("Location: login.php");
exit;
