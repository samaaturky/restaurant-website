<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="login.css">
        <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css"
      integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />
    <title>Log In</title>
</head>
<body>
<?php
// Include database connection
require_once 'database.php';

session_start();

$error = ''; // Initialize error message variable

// Log out functionality
if (isset($_GET['logout'])) {
    session_unset(); // Remove all session variables
    session_destroy(); // Destroy the session
    header("Location: Signin.php"); // Redirect to the login page
    exit();
}

// Process login form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['Signin'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Check if email and password are not empty
    if (!empty($email) && !empty($password)) {
        // Prepare and execute the SQL statement
        $stmt = $conn->prepare("SELECT * FROM login WHERE Email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            // Verify password using password_verify()
          // Verify password using password_verify()
if (password_verify($password, $row['Password'])) {
  // Start session and save user details
  $_SESSION['email'] = $row['Email'];
  $_SESSION['name'] = $row['First']; // Save the user's first name

  // Redirect to index.php for all users
  header("Location: index.php");
  exit();
} else {
  $error = "Incorrect password. Please try again.";
}

        } else {
            $error = "No account found with this email.";
        }

        // Close the statement
        $stmt->close();
    } else {
        $error = "Please fill in all fields.";
    }
}
?>
<div class="login-div">
    <div class="form-div">
        <div class="rest-logo" onclick="window.location.href='index.php'">
            <img src="img/lunchIcon.png" alt="Flavor Haven Logo">
            <h1>Flavor Haven</h1>
        </div>
        <h2>Welcome Back</h2>
        <h4>Sign in with your email address and password</h4>

        <form method="POST" action="">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" placeholder="Enter your email" required>
            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Enter your password" required>
            <div class="show_password">
                 <label for="show_password" class="checkbox-container">
                 <input type="checkbox" id="show_password" class="checkbox-input">
                 <span class="checkbox-label">Show Password</span>
                 </label>
            </div>
            
            <button type="submit" name="Signin">Log In</button>
            <p>Don't have an account? <a href="Register.php">Register Now</a></p>
        </form>

        <?php if (!empty($error)): ?>
            <p style="color: red;"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
    </div>
</div>
<<script src="login.js"></script>
</body>
</html>