<?php 
require_once '../config/db.php'; 
if (!isLoggedIn() || !isAdm1n()) redirect('../auth/login.php');
$page_title = "Applications";
include '../partials/header.php'; 
include '../partials/navbar.php';

if ($_POST && isset($_POST['update_status'])) {
    $stmt = $pdo->prepare("UPDATE applications SET status = ? WHERE id = ?");
    $stmt->execute([$_POST['status'], $_POST['application_id']]);
    $success = "Status updated successfully!";
}
?>

<div class="container-fluid px-4 py-5">
    <?php if (isset($success)): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>
    
    <div class="row g-4">
        <div class="col-12">
            <div class="card p-5">
                <h2 class="mb-4"><i class="fas fa-file-signature me-2 text-primary"></i>All Applications</h2>
                
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Company</th>
                                <th>Drive</th>
                                <th>Date Applied</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $apps = $pdo->query("
                                SELECT a.*, u.username, c.name, d.title 
                                FROM applications a 
                                JOIN users u ON a.user_id = u.id 
                                JOIN drives d ON a.drive_id = d.id 
                                JOIN companies c ON d.company_id = c.id 
                                ORDER BY a.applied_at DESC
                            ");
                            while ($app = $apps->fetch()): 
                            ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($app['username']); ?></strong></td>
                                <td><?php echo htmlspecialchars($app['name']); ?></td>
                                <td><?php echo htmlspecialchars($app['title']); ?></td>
                                <td><?php echo date('M d, Y', strtotime($app['applied_at'])); ?></td>
                                <td>
                                    <span class="status-badge status-<?php echo $app['status']; ?>">
                                        <?php echo ucfirst($app['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <form method="POST" class="d-inline">
                                        <input type="hidden" name="application_id" value="<?php echo $app['id']; ?>">
                                        <select name="status" class="form-select form-select-sm d-inline w-auto" onchange="this.form.submit()">
                                            <option value="applied" <?php echo $app['status']=='applied'?'selected':'';?>>Applied</option>
                                            <option value="shortlisted" <?php echo $app['status']=='shortlisted'?'selected':'';?>>Shortlisted</option>
                                            <option value="rejected" <?php echo $app['status']=='rejected'?'selected':'';?>>Rejected</option>
                                            <option value="selected" <?php echo $app['status']=='selected'?'selected':'';?>>Selected</option>
                                        </select>
                                        <input type="hidden" name="update_status" value="1">
                                    </form>
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
