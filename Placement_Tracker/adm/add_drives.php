<?php 
require_once '../config/db.php'; 
if (!isLoggedIn() || !isAdm1n()) redirect('../auth/login.php');
$page_title = "Add Drive";
include '../partials/header.php'; 
include '../partials/navbar.php';

$company_id = $_GET['company'] ?? 0;
$company = $pdo->prepare("SELECT name FROM companies WHERE id = ?");
$company->execute([$company_id]);
$company = $company->fetch();

if ($_POST) {
    $stmt = $pdo->prepare("INSERT INTO drives (company_id, title, date, venue, status) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$_POST['company_id'], $_POST['title'], $_POST['date'], $_POST['venue'], $_POST['status']]);
    $success = "Drive added successfully!";
}
?>

<div class="container px-4 py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card p-5 shadow-lg">
                <div class="text-center mb-5">
                    <i class="fas fa-calendar-plus fa-4x text-success mb-4"></i>
                    <h2 class="mb-3">Add New Drive</h2>
                    <?php if (isset($success)): ?>
                        <div class="alert alert-success"><?php echo $success; ?></div>
                        <a href="drives.php" class="btn btn-primary mt-3">View All Drives</a>
                    <?php endif; ?>
                </div>
                
                <?php if (!isset($success)): ?>
                <form method="POST">
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold">Company</label>
                            <select name="company_id" class="form-select" required>
                                <option value="">Select Company</option>
                                <?php
                                $companies = $pdo->query("SELECT * FROM companies ORDER BY name");
                                while ($c = $companies->fetch()): 
                                ?>
                                <option value="<?php echo $c['id']; ?>" <?php echo $company_id == $c['id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($c['name']); ?>
                                </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold">Status</label>
                            <select name="status" class="form-select">
                                <option value="open">Open</option>
                                <option value="closed">Closed</option>
                                <option value="completed">Completed</option>
                            </select>
                        </div>
                        <div class="col-12 mb-4">
                            <label class="form-label fw-bold">Drive Title</label>
                            <input type="text" name="title" class="form-control" placeholder="e.g., Software Developer Drive" required>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold">Date</label>
                            <input type="date" name="date" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold">Venue</label>
                            <input type="text" name="venue" class="form-control" placeholder="e.g., Main Auditorium" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success w-100 btn-lg pulse-glow">
                        <i class="fas fa-calendar-plus me-2"></i>Create Drive
                    </button>
                </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include '../partials/footer.php'; ?>
