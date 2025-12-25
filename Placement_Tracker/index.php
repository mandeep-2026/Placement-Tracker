<?php require_once 'config/db.php'; ?>
<?php include 'partials/header.php'; ?>

<div class="container d-flex justify-content-center align-items-center min-vh-100 px-4">
    <div class="row g-4 w-100">
        <?php if (isLoggedIn()): ?>
            <div class="col-lg-6">
                <div class="card p-5 pulse-glow">
                    <div class="text-center mb-5">
                        <div class="mb-4">
                            <i class="fas fa-user-graduate fa-4x text-primary mb-3"></i>
                        </div>
                        <h1 class="display-5 fw-bold mb-3">Welcome Back!</h1>
                        <h3 class="text-primary"><?php echo htmlspecialchars(string: $_SESSION['username']); ?></h3>
                    </div>
                    <div class="d-grid gap-3">
                        <?php if (isAdm1n()): ?>
                            <a href="adm/dashboard.php" class="btn btn-primary btn-lg">
                                <i class="fas fa-tachometer-alt me-2"></i>Admin Dashboard
                            </a>
                        <?php else: ?>
                            <a href="student/dashboard.php" class="btn btn-primary btn-lg">
                                <i class="fas fa-chart-line me-2"></i>Student Dashboard
                            </a>
                        <?php endif; ?>
                        <a href="auth/logout.php" class="btn btn-outline-secondary btn-lg">
                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                        </a>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="col-lg-5">
                <div class="card p-5">
                    <div class="text-center mb-5">
                        <h1 class="display-4 fw-bold mb-3">🚀 Placement Tracker</h1>
                        <p class="lead text-muted mb-0">Track your dream job journey with style</p>
                    </div>
                    <div class="d-grid gap-3">
                        <a href="auth/login.php" class="btn btn-primary btn-lg">
                            <i class="fas fa-sign-in-alt me-2"></i>Login
                        </a>
                        <a href="auth/register.php" class="btn btn-outline-primary btn-lg">
                            <i class="fas fa-user-plus me-2"></i>Join Now
                        </a>
                    </div>
                   
                </div>
            </div>
            <div class="col-lg-7 d-none d-lg-block">
                <div class="card p-5 h-100">
                    <div class="row text-center">
                        
                        <div class="col-md-4">
                            <i class="fas fa-users fa-3x text-success mb-3"></i>
                            <h4>Students</h4>
                            <div class="h4 text-success">1500+</div>
                        </div>
                        <div class="col-md-4">
                            <i class="fas fa-trophy fa-3x text-warning mb-3"></i>
                            <h4>Selections</h4>
                            <div class="h4 text-warning">89%</div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'partials/footer.php'; ?>
