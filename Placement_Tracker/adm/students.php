<?php 
require_once '../config/db.php';
if (!isLoggedIn() || !isAdm1n()) redirect('../auth/login.php');

$page_title = "Students List";
include '../partials/header.php';
include '../partials/navbar.php';

$students = $pdo->query("SELECT full_name, username, email , school_name ,high_school_percentage , inter_percentage , btech_percentage  FROM users WHERE role='student'");
?>

<div class="container-fluid px-4 py-4">
    <h3 class="mb-4"><i class="fas fa-users me-2 text-primary"></i>Students List</h3>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-bordered table-hover mb-0">
                <thead class="table-primary">
                    <tr>
                        <th>Full Name</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>School </th>
                        <th>High School Percentage</th>
                        <th>Inter Percentage</th>
                        <th>Btech percentage</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $students->fetch()): ?>
                        <tr>
                            <td><?php echo $row['full_name']; ?></td>
                            <td><?php echo $row['username']; ?></td>
                            <td><?php echo $row['email']; ?></td>
                            <td><?php echo $row['school_name']; ?></td>
                            <td><?php echo $row['high_school_percentage']; ?></td>
                            <td><?php echo $row['inter_percentage']; ?></td>
                            <td><?php echo $row['btech_percentage']; ?></td>
                            
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../partials/footer.php'; ?>
 