<?php 
require_once '../config/db.php'; 
if (!isLoggedIn()) redirect('../auth/login.php');
$page_title = "My Applications";
include '../partials/header.php'; 
include '../partials/navbar.php';

// Handle status update
if ($_POST && isset($_POST['update_status'])) {
    $stmt = $pdo->prepare("UPDATE applications SET status = ? WHERE id = ? AND user_id = ?");
    $stmt->execute([$_POST['status'], $_POST['application_id'], $_SESSION['user_id']]);
}
?>

<div class="container-fluid px-4 py-5">
    <div class="row g-4">
        <!-- Applications Timeline -->
        <div class="col-lg-8">
            <div class="card p-5">
                <h2 class="mb-4"><i class="fas fa-list me-2 text-primary"></i>My Applications</h2>
                
                <div class="applications-timeline">
                    <?php
                    $apps = $pdo->prepare("
                        SELECT a.*, d.title, d.date, c.name, c.package 
                        FROM applications a 
                        JOIN drives d ON a.drive_id = d.id 
                        JOIN companies c ON d.company_id = c.id 
                        WHERE a.user_id = ? 
                        ORDER BY a.applied_at DESC
                    ");
                    $apps->execute([$_SESSION['user_id']]);
                    while ($app = $apps->fetch()): 
                    ?>
                    <div class="timeline-item <?php echo $app['status']; ?>">
                        <div class="timeline-dot status-<?php echo $app['status']; ?>"></div>
                        <div class="timeline-content">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <h5><?php echo htmlspecialchars($app['name']); ?></h5>
                                    <span class="badge bg-primary"><?php echo htmlspecialchars($app['title']); ?></span>
                                </div>
                                <span class="status-badge status-<?php echo $app['status']; ?>">
                                    <?php echo ucfirst($app['status']); ?>
                                </span>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <small><i class="fas fa-calendar me-1"></i><?php echo date('M d, Y', strtotime($app['date'])); ?></small>
                                </div>
                                <div class="col-md-6">
                                    <small><i class="fas fa-tag me-1"></i><?php echo htmlspecialchars($app['package']); ?></small>
                                </div>
                            </div>
                            <?php if ($app['status'] === 'applied'): ?>
                            
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="col-lg-4">
            <div class="row g-4 h-100">
                <?php
                $stats = $pdo->prepare("
                    SELECT 
                        COUNT(CASE WHEN status='applied' THEN 1 END) as applied,
                        COUNT(CASE WHEN status='shortlisted' THEN 1 END) as shortlisted,
                        COUNT(CASE WHEN status='selected' THEN 1 END) as selected,
                        COUNT(CASE WHEN status='rejected' THEN 1 END) as rejected
                    FROM applications WHERE user_id = ?
                ");
                $stats->execute([$_SESSION['user_id']]);
                $appStats = $stats->fetch();
                $total = $appStats['applied'] + $appStats['shortlisted'] + $appStats['selected'] + $appStats['rejected'];
                ?>
                
                <div class="col-12">
                    <div class="stats-card h-100 p-4">
                        <h4>📊 Application Stats</h4>
                        <div class="mt-4">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Success Rate</span>
                                <span><?php echo $total ? round(($appStats['selected']/$total)*100, 1) : 0; ?>%</span>
                            </div>
                            <div class="progress" style="height: 12px;">
                                <div class="progress-bar bg-success" style="width: <?php echo $total ? round(($appStats['selected']/$total)*100, 1) : 0; ?>%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.applications-timeline {
    position: relative;
    padding-left: 30px;
}
.timeline-item {
    position: relative;
    margin-bottom: 30px;
}
.timeline-dot {
    position: absolute;
    left: -45px;
    top: 5px;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    border: 4px solid #f8f9fa;
}
.timeline-dot.status-applied { background: #f39c12; }
.timeline-dot.status-shortlisted { background: #27ae60; }
.timeline-dot.status-selected { background: #3498db; box-shadow: 0 0 20px rgba(52, 152, 219, 0.5); }
.timeline-dot.status-rejected { background: #e74c3c; }
.timeline-content {
    background: rgba(255,255,255,0.8);
    padding: 20px;
    border-radius: 15px;
    border-left: 4px solid;
    position: relative;
}
.timeline-content::before {
    content: '';
    position: absolute;
    left: -24px;
    top: 20px;
    width: 20px;
    height: 2px;
    background: #dee2e6;
}
</style>

<script>
function updateStatus(appId, status) {
    if (confirm('Update status to ' + status + '?')) {
        fetch('', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: `update_status=1&application_id=${appId}&status=${status}`
        }).then(() => location.reload());
    }
}
</script>

<?php include '../partials/footer.php'; ?>
