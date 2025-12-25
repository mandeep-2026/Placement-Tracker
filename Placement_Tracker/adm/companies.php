<?php 
require_once '../config/db.php'; 
if (!isLoggedIn() || !isAdm1n()) redirect('../auth/login.php');
$page_title = "Manage Companies";
include '../partials/header.php'; 
include '../partials/navbar.php';

if ($_POST && isset($_POST['add_company'])) {
    $stmt = $pdo->prepare("INSERT INTO companies (name, description, eligibility, package) VALUES (?, ?, ?, ?)");
    $stmt->execute([$_POST['name'], $_POST['description'], $_POST['eligibility'], $_POST['package']]);
    $success = "Company added successfully!";
}

if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM companies WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    $success = "Company deleted successfully!";
}
?>

<div class="container-fluid px-4 py-5">
    <!-- Add Company Form -->
    <div class="row g-4 mb-5">
        <div class="col-lg-4">
            <div class="card p-5 h-100">
                <h3 class="mb-4"><i class="fas fa-plus-circle me-2 text-success"></i>Add Company</h3>
                <?php if (isset($success)): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Company Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="2" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Eligibility</label>
                        <input type="text" name="eligibility" class="form-control" placeholder="e.g., 7+ CGPA, 2025 Batch" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Package</label>
                        <input type="text" name="package" class="form-control" placeholder="e.g., 8-12 LPA" required>
                    </div>
                    <button type="submit" name="add_company" class="btn btn-success w-100 btn-lg pulse-glow">
                        <i class="fas fa-save me-2"></i>Add Company
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Companies List -->
        <div class="col-lg-8">
            <div class="card p-5 h-100">
                <h3 class="mb-4"><i class="fas fa-building me-2 text-primary"></i>Companies (<?php echo $pdo->query("SELECT COUNT(*) FROM companies")->fetchColumn(); ?>)</h3>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Logo</th>
                                <th>Company</th>
                                <th>Description</th>
                                <th>Eligibility</th>
                                <th>Package</th>
                                <th>Drives</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $companies = $pdo->query("
                                SELECT c.*, COUNT(d.id) as drive_count 
                                FROM companies c 
                                LEFT JOIN drives d ON c.id = d.company_id 
                                GROUP BY c.id 
                                ORDER BY c.created_at DESC
                            ");
                            while ($company = $companies->fetch()): 
                            ?>
                            <tr>
                                <td>
                                    <div class="avatar bg-gradient-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 45px; height: 45px; font-size: 1.1rem;">
                                        <?php echo strtoupper(substr($company['name'], 0, 1)); ?>
                                    </div>
                                </td>
                                <td><strong><?php echo htmlspecialchars($company['name']); ?></strong></td>
                                <td><?php echo htmlspecialchars(substr($company['description'], 0, 40)); ?>...</td>
                                <td><small class="text-muted"><?php echo htmlspecialchars($company['eligibility']); ?></small></td>
                                <td><span class="badge bg-success fs-6"><?php echo $company['package']; ?></span></td>
                                <td><span class="badge bg-info"><?php echo $company['drive_count']; ?></span></td>
                                <td>
                                    <a href="../admin/add_drive.php?company=<?php echo $company['id']; ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-calendar-plus"></i>
                                    </a>
                                    <a href="?delete=<?php echo $company['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this company?')">
                                        <i class="fas fa-trash"></i>
                                    </a>
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
