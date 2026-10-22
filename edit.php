<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
require_once 'db.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: list.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM devices WHERE id = ?");
$stmt->execute([$id]);
$device = $stmt->fetch();

if (!$device) {
    header("Location: list.php");
    exit;
}

$errors = [];
$name = $device['name'];
$type = $device['type'];
$ip_address = $device['ip_address'];
$location = $device['location'];
$status = $device['status'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $type = trim($_POST['type'] ?? '');
    $ip_address = trim($_POST['ip_address'] ?? '');
    $location = trim($_POST['location'] ?? '');
    $status = trim($_POST['status'] ?? 'active');

    if (empty($name)) {
        $errors['name'] = "Name is required";
    }
    if (empty($ip_address) || !filter_var($ip_address, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
        $errors['ip_address'] = "Must be a valid IPv4 address";
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("UPDATE devices SET name = ?, type = ?, ip_address = ?, location = ?, status = ? WHERE id = ?");
        $stmt->execute([$name, $type, $ip_address, $location, $status, $id]);
        header("Location: list.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Device</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm">
        <div class="container-fluid px-4">
            <a class="navbar-brand fw-bold" href="list.php">Device Tracker</a>
            <div class="navbar-nav ms-auto flex-row align-items-center">
                <span class="navbar-text me-3"><?= htmlspecialchars($_SESSION['email']) ?> | </span>
                <a href="logout.php" class="btn btn-outline-secondary btn-sm">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container my-5" style="max-width: 600px;">
        <div class="card border-0 shadow-sm p-4">
            <h3 class="mb-4 fw-bold">Edit Device</h3>
            
            <form action="edit.php?id=<?= $id ?>" method="POST" novalidate>
                <div class="mb-3">
                    <label for="name" class="form-label">Name *</label>
                    <input type="text" class="form-control <?= isset($errors['name']) ? 'is-invalid' : '' ?>" id="name" name="name" value="<?= htmlspecialchars($name) ?>">
                    <?php if (isset($errors['name'])): ?>
                        <div class="invalid-feedback"><?= $errors['name'] ?></div>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label for="type" class="form-label">Type *</label>
                    <select class="form-select" id="type" name="type">
                        <option value="router" <?= $type === 'router' ? 'selected' : '' ?>>router</option>
                        <option value="switch" <?= $type === 'switch' ? 'selected' : '' ?>>switch</option>
                        <option value="access_point" <?= $type === 'access_point' ? 'selected' : '' ?>>access_point</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="ip_address" class="form-label">IP Address *</label>
                    <input type="text" class="form-control <?= isset($errors['ip_address']) ? 'is-invalid' : '' ?>" id="ip_address" name="ip_address" value="<?= htmlspecialchars($ip_address) ?>">
                    <?php if (isset($errors['ip_address'])): ?>
                        <div class="invalid-feedback"><?= $errors['ip_address'] ?></div>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label for="location" class="form-label">Location</label>
                    <input type="text" class="form-control" id="location" name="location" value="<?= htmlspecialchars($location) ?>">
                </div>

                <div class="mb-4">
                    <label for="status" class="form-label">Status *</label>
                    <select class="form-select" id="status" name="status">
                        <option value="active" <?= $status === 'active' ? 'selected' : '' ?>>active</option>
                        <option value="inactive" <?= $status === 'inactive' ? 'selected' : '' ?>>inactive</option>
                    </select>
                </div>

                <div class="d-flex align-items-center">
                    <button type="submit" class="btn btn-primary px-4 me-3">Update</button>
                    <a href="list.php" class="text-decoration-none text-primary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>