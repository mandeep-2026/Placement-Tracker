<?php 
require_once '../config/db.php'; 
if (!isLoggedIn() || !isAdm1n()) redirect('../auth/login.php');
$page_title = "Admin Dashboard";
include '../partials/header.php'; 
include '../partials/navbar.php'; 
?>

<style>
/* SCROLL FIXED - NO INFINITE SCROLL */
html, body { 
    height: 100%; 
    overflow-x: hidden; 
}
body { 
    min-height: 100vh; 
    padding-top: 100px; 
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

/* Container */
.container-fluid { 
    padding: 2rem 1rem; 
    max-width: 1400px; 
    margin: 0 auto; 
}

/* Stats Cards - FIXED */
.stats-card {
    background: linear-gradient(135deg, rgba(255,255,255,0.95), rgba(255,255,255,0.8));
    backdrop-filter: blur(10px);
    border-radius: 20px;
    padding: 2rem;
    text-align: center;
    transition: all 0.3s ease;
    border: 1px solid rgba(255,255,255,0.2);
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    height: 100%;
}
.stats-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.15);
}
.stats-card i { opacity: 0.9; }
.stats-card h3 { font-size: 2.5rem; font-weight: 700; margin: 0.5rem 0; }

/* Status Badges - ENHANCED */
.status-badge {
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.status-open { background: linear-gradient(45deg, #d4edda, #c3e6cb); color: #155724; }
.status-closed { background: linear-gradient(45deg, #f8d7da, #f5c6cb); color: #721c24; }
.status-completed { background: linear-gradient(45deg, #d1ecf1, #bee5eb); color: #0c5460; }
.status-selected { background: linear-gradient(45deg, #d4edda, #c3e6cb); color: #155724; }
.status-applied { background: linear-gradient(45deg, #fff3cd, #ffeaa7); color: #856404; }

/* Quick Actions - FIXED HEIGHT */
.card { 
    border: none; 
    border-radius: 20px; 
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    overflow: hidden;
}
.action-btn {
    border-radius: 15px !important;
    border: 2px solid;
    font-weight: 600;
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 120px;
    text-decoration: none;
}
.action-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.2) !important;
    color: inherit !important;
}

/* Tables - ENHANCED */
.table { 
    margin: 0; 
    font-size: 0.95rem; 
}
.table th { 
    border: none; 
    font-weight: 600; 
    color: #495057; 
    padding: 1.5rem 1rem; 
}
.table td { 
    padding: 1.25rem 1rem; 
    vertical-align: middle; 
    border-color: rgba(0,0,0,0.05);
}
.card-header {
    border: none;
    font-weight: 600;
    padding: 1.5rem;
}

/* Chart Container */
.chart-container {
    height: 250px;
    position: relative;
}
</style>

<div class="container-fluid px-4 py-5">
    <!-- Admin Stats -->
    <div class="row g-4 mb-5">
        <?php
        // Safe queries with error handling
        $total_students = $pdo->query("SELECT COUNT(*) FROM users WHERE role='student'")->fetchColumn() ?: 0;
        $total_companies = $pdo->query("SELECT COUNT(*) FROM companies")->fetchColumn() ?: 0;
        $open_drives = $pdo->query("SELECT COUNT(*) FROM drives WHERE status='open'")->fetchColumn() ?: 0;
        $total_selections = $pdo->query("SELECT COUNT(*) FROM applications WHERE status='selected'")->fetchColumn() ?: 0;
        $total_applications = $pdo->query("SELECT COUNT(*) FROM applications")->fetchColumn() ?: 0;
        $success_rate = $total_applications ? round(($total_selections/$total_applications)*100,1) : 0;
        ?>
        
        <div class="col-xl-2 col-lg-3 col-md-6">
    <a href="students.php" style="text-decoration:none; color:inherit;">
        <div class="stats-card">
            <i class="fas fa-users fa-3x text-primary mb-3"></i>
            <h3 class="text-primary"><?php echo $total_students; ?></h3>
            <p class="mb-0 text-muted fw-semibold">Total Students</p>
        </div>
    </a>
</div>

        <div class="col-xl-2 col-lg-3 col-md-6">
            <div class="stats-card">
                <i class="fas fa-building fa-3x text-success mb-3"></i>
                <h3 class="text-success"><?php echo $total_companies; ?></h3>
                <p class="mb-0 text-muted fw-semibold">Companies</p>
            </div>
        </div>
        <div class="col-xl-2 col-lg-3 col-md-6">
            <div class="stats-card">
                <i class="fas fa-calendar fa-3x text-warning mb-3"></i>
                <h3 class="text-warning"><?php echo $open_drives; ?></h3>
                <p class="mb-0 text-muted fw-semibold">Open Drives</p>
            </div>
        </div>
        <div class="col-xl-2 col-lg-3 col-md-6">
            <div class="stats-card">
                <i class="fas fa-file-alt fa-3x text-info mb-3"></i>
                <h3 class="text-info"><?php echo $total_applications; ?></h3>
                <p class="mb-0 text-muted fw-semibold">Applications</p>
            </div>
        </div>
        <div class="col-xl-2 col-lg-3 col-md-6">
            <div class="stats-card">
                <i class="fas fa-trophy fa-3x text-danger mb-3"></i>
                <h3 class="text-danger"><?php echo $total_selections; ?></h3>
                <p class="mb-0 text-muted fw-semibold">Selections</p>
            </div>
        </div>
        <div class="col-xl-2 col-lg-3 col-md-6">
            <div class="stats-card">
                <i class="fas fa-chart-line fa-3x text-secondary mb-3"></i>
                <h3 class="text-secondary"><?php echo $success_rate; ?>%</h3>
                <p class="mb-0 text-muted fw-semibold">Success Rate</p>
            </div>
        </div>
    </div>

    <!-- Quick Actions + Chart -->
    <div class="row g-4 mb-5">
        <div class="col-lg-8">
            <div class="card p-4 h-100">
                <h3 class="mb-4"><i class="fas fa-cogs me-2 text-primary"></i>Quick Actions</h3>
                <div class="row g-3">
                    <div class="col-md-3 col-sm-6">
                        <a href="companies.php" class="btn btn-outline-primary action-btn w-100">
                            <i class="fas fa-building fa-2x mb-2"></i>
                            <span>Companies</span>
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <a href="drives.php" class="btn btn-outline-success action-btn w-100">
                            <i class="fas fa-calendar-plus fa-2x mb-2"></i>
                            <span>Add Drive</span>
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <a href="applications.php" class="btn btn-outline-warning action-btn w-100">
                            <i class="fas fa-list fa-2x mb-2"></i>
                            <span>Applications</span>
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <a href="add_company.php" class="btn btn-outline-info action-btn w-100">
                            <i class="fas fa-plus fa-2x mb-2"></i>
                            <span>New Company</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card p-4 h-100">
                <h5 class="mb-3"><i class="fas fa-chart-pie me-2 text-primary"></i>Status Overview</h5>
                <div class="chart-container">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-calendar me-2"></i>Recent Drives</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr><th>Company</th><th>Date</th><th>Status</th><th>Apps</th></tr>
                            </thead>
                            <tbody>
                                <?php
                                $recentDrives = $pdo->query("
                                    SELECT d.*, c.name, 
                                           COALESCE(COUNT(a.id), 0) as app_count 
                                    FROM drives d 
                                    LEFT JOIN companies c ON d.company_id = c.id 
                                    LEFT JOIN applications a ON d.id = a.drive_id 
                                    GROUP BY d.id 
                                    ORDER BY d.created_at DESC 
                                    LIMIT 5
                                ");
                                if ($recentDrives->rowCount() == 0): ?>
                                    <tr><td colspan="4" class="text-center text-muted py-4">
                                        No drives yet. <a href="drives.php" class="text-primary">Add first drive</a>
                                    </td></tr>
                                <?php else: 
                                    while ($drive = $recentDrives->fetch()): ?>
                                    <tr>
                                        <td><strong><?php echo htmlspecialchars($drive['name'] ?? $drive['c.name'] ?? 'N/A'); ?></strong></td>
                                        <td><?php echo $drive['date'] ? date('M d', strtotime($drive['date'])) : 'N/A'; ?></td>
                                        <td><span class="status-badge status-<?php echo $drive['status'] ?? 'open'; ?>">
                                            <?php echo ucfirst($drive['status'] ?? 'Open'); ?>
                                        </span></td>
                                        <td><span class="badge bg-primary"><?php echo $drive['app_count']; ?></span></td>
                                    </tr>
                                <?php endwhile; endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
       <div class="col-lg-6">
    <div class="card h-100">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0"><i class="fas fa-file me-2"></i>Recent Applications</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Student Name</th>
                            <th>Student Email</th>
                            <th>Company</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $recentApps = $pdo->query("
                            SELECT a.status, u.username, u.email, c.name AS company
                            FROM applications a 
                            LEFT JOIN users u ON a.user_id = u.id 
                            LEFT JOIN drives d ON a.drive_id = d.id
                            LEFT JOIN companies c ON d.company_id = c.id 
                            ORDER BY a.applied_at DESC 
                            LIMIT 5
                        ");

                        if ($recentApps->rowCount() == 0): ?>
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">No applications yet</td>
                            </tr>

                        <?php else: 
                            while ($app = $recentApps->fetch()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($app['username'] ?? 'Unknown'); ?></td>
                                <td><?php echo htmlspecialchars($app['email'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($app['company'] ?? 'N/A'); ?></td>
                                <td>
                                    <span class="status-badge status-<?php echo $app['status']; ?>">
                                        <?php echo ucfirst($app['status']); ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endwhile; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<!-- Charts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('statusChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Students', 'Companies', 'Open Drives', 'Applications'],
                datasets: [{
                    data: [<?php echo $total_students; ?>, <?php echo $total_companies; ?>, <?php echo $open_drives; ?>, <?php echo $total_applications; ?>],
                    backgroundColor: ['#3498db', '#2ecc71', '#f39c12', '#e74c3c'],
                    borderWidth: 0,
                    hoverOffset: 10
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { 
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true
                        }
                    }
                }
            }
        });
    }
});
</script>

<?php include '../partials/footer.php'; ?>
