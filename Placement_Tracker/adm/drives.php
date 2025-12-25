<?php 
require_once '../config/db.php'; 
if (!isLoggedIn() || !isAdm1n()) redirect('../auth/login.php');
$page_title = "Manage Drives";
include '../partials/header.php'; 
include '../partials/navbar.php';

if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM drives WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
}

if (isset($_GET['status'])) {
    $stmt = $pdo->prepare("UPDATE drives SET status = ? WHERE id = ?");
    $stmt->execute([$_GET['status'], $_GET['drive']]);
}
?>

<div class="container-fluid px-4 py-5">
    <div class="row g-4 mb-5">
        <div class="col-lg-12">
            <div class="card p-5">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><i class="fas fa-calendar-alt me-2 text-primary"></i>Placement Drives</h2>
                    <a href="add_drives.php" class="btn btn-success btn-lg pulse-glow">
                        <i class="fas fa-plus me-2"></i>Add Drive
                    </a>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Company</th>
                                <th>Title</th>
                                <th>Date</th>
                                <th>Venue</th>
                                <th>Status</th>
                                <th>Applications</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $drives = $pdo->query("
                                SELECT d.*, c.name, COUNT(a.id) as app_count 
                                FROM drives d 
                                JOIN companies c ON d.company_id = c.id 
                                LEFT JOIN applications a ON d.id = a.drive_id 
                                GROUP BY d.id 
                                ORDER BY d.date ASC
                            ");
                            while ($drive = $drives->fetch()): 
                            ?>
                            <tr>
                                <td>
                                    <div class="fw-bold"><?php echo htmlspecialchars($drive['name']); ?></div>
                                </td>
                                <td><?php echo htmlspecialchars($drive['title']); ?></td>
                                <td>
                                    <span class="badge bg-<?php echo $drive['date'] < date('Y-m-d') ? 'danger' : 'success'; ?>">
                                        <?php echo date('M d, Y', strtotime($drive['date'])); ?>
                                    </span>
                                </td>
                                <td><?php echo htmlspecialchars($drive['venue']); ?></td>
                                <td>
                                    <span class="status-badge status-<?php echo $drive['status']; ?>">
                                        <?php echo ucfirst($drive['status']); ?>
                                    </span>
                                </td>
                                <td><span class="badge bg-primary"><?php echo $drive['app_count']; ?></span></td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="?status=open&drive=<?php echo $drive['id']; ?>" class="btn btn-outline-success" title="Open">
                                            <i class="fas fa-play"></i>
                                        </a>
                                        <a href="?status=closed&drive=<?php echo $drive['id']; ?>" class="btn btn-outline-warning" title="Close">
                                            <i class="fas fa-pause"></i>
                                        </a>
                                        <a href="?delete=<?php echo $drive['id']; ?>" class="btn btn-outline-danger" onclick="return confirm('Delete?')" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../partials/footer.php'; ?>
