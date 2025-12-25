<?php 
require_once '../config/db.php'; 
if (!isLoggedIn()) redirect('../auth/login.php');

$page_title = "Profile";
include '../partials/header.php'; 
include '../partials/navbar.php';

// Fetch current user
$userStmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$userStmt->execute([$_SESSION['user_id']]);
$user = $userStmt->fetch();

// Handle profile update
$success = '';
if ($_POST && isset($_POST['update_profile'])) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $school_name = trim($_POST['school_name']);
    $high_school_percentage = trim($_POST['high_school_percentage']);
    $inter_percentage = trim($_POST['inter_percentage']);
    $btech_percentage = trim($_POST['btech_percentage']);

    // Handle image upload
    $profile_image = $user['profile_image'];
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
        $allowed_ext = ['jpg','jpeg','png','gif'];
        $file_ext = strtolower(pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION));
        if (in_array($file_ext, $allowed_ext)) {
            $new_filename = 'user_'.$_SESSION['user_id'].'_'.time().'.'.$file_ext;
            $upload_dir = '../uploads/profile/';
            if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
            move_uploaded_file($_FILES['profile_image']['tmp_name'], $upload_dir.$new_filename);
            $profile_image = $new_filename;
        }
    }

    // Update user in DB
    $updateStmt = $pdo->prepare("
        UPDATE users SET 
            username = ?, 
            email = ?, 
            school_name = ?, 
            high_school_percentage = ?, 
            inter_percentage = ?, 
            btech_percentage = ?, 
            profile_image = ?
        WHERE id = ?
    ");
    $updateStmt->execute([
        $username, 
        $email, 
        $school_name, 
        $high_school_percentage, 
        $inter_percentage, 
        $btech_percentage,
        $profile_image,
        $_SESSION['user_id']
    ]);

    $success = "Profile updated successfully!";
    $user['username'] = $username;
    $user['email'] = $email;
    $user['school_name'] = $school_name;
    $user['high_school_percentage'] = $high_school_percentage;
    $user['inter_percentage'] = $inter_percentage;
    $user['btech_percentage'] = $btech_percentage;
    $user['profile_image'] = $profile_image;
}
?>

<div class="container px-4 py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card p-5 shadow-lg">
                <!-- Profile Header -->
                <div class="text-center mb-5">
                    <div class="avatar rounded-circle mx-auto mb-4 d-flex align-items-center justify-content-center" style="width: 120px; height: 120px; font-size: 3rem; background-color: #0d6efd; color: #fff; overflow: hidden;">
                        <?php if (!empty($user['profile_image'])): ?>
                            <img src="../uploads/profile/<?php echo htmlspecialchars($user['profile_image']); ?>" alt="Profile Image" style="width:100%; height:100%; object-fit:cover;">
                        <?php else: ?>
                            <?php echo strtoupper(substr($user['username'], 0, 1)); ?>
                        <?php endif; ?>
                    </div>
                    <h2 class="mb-2"><?php echo htmlspecialchars($user['username']); ?></h2>
                    <p class="text-muted mb-0">Student | Joined <?php echo date('M Y', strtotime($user['created_at'])); ?></p>
                </div>

                <?php if ($success): ?>
                    <div class="alert alert-success text-center"><?php echo $success; ?></div>
                <?php endif; ?>

                <!-- Profile Form -->
                <form method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-12 mb-3 text-center">
                            <label class="form-label fw-bold">Profile Image</label><br>
                            <input type="file" name="profile_image" accept="image/*">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Username</label>
                            <input type="text" name="username" class="form-control" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Email</label>
                            <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-bold">School Name</label>
                            <input type="text" name="school_name" class="form-control" value="<?php echo htmlspecialchars($user['school_name']); ?>" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">High School %</label>
                            <input type="number" step="0.01" name="high_school_percentage" class="form-control" value="<?php echo htmlspecialchars($user['high_school_percentage']); ?>" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Intermediate %</label>
                            <input type="number" step="0.01" name="inter_percentage" class="form-control" value="<?php echo htmlspecialchars($user['inter_percentage']); ?>" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">B.Tech %</label>
                            <input type="number" step="0.01" name="btech_percentage" class="form-control" value="<?php echo htmlspecialchars($user['btech_percentage']); ?>" required>
                        </div>
                    </div>
                    <div class="text-center mt-3">
                        <button type="submit" name="update_profile" class="btn btn-primary btn-lg pulse-glow px-5">
                            <i class="fas fa-save me-2"></i>Update Profile
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../partials/footer.php'; ?>
