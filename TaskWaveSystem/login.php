<?php
session_start();
include "include/db.php";

$error = ""; // Variable to hold error messages

if(isset($_POST['login']))
{
    $email = $_POST['email'];
    $password = $_POST['password'];
    $position = $_POST['position']; // employee or manager

    $sql = "SELECT * FROM user WHERE email='$email' AND position='$position'";
    $result = $conn->query($sql);

    if($result->num_rows > 0)
    {
        $user = $result->fetch_assoc();

        if(password_verify($password,$user['password']))
        {
            $_SESSION['user'] = $user['name'];
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['position'] = $user['position'];

            // Redirect based on position
            if($user['position'] === 'manager'){
                header("Location: Manager/manager_dashboard.php");
            } else {
                header("Location: Employee/employee_dashboard.php");
            }
            exit();
        }
        else
        {
            $error = "Incorrect credentials. Please try again.";
        }

    }
    else
    {
        $error = "Incorrect credentials. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login - TaskWave</title>
<link rel="stylesheet" href="assets\style.css" />

<style>
    
.box{background:white;padding:50px 40px;border-radius:15px;width:360px;text-align:center;box-shadow:0 15px 40px rgba(0,0,0,0.2);animation: fadeSlide 1s ease;}
@keyframes fadeSlide{from{opacity:0; transform:translateY(30px);}to{opacity:1; transform:translateY(0);}}
h2{margin-bottom:20px; color:#333; font-weight:500;}

input, select{width:100%; padding:12px 40px 12px 12px; margin:10px 0; border-radius:8px; border:1px solid #ccc; outline:none; transition:0.3s;}
input:focus, select:focus{border-color:#4CAF50;}
.password-wrapper{position:relative;}
.password-wrapper .toggle-eye{position:absolute; right:12px; top:50%; transform:translateY(-50%); cursor:pointer; width:24px; height:24px; fill:#666;}
button{width:100%;padding:12px;margin-top:15px;border:none;border-radius:8px;background:#4CAF50;color:white;font-weight:500;cursor:pointer;transition:all 0.3s ease;}
button:hover{transform:translateY(-3px);box-shadow:0 8px 18px rgba(0,0,0,0.2);}
button:active{transform:scale(0.97);}
.register-link{
    display:block;
    margin-top:15px;
    color:#2196F3;
    text-decoration:none;
    font-weight:500;
}
.register-link:hover{text-decoration:underline;}
/* Error message style */
.error-message{
    color:red;
    margin-bottom:10px;
    font-size:14px;
    text-align:left;
}
</style>
</head>
<body>

<div class="box">
<h2>Login</h2>

<!-- Display error message -->
<?php if($error != ""): ?>
    <div class="error-message"><?php echo $error; ?></div>
<?php endif; ?>

<form method="POST">
<input type="email" name="email" placeholder="Email" required>

<div class="password-wrapper">
<input type="password" id="password" name="password" placeholder="Password" required>
<svg class="toggle-eye" onclick="togglePassword()" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
    <path d="M12 5c-7.633 0-12 7-12 7s4.367 7 12 7 12-7 12-7-4.367-7-12-7zm0 12c-2.761 0-5-2.239-5-5s2.239-5 5-5
    5 2.239 5 5-2.239 5-5 5zm0-8c-1.654 0-3 1.346-3 3s1.346 3 3 3 3-1.346 3-3-1.346-3-3-3z"/>
</svg>
</div>

<select name="position" required>
    <option value="">Select Position</option>
    <option value="employee">Employee</option>
    <option value="manager">Manager</option>
</select>

<button name="login">Login</button>
</form>

<!-- Registration link -->
<a href="register.php" class="register-link">Don't have an account? Register</a>

</div>

<script>
function togglePassword(){
    var passwordInput = document.getElementById('password');
    var eyeIcon = document.querySelector('.toggle-eye path');
    if(passwordInput.type === "password"){
        passwordInput.type = "text";
        eyeIcon.setAttribute("d","M12 5c-7.633 0-12 7-12 7s4.367 7 12 7 12-7 12-7-4.367-7-12-7zm0 0l0 14"); 
    } else {
        passwordInput.type = "password";
        eyeIcon.setAttribute("d","M12 5c-7.633 0-12 7-12 7s4.367 7 12 7 12-7 12-7-4.367-7-12-7zm0 12c-2.761 0-5-2.239-5-5s2.239-5 5-5 5 2.239 5 5-2.239 5-5 5zm0-8c-1.654 0-3 1.346-3 3s1.346 3 3 3 3-1.346 3-3-1.346-3-3-3z");
    }
}
</script>

</body>
</html>