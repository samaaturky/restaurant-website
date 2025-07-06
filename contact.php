<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <link rel="stylesheet" href="contact.css">
    <link rel="stylesheet" href="nav.css" />
    <link rel="stylesheet" href="footer.css">
    <link rel="stylesheet" href="global.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
</head>
<body>
<?php
session_start();
require_once 'database.php';

// Fetch user role
$email = isset($_SESSION['email']) ? $_SESSION['email'] : null;
$isAdmin = false;

// Only fetch user details if the user is logged in
if ($email) {
    $query = "SELECT * FROM login WHERE Email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
    $isAdmin = ($user['Role'] === 'admin');
    $stmt->close();
}

// Logout functionality
if (isset($_POST['Logout'])) {
    session_destroy();
    header("Location: Signin.php");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['first_name'])) {
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $email = $_POST['email'];
    $message = $_POST['message'];

if ($email !== $_SESSION['email']) {
        echo "<div style='margin-top:90px' class='alert alert-danger'>Email does not match your logged-in email.</div>";
    } else{
    // Insert message into the database
    $insertQuery = "INSERT INTO messages (first_name, last_name, email, message) VALUES (?, ?, ?, ?)";
    $insertStmt = $conn->prepare($insertQuery);
    if ($insertStmt) {
        $insertStmt->bind_param("ssss", $firstName, $lastName, $email, $message);
        if (!$insertStmt->execute()) {
            echo "Error inserting message: " . $conn->error; // Debugging output
        }
    } else {
        echo "Error preparing statement: " . $conn->error; // Debugging output
    }
}
}


// If the user is an admin, fetch all messages
$allmessages = [];
if ($isAdmin) {
    $allmessagesQuery = "SELECT * FROM messages";
    $allmessagesStmt = $conn->prepare($allmessagesQuery);
    $allmessagesStmt->execute();
    $allmessages = $allmessagesStmt->get_result();
}
?>
    <!-- NavBar -->
<div id="nav-bar">
        <nav>
            <div class="rest-logo" onclick="window.location.href='index.php'">
                <img src="img/lunchIcon.png" alt="" />
                <h1>Flavor Haven</h1>
            </div>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="menu.php">Menu</a></li>
                <li><a href="booking.php">Booking</a></li>
                <li><a href="contact.php">Contact</a></li>

                <?php if (isset($_SESSION['email'])): ?>
                    <li class="log-in-nav">
                        <a href="Signin.php?logout=true" class="logout-button">Log out</a>
                    </li>
                <?php else: ?>
                    <li class="log-in-nav"><a href="Signin.php">Log in</a></li>
                    <li class="register-nav"><a href="Register.php">Register</a></li>
                <?php endif; ?>
                <?php if (isset($_SESSION['email'])): ?>
                    <li><a href="profile.php"><i class="fa-solid fa-user"></i>Profile</a></li>
                <?php endif; ?>
            </ul>
            <div id="sidebar-toggle">
                <i class="fa-solid fa-bars"></i>
            </div>
        </nav>
    </div>
    <div id="sidebar">
            <ul>
                <li class='thin-card'><a href="index.php">Home</a></li>
                <li class='thin-card'><a href="menu.php">Menu</a></li>
                <li class='thin-card'><a href="booking.php">Booking</a></li>
                <li class='thin-card'><a href="contact.php">Contact</a></li>
                <?php if (isset($_SESSION['email'])): ?>
                    <li class='thin-card'>
                        <a href="Signin.php?logout=true" class="logout-button">Log out</a>
                    </li>
                <?php else: ?>
                    <li class='thin-card'><a href="Signin.php">Log in</a></li>
                    <li class='thin-card'><a href="Register.php">Register</a></li>
                <?php endif; ?>
                <?php if (isset($_SESSION['email'])): ?>
                    <li class='thin-card'><a href="profile.php"><i class="fa-solid fa-user"></i>Profile</a></li>
                <?php endif; ?>
            </ul>
    </div>
    <div id="overlay"></div>
    
    <div class="divider"></div>
    

    <div class="container">
    <img src="img/5132732-removebg-preview.png" alt="contact image">

    <?php if (!$isAdmin): // Show form only if the user is not an admin ?>
        
                        <div class="form-container">
              <div class="text-content">
        <h1>Contact us</h1>
            <p>
                Need to get in touch with us? Fill out the form with your inquiry or find our email
                <a href="#" class="department-email">here</a>.
            </p>
           
        </div>
                <form action="" method="POST" class="contact-form" onsubmit="return checkLogin()">
                    <h3>Let’s taco 'bout it!</h3>
                    <div class="form-group">
                        <label for="first-name">First name:</label>
                        <input type="text" id="first-name" name="first_name" 
                               value="<?php echo isset($_SESSION['first_name']) ? htmlspecialchars($_SESSION['first_name']) : ''; ?>" 
                               required>
                    </div>
                    <div class="form-group">
                        <label for="last-name">Last name:</label>
                        <input type="text" id="last-name" name="last_name" 
                               value="<?php echo isset($_SESSION['last_name']) ? htmlspecialchars($_SESSION['last_name']) : ''; ?>" 
                               required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" 
                               value="<?php echo isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : ''; ?>" 
                               placeholder="user@gmail.com" required>
                    </div>
                    <div class="form-group">
                        <label for="message">What can we help you with?</label>
                        <textarea id="message" name="message" placeholder="Your message here" required></textarea>
                    </div>
                    <button type="submit" class="submit-btn">Submit</button>
                </form>
                <div id="loginWarning" class="alert alert-warning" style="display:none;" > 
                    
                        You need to <a href="Signin.php">log in</a> to contact us . Please create an account if you don't have one.
                        
                    </div>
                        </div>
            <?php endif; ?>
        <!-- Admin Messages Section -->
        <?php if ($isAdmin): ?>
          <div class="admin-messages" >
    <h3 >Messages from Users</h3>
    <table>
       <thead>
        <tr>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>Message</th>
            <th>Created At</th>
        </tr>
    </thead>
    <tbody>
    <?php while ($msg = $allmessages->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($msg['first_name']); ?></td>
            <td><?php echo htmlspecialchars($msg['last_name']); ?></td>
            <td><?php echo htmlspecialchars($msg['email']); ?></td>
            <td><?php echo htmlspecialchars($msg['message']); ?></td>
            <td><?php echo htmlspecialchars($msg['created_at']); ?></td>
        </tr>
    <?php endwhile; ?>
    </tbody>
</table>
        </div>
        <?php endif; ?>
    </div>
    <div class="divider"></div>
    <footer>
        <div class="footer-content">
            <div class="footer-links">
                <ul>
                <li><a href="menu.php">Menu</a></li>
                <li><a href="booking.php">Booking</a></li>
                <li><a href="contact.php">Contact</a></li>
                <?php if (isset($_SESSION['email'])): ?>
                    <li>
                        <a href="Signin.php?logout=true" class="logout-button">Log out</a>
                    </li>
                <?php else: ?>
                    <li><a href="Signin.php">Log in</a></li>
                    <li><a href="Register.php">Register</a></li>
                <?php endif; ?>
                <?php if (isset($_SESSION['email'])): ?>
                    <li><a href="profile.php">Profile</a></li>
                <?php endif; ?>
                </ul>
            </div>
            <div class="footer-social">
                <ul>
                    <li>
                        <i class="fa-brands fa-facebook"></i><a href="#">Facebook</a>
                    </li>
                    <li>
                        <i class="fa-brands fa-instagram"></i><a href="#">Instagram</a>
                    </li>
                    <li>
                        <i class="fa-brands fa-square-x-twitter"></i><a href="#">Twitter</a>
                    </li>
                </ul>
            </div>
            <div class="rest-logo" onclick="window.location.href='index.php'">
                <img src="img/lunchIcon.png" alt="" />
                <h1>Flavor Haven</h1>
            </div>
        </div>
            <i class="fa-solid fa-arrow-up" id="scroll-to-top">
  <a href="#"></a>
</i>
    </footer>
    <script src="nav.js"></script>
    <script src="contact.js"></script>
    <script>
function checkLogin() {
    <?php if (!isset($_SESSION['email'])): ?>
        document.getElementById('loginWarning').style.display = 'block';
        return false; // Prevent form submission
    <?php else: ?>
        return true; // Allow form submission
    <?php endif; ?>
}
</script>

</body>
</html>