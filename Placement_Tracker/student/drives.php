<?php 
require_once '../config/db.php'; 
if (!isLoggedIn()) redirect('../auth/login.php');
$page_title = "Placement Drives";
include '../partials/header.php'; 
include '../partials/navbar.php';
?>

<div class="container-fluid px-4 py-5">
    <div class="row mb-5">
        <div class="col-12">
            <div class="card p-5">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><i class="fas fa-calendar-alt me-2 text-primary"></i>Placement Drives</h2>
                    <div class="btn-group" role="group">
                        <button class="btn btn-outline-primary active" data-filter="all">All</button>
                        <button class="btn btn-outline-primary" data-filter="open">Open</button>
                        <button class="btn btn-outline-primary" data-filter="upcoming">Upcoming</button>
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-hover mb-0 drives-table">
                        <thead>
                            <tr>
                                <th>Company</th>
                                <th>Drive Title</th>
                                <th>Date</th>
                                <th>Venue</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $drives = $pdo->query("
                                SELECT d.*, c.name, c.description, c.eligibility, c.package,
                                       (SELECT COUNT(*) FROM applications a WHERE a.drive_id = d.id AND a.user_id = {$_SESSION['user_id']}) as applied
                                FROM drives d 
                                JOIN companies c ON d.company_id = c.id 
                                ORDER BY d.date ASC
                            ");
                            while ($drive = $drives->fetch()): 
                                $isApplied = $drive['applied'] > 0;
                                $isOpen = $drive['status'] === 'open';
                            ?>
                            <tr class="drive-row" data-status="<?php echo $drive['status']; ?>">
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar bg-primary text-white rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                            <?php echo strtoupper(substr($drive['name'], 0, 1)); ?>
                                        </div>
                                        <div>
                                            <strong><?php echo htmlspecialchars($drive['name']); ?></strong>
                                            <br><small class="text-muted"><?php echo htmlspecialchars(substr($drive['description'], 0, 50)); ?>...</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <strong><?php echo htmlspecialchars($drive['title']); ?></strong>
                                    <br><small class="text-muted"><?php echo htmlspecialchars($drive['eligibility']); ?></small>
                                </td>
                                <td>
                                    <span class="badge bg-<?php echo date('Y-m-d', strtotime($drive['date'])) < date('Y-m-d') ? 'danger' : 'success'; ?>">
                                        <?php echo date('M d, Y', strtotime($drive['date'])); ?>
                                    </span>
                                </td>
                                <td><?php echo htmlspecialchars($drive['venue']); ?></td>
                                <td><span class="status-badge status-<?php echo $drive['status']; ?>"><?php echo ucfirst($drive['status']); ?></span></td>
                                <td>
                                    <?php if ($isOpen && !$isApplied): ?>
                                        <button class="btn btn-primary btn-sm apply-btn pulse-glow" data-drive-id="<?php echo $drive['id']; ?>">
                                            <i class="fas fa-paper-plane me-1"></i>Apply
                                        </button>
                                    <?php elseif ($isApplied): ?>
                                        <span class="badge bg-success"><i class="fas fa-check me-1"></i>Applied</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Closed</span>
                                    <?php endif; ?>

                                    <!-- View company details button -->
                                    <button class="btn btn-info btn-sm view-details ms-1"
                                        data-name="<?php echo htmlspecialchars($drive['name']); ?>"
                                        data-description="<?php echo htmlspecialchars($drive['description']); ?>"
                                        data-eligibility="<?php echo htmlspecialchars($drive['eligibility']); ?>"
                                        data-package="<?php echo htmlspecialchars($drive['package']); ?>"
                                        data-date="<?php echo date('M d, Y', strtotime($drive['date'])); ?>"
                                        data-venue="<?php echo htmlspecialchars($drive['venue']); ?>">
                                        <i class="fas fa-eye"></i>
                                    </button>
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

<!-- Company Details Modal -->
<div class="modal fade" id="detailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h4 class="modal-title company-title"></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <p class="company-description text-muted"></p>
                
                <hr>
                <p><strong>Eligibility:</strong> <span class="company-eligibility"></span></p>
                <p><strong>Package:</strong> <span class="company-package text-success"></span></p>
                <p><strong>Date:</strong> <span class="company-date"></span></p>
                <p><strong>Venue:</strong> <span class="company-venue"></span></p>
            </div>
        </div>
    </div>
</div>

<!-- Apply Modal -->
<div class="modal fade" id="applyModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title">Apply for Drive</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center p-5">
                <i class="fas fa-paper-plane fa-4x text-primary mb-4"></i>
                <h4>Confirm Application</h4>
                <p class="text-muted mb-0">Are you sure you want to apply for this drive?</p>
                <form id="applyForm" method="POST">
                    <input type="hidden" name="drive_id" id="applyDriveId">
                    <button type="submit" class="btn btn-primary btn-lg mt-3 pulse-glow">
                        <i class="fas fa-check me-2"></i>Yes, Apply Now
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {

    // Filter buttons
    document.querySelectorAll('[data-filter]').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('[data-filter]').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            const filter = this.dataset.filter;
            document.querySelectorAll('.drive-row').forEach(row => {
                row.style.display = (filter === 'all' || row.dataset.status === filter) ? '' : 'none';
            });
        });
    });

    // Open apply modal
    document.querySelectorAll('.apply-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.getElementById('applyDriveId').value = this.dataset.driveId;
            new bootstrap.Modal(document.getElementById('applyModal')).show();
        });
    });

    // Submit apply form
    document.getElementById('applyForm').addEventListener('submit', function(e) {
        e.preventDefault();
        fetch('apply_drive.php', {
            method: 'POST',
            body: new FormData(this)
        }).then(res => res.json()).then(data => {
            if (data.success) location.reload();
            else alert(data.message);
        });
    });

    // View company details
    document.querySelectorAll('.view-details').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelector('.company-title').innerText = this.dataset.name;
            document.querySelector('.company-description').innerText = this.dataset.description;
            document.querySelector('.company-eligibility').innerText = this.dataset.eligibility;
            document.querySelector('.company-package').innerText = this.dataset.package + " LPA";
            document.querySelector('.company-date').innerText = this.dataset.date;
            document.querySelector('.company-venue').innerText = this.dataset.venue;
            new bootstrap.Modal(document.getElementById('detailsModal')).show();
        });
    });

});
</script>

<?php include '../partials/footer.php'; ?>
