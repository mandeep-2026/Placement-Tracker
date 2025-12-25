<?php require_once '../config/db.php'; ?>
<nav class="navbar navbar-expand-lg navbar-light fixed-top">
    <div class="container">
        <a class="navbar-brand fw-bold" href="<?php echo isAdm1n() ? '../adm/dashboard.php' : '../student/dashboard.php'; ?>">
            <i class="fas fa-briefcase text-primary me-2"></i>Placement Tracker
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle fa-2x me-2"></i>
                        <span class="d-none d-md-inline"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                        <span class="badge bg-<?php echo $_SESSION['role'] === 'admin' ? 'danger' : 'primary'; ?> ms-1">
                            <?php echo ucfirst($_SESSION['role']); ?>
                        </span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow-lg">
                        <li>
                            <span class="dropdown-item-text px-3 py-2 border-bottom">
                                <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong><br>
                                <small class="text-muted"><?php echo $_SESSION['role']; ?></small>
                            </span>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <?php if (isAdm1n()): ?>
                        <li><a class="dropdown-item" href="../adm/dashboard.php"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</a></li>
                        <li><a class="dropdown-item" href="../adm/companies.php"><i class="fas fa-building me-2"></i>Companies</a></li>
                        <li><a class="dropdown-item" href="../adm/drives.php"><i class="fas fa-calendar me-2"></i>Drives</a></li>
                        <?php else: ?>
                        <li><a class="dropdown-item" href="../student/dashboard.php"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</a></li>
                        <li><a class="dropdown-item" href="../student/drives.php"><i class="fas fa-calendar-alt me-2"></i>Drives</a></li>
                        <?php endif; ?>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item text-danger" href="<?php echo isAdm1n() ? '../auth/logout.php' : '../student/logout.php'; ?>">
                                <i class="fas fa-sign-out-alt me-2"></i>Logout
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
<div class="mt-5 pt-4"></div>

<style>
.navbar-brand {
    background: linear-gradient(45deg, #667eea, #764ba2);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.dropdown-menu {
    border: none;
    border-radius: 20px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.15);
    backdrop-filter: blur(10px);
}

.dropdown-item {
    padding: 12px 20px;
    transition: all 0.3s ease;
}

.dropdown-item:hover {
    background: linear-gradient(45deg, rgba(102,126,234,0.1), rgba(118,75,162,0.1));
    transform: translateX(5px);
}

.dropdown-item-text {
    font-size: 0.95rem;
}
</style>
