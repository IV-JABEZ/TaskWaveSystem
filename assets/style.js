// Validate password length before submitting registration form
function validateForm() {
    var password = document.getElementById('password').value;
    if(password.length < 6) {
        alert("Password must be at least 6 characters long.");
        return false; // stop form submission
    }
    return true; // allow form submission
}

// Toggle password visibility
function togglePassword() {
    var passwordInput = document.getElementById('password');
    passwordInput.type = (passwordInput.type === "password") ? "text" : "password";
}

// Show employee role if Employee is selected
function checkPosition() {
    var position = document.getElementById("position").value;
    var roleBox = document.getElementById("employeeRoleBox");
    roleBox.style.display = (position === "employee") ? "block" : "none";
}