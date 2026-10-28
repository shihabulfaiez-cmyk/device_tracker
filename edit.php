<?php
require_once 'db.php';
require_once 'includes/header.php';

// Auth Guard
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$current_user_id = $_SESSION['user_id'];
$is_super_admin  = (isset($_SESSION['role']) && $_SESSION['role'] === 'super_admin');

$device_id = intval($_GET['id'] ?? 0);

if (!$device_id) {
    header("Location: list.php");
    exit();
}

// Fetch device details
$stmt = $pdo->prepare("SELECT * FROM devices WHERE id = :id");
$stmt->execute([':id' => $device_id]);
$device = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$device) {
    header("Location: list.php");
    exit();
}

// Ownership check: Standard users can only edit their own devices
if (!$is_super_admin && $device['user_id'] != $current_user_id) {
    header("Location: list.php?error=unauthorized");
    exit();
}

// Fetch user list for Super Admin owner assignment
$users = [];
if ($is_super_admin) {
    $user_stmt = $pdo->query("SELECT id, name FROM users ORDER BY name ASC");
    $users = $user_stmt->fetchAll(PDO::FETCH_ASSOC);
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $device_name   = trim($_POST['device_name'] ?? '');
    $brand         = trim($_POST['brand'] ?? '');
    $serial_number = trim($_POST['serial_number'] ?? '');
    $status        = trim($_POST['status'] ?? 'Active');
    
    $assigned_user_id = $device['user_id'];
    if ($is_super_admin && isset($_POST['user_id'])) {
        $assigned_user_id = !empty($_POST['user_id']) ? intval($_POST['user_id']) : null;
    }

    if (empty($device_name) || empty($brand)) {
        $error = "Device name and brand are required.";
    } else {
        try {
            $update_sql = "UPDATE devices 
                           SET device_name = :device_name, brand = :brand, serial_number = :serial_number, status = :status, user_id = :user_id 
                           WHERE id = :id";
            $update_stmt = $pdo->prepare($update_sql);
            $update_stmt->execute([
                ':device_name'   => $device_name,
                ':brand'         => $brand,
                ':serial_number' => $serial_number,
                ':status'        => $status,
                ':user_id'       => $assigned_user_id,
                ':id'            => $device_id
            ]);

            header("Location: list.php");
            exit();
        } catch (PDOException $e) {
            $error = "Database Error: " . $e->getMessage();
        }
    }
}
?>

<div class="container py-5" style="max-width: 700px;">
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <h2 class="fw-bold mb-3">Edit Device</h2>
            <p class="text-muted mb-4">Update the operational parameters or owner for this record.</p>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST" action="edit.php?id=<?= $device_id ?>">
                <div class="mb-3">
                    <label for="device_name" class="form-label fw-semibold">Device Name</label>
                    <input type="text" class="form-control" id="device_name" name="device_name" required value="<?= htmlspecialchars($device['device_name']) ?>">
                </div>

                <div class="mb-3">
                    <label for="brand" class="form-label fw-semibold">Brand</label>
                    <input type="text" class="form-control" id="brand" name="brand" required value="<?= htmlspecialchars($device['brand']) ?>">
                </div>

                <div class="mb-3">
                    <label for="serial_number" class="form-label fw-semibold">Serial Number</label>
                    <input type="text" class="form-control" id="serial_number" name="serial_number" value="<?= htmlspecialchars($device['serial_number'] ?? '') ?>">
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label fw-semibold">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="Active" <?= $device['status'] === 'Active' ? 'selected' : '' ?>>Active</option>
                        <option value="In Maintenance" <?= $device['status'] === 'In Maintenance' ? 'selected' : '' ?>>In Maintenance</option>
                        <option value="Decommissioned" <?= $device['status'] === 'Decommissioned' ? 'selected' : '' ?>>Decommissioned</option>
                    </select>
                </div>

                <?php if ($is_super_admin): ?>
                    <div class="mb-4">
                        <label for="user_id" class="form-label fw-semibold">Assign to User</label>
                        <select class="form-select" id="user_id" name="user_id">
                            <option value="">Unassigned</option>
                            <?php foreach ($users as $u): ?>
                                <option value="<?= $u['id'] ?>" <?= $u['id'] == $device['user_id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($u['name']) ?> (ID: <?= $u['id'] ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                <?php endif; ?>

                <div class="d-flex justify-content-between align-items-center">
                    <a href="list.php" class="btn btn-outline-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Update Device</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>