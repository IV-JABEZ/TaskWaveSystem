<?php
// Include database connection
include "include/db.php";

// Check if register button was clicked
if(isset($_POST['register']))
{
    // Get form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password_input = $_POST['password']; // Raw password

    // Server-side password length validation
    if(strlen($password_input) < 6){
        echo "<script>alert('Password must be at least 6 characters long');</script>";
    } else {

        // Hash the password for security
        $password = password_hash($password_input, PASSWORD_DEFAULT);

        // Get selected position
        $position = $_POST['position'];

        // Get employee specific role if available
        $employee_role = isset($_POST['employee_role']) ? $_POST['employee_role'] : '';

        // Check if email already exists in database
        $check = $conn->query("SELECT * FROM `user` WHERE email='$email'");

        if($check->num_rows > 0){
            echo "<script>alert('Email already registered');</script>";
        } 
        else {
            // Insert new user into database
            $sql = "INSERT INTO `user` (name,email,position,employee_role,password)
                    VALUES ('$name','$email','$position','$employee_role','$password')";

            if($conn->query($sql) === TRUE){
                echo "<script>alert('Registration Successful! You can now log in.'); window.location='login.php';</script>";
            } 
            else {
                echo "Error: " . $conn->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register - TaskWave</title>
<link rel="stylesheet" href="assets\style.css" />

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Roboto',sans-serif;
}



@keyframes gradientMove{
0%{background-position:0% 50%;}
50%{background-position:100% 50%;}
100%{background-position:0% 50%;}
}

.box{
    background:white;
    padding:50px 40px;
    border-radius:15px;
    width:360px;
    text-align:center;
    box-shadow:0 15px 40px rgba(0,0,0,0.2);
}

h2{
    margin-bottom:20px;
    color:#333;
}

input, select{
    width:100%;
    padding:12px;
    margin:10px 0;
    border-radius:8px;
    border:1px solid #ccc;
}

.password-wrapper{
    position:relative;
}

.password-wrapper .toggle-eye{
    position:absolute;
    right:12px;
    top:50%;
    transform:translateY(-50%);
    width:24px;
    height:24px;
    cursor:pointer;
    fill:#666;
}

.password-hint{
    display:block;
    text-align:left;
    margin-top:4px;
    font-size:12px;
    color:#777;
}

button{
    width:100%;
    padding:12px;
    margin-top:15px;
    border:none;
    border-radius:8px;
    background:#2196F3;
    color:white;
    font-weight:500;
    cursor:pointer;
}
.login-link {
    
    font-size:13px;
    color: skyblue;
}
</style>
</head>

<body>

<div class="box">

<h2>Create Account</h2>

<!-- Registration Form -->
<form method="POST" onsubmit="return validateForm()">

<input type="text" name="name" placeholder="Full Name" required>
<input type="email" name="email" placeholder="Email" required>

<div class="password-wrapper">
    <input type="password" id="password" name="password" placeholder="Password" required>
    <svg class="toggle-eye" onclick="togglePassword()" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
        <path d="M12 5c-7.633 0-12 7-12 7s4.367 7 12 7 12-7 12-7-4.367-7-12-7zm0 12c-2.761 0-5-2.239-5-5s2.239-5 5-5
        5 2.239 5 5-2.239 5-5 5zm0-8c-1.654 0-3 1.346-3 3s1.346 3 3 3 3-1.346 3-3-1.346-3-3-3z"/>
    </svg>
    <small class="password-hint">Password must be at least 6 characters</small>
</div>

<select name="position" id="position" onchange="checkPosition()" required>
<option value="">Select Your Position</option>
<option value="employee">Employee</option>
<option value="manager">Manager</option>
</select>

<div id="employeeRoleBox" style="display:none;">
<input type="text" name="employee_role" placeholder="Enter Employee Role (Developer, HR, Cashier etc.)">
</div>

<button name="register">Register</button>

</form>

<a href="login.php" class="login-link">Already have an account? Login</a>

</div>

<!-- Link to external JS -->
<script src="assets/style.js"></script>

</body>
</html>