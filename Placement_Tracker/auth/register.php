<?php
session_start();
require_once "../config/db.php"; // db.php with $pdo (PDO)

if(isset($_POST['register'])){
    $name     = trim($_POST['name']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
      $email = trim($_POST['email']);
    $role     = trim($_POST['role']);

    // Check if user already exists
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    if($stmt->rowCount() > 0){
        echo "<script>alert('⚠️ Username already exists!'); window.location='register.php';</script>";
        exit();
    }

    // Insert new user
    $stmt = $pdo->prepare("INSERT INTO users (full_name, username,email, password, role) VALUES (?, ?, ?,?, ?)");
    $stmt->execute([$name, $username, $email , $password, $role]);

    echo "<script>alert('🎉 Registered Successfully! Now Login.'); window.location='login.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Register</title>
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
.register-card {
    background: #fff;
    padding: 40px 30px;
    border-radius: 12px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.2);
    width: 100%;
    max-width: 400px;
}

/* TITLE */
.register-card h2 {
    text-align: center;
    margin-bottom: 25px;
    color: #333;
}

/* INPUTS */
.register-card input, 
.register-card select {
    width: 100%;
    padding: 12px 15px;
    margin-bottom: 20px;
    border: 1px solid #ccc;
    border-radius: 8px;
    font-size: 15px;
    transition: 0.3s;
}

.register-card input:focus, 
.register-card select:focus {
    border-color: #2575fc;
    outline: none;
}

/* BUTTON */
.register-card button {
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

.register-card button:hover {
    background: #6a11cb;
}

/* FOOTER */
.register-card .footer {
    text-align: center;
    margin-top: 15px;
    font-size: 14px;
}

.register-card .footer a {
    color: #2575fc;
    text-decoration: none;
    font-weight: bold;
}

.register-card .footer a:hover {
    text-decoration: underline;
}
</style>
</head>
<body>

<div class="register-card">
    <h2>Register</h2>
    <form action="register.php" method="POST">
        <input type="text" name="name" placeholder="Full Name" required>
        <input type="email" name="email" placeholder="Enter your E-mail" required>
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>

        <!-- hidden role value -->
        <input type="hidden" name="role" value="student">

        <button type="submit" name="register">Register</button>
    </form>

    <div class="footer">
        Already have an account? <a href="login.php">Login Here</a>
    </div>
</div>


</body>
</html>
