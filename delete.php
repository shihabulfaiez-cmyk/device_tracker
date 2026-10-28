<?php
require_once 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$current_user_id = $_SESSION['user_id'];
$is_super_admin  = (isset($_SESSION['role']) && $_SESSION['role'] === 'super_admin');

$device_id = intval($_GET['id'] ?? 0);

if ($device_id > 0) {
    // Check device existence and ownership
    $stmt = $pdo->prepare("SELECT user_id FROM devices WHERE id = :id");
    $stmt->execute([':id' => $device_id]);
    $device = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($device) {
        // Enforce role permission
        if ($is_super_admin || $device['user_id'] == $current_user_id) {
            $delete_stmt = $pdo->prepare("DELETE FROM devices WHERE id = :id");
            $delete_stmt->execute([':id' => $device_id]);
        }
    }
}

header("Location: list.php");
exit();