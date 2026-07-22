<?php
session_start();
if (isset($_SESSION['user_id']) && $_SESSION['user_role'] === 'admin') {
    header('Location: admin/dashboard.php');
} else {
    header('Location: admin/login/login.php');
}
exit;
