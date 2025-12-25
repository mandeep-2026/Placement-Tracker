<?php 
require_once '../config/db.php'; 
if (!isLoggedIn()) redirect('../auth/login.php');
$page_title = "Student Dashboard";
include '../partials/header.php'; 
include '../partials/navbar.php';
?>

<div class="container-fluid px-4 py-5">
    <!-- Stats Cards Row -->
    <div class="row g-4 mb-5">
        <?php
        $stats = $pdo->prepare("
            SELECT 
                (SELECT COUNT(*) FROM drives WHERE status='open') as open_drives,
                (SELECT COUNT(*) FROM applications WHERE user_id=? AND status='applied') as applied,
                (SELECT COUNT(*) FROM applications WHERE user_id=? AND status='shortlisted') as shortlisted,
                (SELECT COUNT(*) FROM applications WHERE user_id=? AND status='selected') as selected
        ");
        $stats->execute([$_SESSION['user_id'], $_SESSION['user_id'], $_SESSION['user_id']]);
        $userStats = $stats->fetch();
        ?>
        
        <div class="col-lg-3 col-md-6">
            <div class="stats-card h-100 pulse-glow">
                <i class="fas fa-calendar-check fa-3x text-primary mb-3"></i>
                <h3 class="text-primary"><?php echo $userStats['open_drives']; ?></h3>
                <p class="mb-0 text-muted">Open Drives</p>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="stats-card h-100">
                <i class="fas fa-file-alt fa-3x text-warning mb-3"></i>
                <h3 class="text-warning"><?php echo $userStats['applied']; ?></h3>
                <p class="mb-0 text-muted">Applied</p>
            </div>
        </div>
       
       
    </div>

    <!-- Quick Actions -->
    <div class="row g-4 mb-5">
        <div class="col-lg-8">
            <div class="card p-5">
                <h2 class="mb-4"><i class="fas fa-bolt me-2"></i>Quick Actions</h2>
                <div class="row g-3">
                    <div class="col-md-4">
                        <a href="drives.php" class="btn btn-primary w-100 h-100 p-4">
                            <i class="fas fa-search fa-2x mb-2 d-block"></i>
                            <span class="fs-5 d-block">View Drives</span>
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="applications.php" class="btn btn-success w-100 h-100 p-4">
                            <i class="fas fa-list fa-2x mb-2 d-block"></i>
                            <span class="fs-5 d-block">My Applications</span>
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="profile.php" class="btn btn-info w-100 h-100 p-4">
                            <i class="fas fa-user-edit fa-2x mb-2 d-block"></i>
                            <span class="fs-5 d-block">Profile</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card p-4 h-100">
                <h3 class="mb-4">📊 Success Rate</h3>
                <div class="mb-4">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Selection Rate</span>
                        <span><?php echo $userStats['applied'] ? round(($userStats['selected']/$userStats['applied'])*100, 1) : 0; ?>%</span>
                    </div>
                    <div class="progress" style="height: 20px;">
                        <div class="progress-bar bg-success" style="width: <?php echo $userStats['applied'] ? round(($userStats['selected']/$userStats['applied'])*100, 1) : 0; ?>%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Applications -->
    <div class="card">
        <div class="card-body p-0">
            <div class="p-5 border-bottom">
                <h3><i class="fas fa-clock me-2"></i>Recent Applications</h3>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Company</th>
                            <th>Drive</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $apps = $pdo->prepare("
                            SELECT a.*, d.title, c.name 
                            FROM applications a 
                            JOIN drives d ON a.drive_id = d.id 
                            JOIN companies c ON d.company_id = c.id 
                            WHERE a.user_id = ? 
                            ORDER BY a.applied_at DESC 
                            LIMIT 5
                        ");
                        $apps->execute([$_SESSION['user_id']]);
                        while ($app = $apps->fetch()): 
                        ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($app['name']); ?></strong></td>
                            <td><?php echo htmlspecialchars($app['title']); ?></td>
                            <td><span class="status-badge status-<?php echo $app['status']; ?>"><?php echo ucfirst($app['status']); ?></span></td>
                            <td><?php echo date('M d', strtotime($app['applied_at'])); ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include '../partials/footer.php'; ?>
