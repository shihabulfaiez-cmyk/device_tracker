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

// Fetch all users for Super Admin dropdown selection
$users = [];
if ($is_super_admin) {
    $user_stmt = $pdo->query("SELECT id, name FROM users ORDER BY name ASC");
    $users = $user_stmt->fetchAll(PDO::FETCH_ASSOC);
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $device_name   = trim($_POST['device_name'] ?? '');
    $brand         = trim($_POST['brand'] ?? '');
    $serial_number = trim($_POST['serial_number'] ?? '');
    $status        = trim($_POST['status'] ?? 'Active');
    
    // Determine assigned user_id
    $assigned_user_id = $current_user_id; // Default to current user
    if ($is_super_admin && isset($_POST['user_id']) && !empty($_POST['user_id'])) {
        $assigned_user_id = intval($_POST['user_id']);
    }

    if (empty($device_name) || empty($brand)) {
        $error = "Device name and brand are required fields.";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO devices (device_name, brand, serial_number, status, user_id) VALUES (:device_name, :brand, :serial_number, :status, :user_id)");
            $stmt->execute([
                ':device_name'   => $device_name,
                ':brand'         => $brand,
                ':serial_number' => $serial_number,
                ':status'        => $status,
                ':user_id'       => $assigned_user_id
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
            <h2 class="fw-bold mb-3">Add New Device</h2>
            <p class="text-muted mb-4">Register a new piece of hardware into the system inventory.</p>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger" role="alert"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST" action="create.php">
                <div class="mb-3">
                    <label for="device_name" class="form-label fw-semibold">Device Name</label>
                    <input type="text" class="form-control" id="device_name" name="device_name" required placeholder="e.g. Dell Latitude 5470">
                </div>

                <div class="mb-3">
                    <label for="brand" class="form-label fw-semibold">Brand</label>
                    <input type="text" class="form-control" id="brand" name="brand" required placeholder="e.g. Dell, HP, Apple">
                </div>

                <div class="mb-3">
                    <label for="serial_number" class="form-label fw-semibold">Serial Number</label>
                    <input type="text" class="form-control" id="serial_number" name="serial_number" placeholder="e.g. DL98342XF">
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label fw-semibold">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="Active">Active</option>
                        <option value="In Maintenance">In Maintenance</option>
                        <option value="Decommissioned">Decommissioned</option>
                    </select>
                </div>

                <?php if ($is_super_admin): ?>
                    <div class="mb-4">
                        <label for="user_id" class="form-label fw-semibold">Assign to User</label>
                        <select class="form-select" id="user_id" name="user_id">
                            <?php foreach ($users as $u): ?>
                                <option value="<?= $u['id'] ?>" <?= $u['id'] == $current_user_id ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($u['name']) ?> (ID: <?= $u['id'] ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                <?php endif; ?>

                <div class="d-flex justify-content-between align-items-center">
                    <a href="list.php" class="btn btn-outline-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle me-1"></i> Save Device</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>