<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
require_once 'db.php';

$stmt = $pdo->query("SELECT * FROM devices ORDER BY id ASC");
$devices = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Device Tracker - List</title>
    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <!-- Top Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm">
        <div class="container-fluid px-4">
            <a class="navbar-brand fw-bold" href="list.php">Device Tracker</a>
            <div class="navbar-nav ms-auto flex-row align-items-center">
                <span class="navbar-text me-3"><?= htmlspecialchars($_SESSION['email']) ?> | </span>
                <a href="logout.php" class="btn btn-outline-secondary btn-sm">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container my-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="fw-bold"></h3>
            <a href="create.php" class="btn btn-success btn-sm px-3">+ Add Device</a>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th scope="col" class="ps-3">#</th>
                                <th scope="col">Name</th>
                                <th scope="col">Type</th>
                                <th scope="col">IP Address</th>
                                <th scope="col">Location</th>
                                <th scope="col">Status</th>
                                <th scope="col" class="text-end pe-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($devices) > 0): ?>
                                <?php foreach ($devices as $index => $device): ?>
                                    <tr>
                                        <th scope="row" class="ps-3"><?= $index + 1 ?></th>
                                        <td><?= htmlspecialchars($device['name']) ?></td>
                                        <td><?= htmlspecialchars($device['type']) ?></td>
                                        <td><?= htmlspecialchars($device['ip_address']) ?></td>
                                        <td><?= htmlspecialchars($device['location']) ?></td>
                                        <td>
                                            <?php if ($device['status'] === 'active'): ?>
                                                <span class="text-success">&#9679;</span> Active
                                            <?php else: ?>
                                                <span class="text-secondary">&#9679;</span> Inactive
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-end pe-3">
                                            <a href="edit.php?id=<?= $device['id'] ?>" class="text-primary text-decoration-none me-3">Edit</a>
                                            <a href="delete.php?id=<?= $device['id'] ?>" class="text-danger text-decoration-none" onclick="return confirm('Delete this device? \n\n<?= htmlspecialchars($device['name']) ?> will be permanently removed.');">Delete</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">
                                        No rows: "No devices found. Click 'Add Device' to create one."
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>