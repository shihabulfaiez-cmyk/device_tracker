<?php
require_once 'db.php';
require_once 'includes/header.php';

// Auth Guard: User must be logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$current_user_id = $_SESSION['user_id'];
$is_super_admin  = (isset($_SESSION['role']) && $_SESSION['role'] === 'super_admin');

// Search & Filter Inputs
$status_filter = trim($_GET['status'] ?? '');
$search_query  = trim($_GET['search'] ?? '');

// Base SQL
$sql = "SELECT d.*, u.name AS owner_name 
        FROM devices d 
        LEFT JOIN users u ON d.user_id = u.id 
        WHERE 1=1";

$params = [];

// Ownership Restriction: Standard users only see their own devices
if (!$is_super_admin) {
    $sql .= " AND d.user_id = :user_id";
    $params[':user_id'] = $current_user_id;
}

// Optional Filters
if (!empty($status_filter)) {
    $sql .= " AND d.status = :status";
    $params[':status'] = $status_filter;
}

if (!empty($search_query)) {
    $sql .= " AND (d.device_name LIKE :search OR d.brand LIKE :search OR d.serial_number LIKE :search)";
    $params[':search'] = '%' . $search_query . '%';
}

$sql .= " ORDER BY d.id DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$devices = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Device Inventory</h2>
            <p class="text-muted mb-0">
                <?= $is_super_admin ? 'Viewing all registered assets (Super Admin)' : 'Viewing your assigned devices' ?>
            </p>
        </div>
        <a href="create.php" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i> Add New Device
        </a>
    </div>

    <!-- Filter Bar -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="list.php" class="row g-3">
                <div class="col-md-5">
                    <input type="text" name="search" class="form-control" placeholder="Search by device name, brand, or serial no..." value="<?= htmlspecialchars($search_query) ?>">
                </div>
                <div class="col-md-4">
                    <select name="status" class="form-select">
                        <option value="">All Statuses</option>
                        <option value="Active" <?= $status_filter === 'Active' ? 'selected' : '' ?>>Active</option>
                        <option value="In Maintenance" <?= $status_filter === 'In Maintenance' ? 'selected' : '' ?>>In Maintenance</option>
                        <option value="Decommissioned" <?= $status_filter === 'Decommissioned' ? 'selected' : '' ?>>Decommissioned</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-secondary w-100"><i class="bi bi-funnel"></i> Filter</button>
                    <a href="list.php" class="btn btn-outline-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Device List Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Device Name</th>
                            <th>Brand</th>
                            <th>Serial Number</th>
                            <th>Status</th>
                            <?php if ($is_super_admin): ?>
                                <th>Assigned To</th>
                            <?php endif; ?>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($devices)): ?>
                            <tr>
                                <td colspan="<?= $is_super_admin ? '7' : '6' ?>" class="text-center py-4 text-muted">
                                    No devices found matching your criteria.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($devices as $device): ?>
                                <tr>
                                    <td><?= htmlspecialchars($device['id']) ?></td>
                                    <td class="fw-semibold"><?= htmlspecialchars($device['device_name']) ?></td>
                                    <td><?= htmlspecialchars($device['brand']) ?></td>
                                    <td><code><?= htmlspecialchars($device['serial_number'] ?? 'N/A') ?></code></td>
                                    <td>
                                        <span class="badge bg-<?= $device['status'] === 'Active' ? 'success' : ($device['status'] === 'In Maintenance' ? 'warning' : 'danger') ?>">
                                            <?= htmlspecialchars($device['status']) ?>
                                        </span>
                                    </td>
                                    <?php if ($is_super_admin): ?>
                                        <td><?= htmlspecialchars($device['owner_name'] ?? 'Unassigned') ?></td>
                                    <?php endif; ?>
                                    <td class="text-end">
                                        <a href="edit.php?id=<?= $device['id'] ?>" class="btn btn-sm btn-outline-primary me-1">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                        <a href="delete.php?id=<?= $device['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this device?');">
                                            <i class="bi bi-trash"></i> Delete
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>