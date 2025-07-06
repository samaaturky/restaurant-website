<?php
// Include the database connection file
require_once 'database.php';

// Start session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: Signin.php");
    exit();
}

// Get the logged-in user's email from the session
$email = $_SESSION['email'];

// Fetch user data from the database for the logged-in user
$query = "SELECT * FROM login WHERE Email = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $email);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// Check if the user is an admin
$isAdmin = ($user['Role'] === 'admin'); // Adjust this based on your role naming

// Fetch past reservations for the logged-in user
$reservationQuery = "SELECT * FROM reservations WHERE email = ?";
$reservationStmt = $conn->prepare($reservationQuery);
$reservationStmt->bind_param("s", $email);
$reservationStmt->execute();
$userReservations = $reservationStmt->get_result();

// If the user is an admin, fetch all users and their reservations
$allUsers = [];
$allReservations = [];
if ($isAdmin) {
    // Fetch all users
    $allUsersQuery = "SELECT * FROM login";
    $allUsersStmt = $conn->prepare($allUsersQuery);
    $allUsersStmt->execute();
    $allUsers = $allUsersStmt->get_result();

    // Fetch all reservations
    $allReservationsQuery = "SELECT * FROM reservations";
    $allReservationsStmt = $conn->prepare($allReservationsQuery);
    $allReservationsStmt->execute();
    $allReservations = $allReservationsStmt->get_result();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['uploadProfilePic'])) {
  if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === UPLOAD_ERR_OK) {
      $fileTmpPath = $_FILES['profile_pic']['tmp_name'];
      $fileName = basename($_FILES['profile_pic']['name']);
      $fileType = mime_content_type($fileTmpPath);
      
      // Allow only image files
      $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
      if (!in_array($fileType, $allowedTypes)) {
          $error = "Invalid file type. Only JPG and PNG files are allowed.";
      } else {
          // Upload directory
          $uploadsDir = 'uploads/';
          if (!is_dir($uploadsDir)) {
              mkdir($uploadsDir, 0777, true);
          }

          // Generate a unique file path
          $newFilePath = $uploadsDir . time() . "_" . $fileName;
          if (move_uploaded_file($fileTmpPath, $newFilePath)) {
              // Update the database with the new profile picture path
              $updatePicQuery = "UPDATE login SET profile_pic = ? WHERE Email = ?";
              $stmt = $conn->prepare($updatePicQuery);
              $stmt->bind_param("ss", $newFilePath, $email);
              if ($stmt->execute()) {
                  $success = "Profile picture updated successfully!";
                  // Refresh user data to reflect the updated picture
                  $user['profile_pic'] = $newFilePath;
              } else {
                  $error = "Database update failed.";
              }
          } else {
              $error = "File upload failed.";
          }
      }
  } else {
      $error = "Please upload a valid file.";
  }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['removeProfilePic'])) {
  // Fetch the current profile picture path
  $currentPic = $user['profile_pic'];
  if ($currentPic && file_exists($currentPic)) {
      unlink($currentPic); // Delete the file from the server
  }

  // Set profile_pic to NULL in the database
  $removePicQuery = "UPDATE login SET profile_pic = NULL WHERE Email = ?";
  $stmt = $conn->prepare($removePicQuery);
  $stmt->bind_param("s", $email);
  if ($stmt->execute()) {
      $success = "Profile picture removed successfully!";
      // Update user data for the current session
      $user['profile_pic'] = NULL;
  } else {
      $error = "Failed to remove profile picture.";
  }
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Personal Profile</title>
    <link rel="stylesheet" href="profile.css" />
    <link rel="stylesheet" href="nav.css">
    <link rel="stylesheet" href="footer.css">
        <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css"
      integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />
</head>
<body>

<?php
// Logout functionality
if (isset($_POST['Logout'])) {
    session_destroy();
    header("Location: Signin.php");
    exit();
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
 <!-- Personal profile picture -->
<div class="divider"></div>
<main>
 <div class="profile-container">  
 <div class="profile-picture-container">
    <img src="<?php echo $user['profile_pic'] ? htmlspecialchars($user['profile_pic']) : 'img/pp.jpeg'; ?>"
         alt="Profile Picture" class="profile-picture" id="profilePicture" />
    <!-- Form to Upload New Photo -->
    <form action="" method="POST" enctype="multipart/form-data">
        <input type="file" name="profile_pic" accept="image/*" required />
             <div class="button-div">
        <button type="submit" name="uploadProfilePic">Change Profile Picture</button>
    </form>

    <!-- Form to Remove Photo -->
     <!-- "background-color: red; color: white; position:absolute;
         right: -100px; top: 108px; " -->
    <?php if ($user['profile_pic']): ?>
    <form action="" method="POST">
        <button type="submit" name="removeProfilePic" 
        style="background-color: red; color: white; ">
            Remove
        </button>
    </form>
    <?php endif; ?>
    </div>

</div>


  <!-- Personal Information Section -->
  <div class="personal-info">
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['saveChanges'])) {
        // Get the new values from the form
        $newUsername = $_POST['username'];
        $newEmail = $_POST['email'];
        $newPhone = $_POST['phoneNumber'];
        // Validate inputs (you can add more validation as needed)
        if (filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
          // Update the user's data in the database
          $updateQuery = "UPDATE login SET First = ?, Email = ?, Phone = ? WHERE Email = ?";
          $updateStmt = $conn->prepare($updateQuery);
          $updateStmt->bind_param("ssss", $newUsername, $newEmail, $newPhone, $email);

          if ($updateStmt->execute()) {
              // Update the session email and reload the page
              $_SESSION['email'] = $newEmail;
              header("Location: profile.php");
              exit();
          } else {
              echo "<p>Error updating information. Please try again.</p>";
          }
      } else {
          echo "<p>Invalid email format. Please try again.</p>";
      }
  }
  ?>
  <form action="" method="post" id="editForm">
      <p id="username">
          <label>Username:</label>
          <input type="text" name="username" value="<?php echo htmlspecialchars($user['First']); ?>" readonly>
      </p>
      <p id="email">
          <label>Email:</label>
          <input type="email" name="email" value="<?php echo htmlspecialchars($user['Email']); ?>" readonly>
      </p>
      <p id="phoneNumber">
          <label>Phone number:</label>
          <input type="text" name="phoneNumber" value="<?php echo htmlspecialchars($user['Phone']); ?>" readonly>
      </p>
      <!-- Buttons -->
      <button type="button" id="editButton" onclick="enableEdit()">Edit</button>
      <button type="submit" name="saveChanges" id="saveChanges" style="display:none;">Save Changes</button>
  </form>
</div>

  <!-- User's Past Reservations -->
  <div class="past-reservations">
      <h3>Past Reservations</h3>
      <table>
          <thead>
              <tr>
                  <th>Name</th>
                  <th>Date</th>
                  <th>Number of People</th>
              </tr>
          </thead>
          <tbody>
              <?php if ($userReservations->num_rows > 0): ?>
                  <?php while ($reservation = $userReservations->fetch_assoc()): ?>
                      <tr>
                          <td><?php echo htmlspecialchars($reservation['name']); ?></td>
                          <td><?php echo htmlspecialchars($reservation['reservation_date']); ?></td>
                          <td><?php echo htmlspecialchars($reservation['number_of_people']); ?></td>
                      </tr>
                  <?php endwhile; ?>
              <?php else: ?>
                  <tr>
                      <td colspan="3">No past reservations found.</td>
                  </tr>
              <?php endif; ?>
          </tbody>
      </table>
  </div>

  <!-- Admin Section: Display All Users -->
  <?php if ($isAdmin): ?>
  <div class="past-reservations">
      <h3>All Users</h3>
      <table>
          <thead>
              <tr>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Phone</th>
              </tr>
          </thead>
          <tbody>
              <?php while ($user = $allUsers->fetch_assoc()): ?>
                  <tr>
                      <td><?php echo htmlspecialchars($user['First']) . " " . htmlspecialchars($user['Last']); ?></td>
                      <td><?php echo htmlspecialchars($user['Email']); ?></td>
                      <td><?php echo htmlspecialchars($user['Phone']); ?></td>
                  </tr>
              <?php endwhile; ?>
          </tbody>
      </table>
      <h3>All Reservations</h3>
        <table>
            <thead>
                <tr>
                    <th>User Email</th>
                    <th>Name</th>
                    <th>Date</th>
                    <th>Number of People</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($reservation = $allReservations->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($reservation['email']); ?></td>
                        <td><?php echo htmlspecialchars($reservation['name']); ?></td>
                        <td><?php echo htmlspecialchars($reservation['reservation_date']); ?></td>
                        <td><?php echo htmlspecialchars($reservation['number_of_people']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>
                </main>

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
              <i class="fa-brands fa-square-x-twitter"></i
              ><a href="#">Twitter</a>
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

</body>
</html>
<script>
    
    function enableEdit() {
        const formInputs = document.querySelectorAll("#editForm input");
        const editButton = document.getElementById("editButton");
        const saveButton = document.getElementById("saveChanges");

        // Enable all input fields for editing
        formInputs.forEach(input => {
            input.removeAttribute("readonly");
            input.style.border = "1px solid #ccc"; // Optional: Add visual cue for editable fields
        });

        // Show the "Save" button and hide the "Edit" button
        editButton.style.display = "none";
        saveButton.style.display = "inline-block";
    }
</script>