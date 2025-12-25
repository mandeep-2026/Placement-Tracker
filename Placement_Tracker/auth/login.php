<?php
session_start();
require_once "../config/db.php"; // db.php defines $pdo (PDO)

if(isset($_POST['login'])){
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $email    = trim($_POST['email']);
    $role     = trim($_POST['role']);

    // Correct Query (use 4 conditions since email is required)
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? AND password = ? AND email = ? AND role = ?");
    $stmt->execute([$username, $password, $email, $role]);
    $user = $stmt->fetch();

    if($user){
        // Set session
        $_SESSION['user_id']  = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['email']    = $user['email'];
        $_SESSION['role']     = $user['role'];

        // Redirect by role
        if($user['role'] === 'admin'){
            header("Location: ../adm/dashboard.php");
        } else {
            header("Location: ../student/dashboard.php");
        }
        exit();
    } else {
        echo "<script>alert('❌ Invalid username/email/password/role!'); window.location='login.php';</script>";
        exit();
    }
}
?>


<!DOCTYPE html>
<html>
<head>
<title>Login</title>
<style>
/* RESET */
* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

/* BODY */
body {
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    background: linear-gradient(135deg, #6a11cb, #2575fc);
}

/* CARD */
.login-card {
    background: #fff;
    padding: 40px 30px;
    border-radius: 12px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.2);
    width: 100%;
    max-width: 400px;
}

/* TITLE */
.login-card h2 {
    text-align: center;
    margin-bottom: 25px;
    color: #333;
}

/* INPUTS */
.login-card input, 
.login-card select {
    width: 100%;
    padding: 12px 15px;
    margin-bottom: 20px;
    border: 1px solid #ccc;
    border-radius: 8px;
    font-size: 15px;
    transition: 0.3s;
}

.login-card input:focus, 
.login-card select:focus {
    border-color: #2575fc;
    outline: none;
}

/* BUTTON */
.login-card button {
    width: 100%;
    padding: 12px;
    background: #2575fc;
    border: none;
    border-radius: 8px;
    color: #fff;
    font-size: 16px;
    cursor: pointer;
    transition: 0.3s;
}

.login-card button:hover {
    background: #6a11cb;
}

/* FOOTER */
.login-card .footer {
    text-align: center;
    margin-top: 15px;
    font-size: 14px;
}

.login-card .footer a {
    color: #2575fc;
    text-decoration: none;
    font-weight: bold;
}

.login-card .footer a:hover {
    text-decoration: underline;
}
</style>
</head>
<body>

<div class="login-card">
    <h2>Login</h2>
    <form action="login.php" method="POST">
        <input type="text" name="username" placeholder="Enter Username" required>
          <input type="text" name="email" placeholder="Enter E-mail Id" required>
        <input type="password" name="password" placeholder="Enter Password" required>
        <select name="role" required>
            <option value="">Select Role</option>
            <option value="admin">Admin</option>
            <option value="student">Student</option>
        </select>
        <button type="submit" name="login">Login</button>
    </form>
    <div class="footer">
        Don't have an account? <a href="register.php">Register Here</a>
    </div>
</div>

</body>
</html>
