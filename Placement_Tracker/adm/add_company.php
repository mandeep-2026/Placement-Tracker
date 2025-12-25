<?php 
require_once '../config/db.php'; 
if (!isLoggedIn() || !isAdm1n()) redirect('../auth/login.php');
$page_title = "Add Company";
include '../partials/header.php'; 
include '../partials/navbar.php';

$success = $error = '';
$company_id = $_GET['edit'] ?? 0;

// Fetch company for edit
$company = null;
if ($company_id) {
    $stmt = $pdo->prepare("SELECT * FROM companies WHERE id = ?");
    $stmt->execute([$company_id]);
    $company = $stmt->fetch();
}

if ($_POST) {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $eligibility = trim($_POST['eligibility']);
    $package = trim($_POST['package']);
    
    if (empty($name) || empty($description) || empty($eligibility) || empty($package)) {
        $error = "All fields are required!";
    } else {
        if ($company_id) {
            // Update existing company
            $stmt = $pdo->prepare("UPDATE companies SET name=?, description=?, eligibility=?, package=? WHERE id=?");
            $stmt->execute([$name, $description, $eligibility, $package, $company_id]);
            $success = "Company updated successfully!";
        } else {
            // Add new company
            $stmt = $pdo->prepare("INSERT INTO companies (name, description, eligibility, package) VALUES (?, ?, ?, ?)");
            $stmt->execute([$name, $description, $eligibility, $package]);
            $success = "Company added successfully!";
        }
    }
}
?>

<div class="container px-4 py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-8">
            <div class="card p-5 shadow-lg">
                <div class="text-center mb-5">
                    <i class="fas fa-building fa-4x text-success mb-4"></i>
                    <h2 class="mb-2"><?php echo $company_id ? 'Edit Company' : 'Add New Company'; ?></h2>
                    <p class="text-muted lead mb-0">Manage company details for placement drives</p>
                </div>
                
                <?php if ($success): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i><?php echo $success; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <div class="text-center mb-4">
                        <a href="companies.php" class="btn btn-primary">
                            <i class="fas fa-list me-2"></i>View All Companies
                        </a>
                    </div>
                <?php endif; ?>
                
                <?php if ($error): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i><?php echo $error; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <?php if (!$success): ?>
                <form method="POST" id="companyForm">
                    <div class="row g-4">
                        <!-- Company Name -->
                        <div class="col-md-6">
                            <label class="form-label fw-bold">
                                <i class="fas fa-tag me-1 text-primary"></i>Company Name
                            </label>
                            <input type="text" 
                                   name="name" 
                                   class="form-control" 
                                   value="<?php echo htmlspecialchars($company['name'] ?? ''); ?>" 
                                   placeholder="e.g., Google, Microsoft, TCS" 
                                   required>
                            <div class="form-text">Official company name as recognized</div>
                        </div>
                        
                        <!-- Package -->
                        <div class="col-md-6">
                            <label class="form-label fw-bold">
                                <i class="fas fa-rupee-sign me-1 text-success"></i>Package Range
                            </label>
                            <input type="text" 
                                   name="package" 
                                   class="form-control" 
                                   value="<?php echo htmlspecialchars($company['package'] ?? ''); ?>" 
                                   placeholder="e.g., 8-12 LPA, 20+ LPA" 
                                   required>
                            <div class="form-text">CTC range offered to freshers</div>
                        </div>
                        
                        <!-- Description -->
                        <div class="col-12">
                            <label class="form-label fw-bold">
                                <i class="fas fa-info-circle me-1 text-info"></i>Company Description
                            </label>
                            <textarea name="description" 
                                      class="form-control" 
                                      rows="3" 
                                      placeholder="Brief description about company profile, services, etc."
                                      required><?php echo htmlspecialchars($company['description'] ?? ''); ?></textarea>
                            <div class="form-text">Max 2000 characters recommended</div>
                        </div>
                        
                        <!-- Eligibility -->
                        <div class="col-12">
                            <label class="form-label fw-bold">
                                <i class="fas fa-graduation-cap me-1 text-warning"></i>Eligibility Criteria
                            </label>
                            <textarea name="eligibility" 
                                      class="form-control" 
                                      rows="3" 
                                      placeholder="e.g., 7+ CGPA, 2025 Batch, No active backlogs, CSE/IT/ECE"
                                      required><?php echo htmlspecialchars($company['eligibility'] ?? ''); ?></textarea>
                            <div class="form-text">Academic & branch eligibility requirements</div>
                        </div>
                    </div>
                    
                    <div class="d-flex gap-3 justify-content-center mt-5">
                        <button type="submit" class="btn btn-success btn-lg px-5 pulse-glow">
                            <i class="fas fa-<?php echo $company_id ? 'edit' : 'plus'; ?> me-2"></i>
                            <?php echo $company_id ? 'Update Company' : 'Add Company'; ?>
                        </button>
                        <a href="companies.php" class="btn btn-outline-secondary btn-lg px-5">
                            <i class="fas fa-arrow-left me-2"></i>Cancel
                        </a>
                    </div>
                    
                    <?php if ($company_id): ?>
                    <div class="text-center mt-4 p-4 bg-light rounded-3">
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            Editing: <strong><?php echo htmlspecialchars($company['name']); ?></strong>
                        </small>
                    </div>
                    <?php endif; ?>
                </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form validation
    const form = document.getElementById('companyForm');
    form.addEventListener('submit', function(e) {
        const name = this.querySelector('input[name="name"]').value.trim();
        const package = this.querySelector('input[name="package"]').value.trim();
        
        if (name.length < 2) {
            e.preventDefault();
            alert('Company name must be at least 2 characters!');
            return false;
        }
        
        if (!package.match(/LPA|CTC|lpa|ctc/i)) {
            if (!confirm('Package field should contain "LPA" or "CTC". Continue anyway?')) {
                e.preventDefault();
                return false;
            }
        }
    });
    
    // Auto-resize textareas
    document.querySelectorAll('textarea').forEach(textarea => {
        textarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = this.scrollHeight + 'px';
        });
        // Trigger initial resize
        this.dispatchEvent(new Event('input'));
    });
    
    // Character counter
    const description = document.querySelector('textarea[name="description"]');
    const eligibility = document.querySelector('textarea[name="eligibility"]');
    
    description.addEventListener('input', updateCounter);
    eligibility.addEventListener('input', updateCounter);
    
    function updateCounter(e) {
        const counter = e.target.parentElement.querySelector('.char-counter');
        if (!counter) {
            const newCounter = document.createElement('div');
            newCounter.className = 'char-counter text-muted small mt-1';
            newCounter.style.fontSize = '0.8rem';
            e.target.parentElement.appendChild(newCounter);
        }
        e.target.parentElement.querySelector('.char-counter').textContent = 
            `${e.target.value.length}/200 characters`;
    }
});
</script>

<?php include '../partials/footer.php'; ?>
