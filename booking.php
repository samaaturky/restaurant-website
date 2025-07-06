<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Book a Table</title>
    <!-- Bootstrap CSS -->
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="booking.css" />
    <link rel="stylesheet" href="nav.css" />
    <link rel="stylesheet" href="footer.css" />
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
session_start();

// Logout functionality
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: Signin.php");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $date = $_POST['date'];
    $people = (int)$_POST['people'];
    require_once 'database.php';
    // Validate email
    if ($email !== $_SESSION['email']) {
        echo "<div style='margin-top:90px' class='alert alert-danger'>Email does not match your logged-in email.</div>";
    }  else {
        // Check if the name already exists
  // Check if the same name and date already exist
  $check_query = $conn->prepare("SELECT COUNT(*) FROM reservations WHERE name = ? AND reservation_date = ?");
  $check_query->bind_param("ss", $name, $date);
  $check_query->execute();
  $check_query->bind_result($count);
  $check_query->fetch();
  $check_query->close();
        if ($count > 0) {
            echo "<div style='margin-top:90px' class='alert alert-warning'>The name '$name' is already used for the date '$date'. Please choose a different name or date.</div>";
        } 
        else {
        // Check if the user has reached the reservation limit
        $reservation_limit_query = $conn->prepare("SELECT COUNT(*) FROM reservations WHERE email = ? AND reservation_date = ?");
        $reservation_limit_query->bind_param("ss", $email , $date);
        $reservation_limit_query->execute();
        $reservation_limit_query->bind_result($reservation_count);
        $reservation_limit_query->fetch();
        $reservation_limit_query->close();

        if ($reservation_count >= 4) {
            echo "<div style='margin-top:90px' class='alert alert-danger'>Unfortunately the resturant is fully booked on  $date.</div>";
        }
        else {
            // Prepare and bind
            $stmt = $conn->prepare("INSERT INTO reservations (name, email, reservation_date, number_of_people) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("sssi", $name, $email, $date, $people);

            // Execute the statement
            if ($stmt->execute()) {
                echo "<div style='margin-top:90px' class='alert alert-success'>Reservation successful for $name on $date for $people people.</div>";
            } else {
                echo "<div style='margin-top:90px' class='alert alert-danger'>Error: " . $stmt->error . "</div>";
            }

            $stmt->close();
        }
    }
}
}
?>
<!-- Header -->
<header>
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
</header>
<div class="divider"></div>
<!-- Main Section -->
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="combined-container p-4">
                <div class="video-container mb-4">
                    <video controls autoplay loop class="video-custom-size rounded">
                        <source src="img/vid_Trim.mp4" type="video/mp4" />
                        <source src="img/vid_Trim.webm" type="video/webm" />
                        Your browser does not support the video tag.
                    </video>
                </div>
                <!-- Reservation Form -->
                <div class="reservation-form">
                    <h2>Reservation</h2>
                    <h3>Book A Table Online</h3>
                    <form id="reservationForm" method="POST" onsubmit="return checkLogin()">
                        <div class="mb-3">
                            <label for="name" class="form-label">Your Name</label>
                            <input
                                type="text"
                                class="form-control"
                                id="name"
                                name="name"
                                placeholder="Enter your name"
                                required
                            />
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Your Email</label>
                            <input
                                type="email"
                                class="form-control"
                                id="email"
                                name="email"
                                placeholder="Enter your email"
                                required
                            />
                        </div>
                        <div class="mb-3">
                            <label for="date" class="form-label">Date</label>
                            <input type="date" class="form-control" id="date" name="date" required />
                        </div>
                        <div class="mb-3">
                            <label for="people" class="form-label">No. of People</label>
                            <select class="form-select" id="people" name="people" required>
                                <option value="1">1 Person</option>
                                <option value="2">2 People</option>
                                <option value="3">3 People</option>
                                <option value="4">4 People</option>
                            </select>
                        </div>
                        <button type="submit">Book Now</button>
                    </form>
                    <div id="loginWarning" class="alert alert-warning" style="display:none;">
                        You need to <a href="Signin.php">log in</a> to book a table. Please create an account if you don't have one.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<div class="divider"></div>
<footer>
    <div class="footer-content">
        <div class="footer-links">
            <ul>
                <li><a href="#">Home</a></li>
                <li><a href="#">Menu</a></li>
                <li><a href="#">Book Now</a></li>
                <li><a href="#">About us</a></li>
                <li><a href="#">Register</a></li>
                <li><a href="Signin.php">Log in</a></li>
                <li><a href="#">Contact</a></li>
                <li><a href="#">Profile</a></li>
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

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
<script src='booking.js'></script>
<script src="nav.js"></script>
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