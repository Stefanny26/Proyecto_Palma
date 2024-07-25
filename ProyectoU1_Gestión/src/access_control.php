<?php
session_start();

function checkAccess($allowedRoles) {
    if (!isset($_SESSION['role'])) {
        header('Location: login.php');
        exit();
    }

    if (!in_array($_SESSION['role'], $allowedRoles)) {
        header('Location: restricted.php');
        exit();
    }
}

function hasRole($role) {
    return isset($_SESSION['role']) && $_SESSION['role'] === $role;
}
?>
