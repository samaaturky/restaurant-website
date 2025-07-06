<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="styles.css" />
    <link rel="stylesheet" href = "nav.css" />
    <link rel="stylesheet" href = "footer.css" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css"
      integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />
    <title>Restaurant</title>
</head>
<body>
<?php
session_start();

// Logout functionality
if (isset($_POST['Logout'])) {
    session_destroy();
    header("Location: Signin.php"); // Redirect to Signin.php after logout
    exit();
}
?>

<header class='header' id='header'>
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
    <!-- NavBar END -->

    <!-- Hero Start  -->
    <div class="content">
        <h1>
            Welcome to <span>Flavor Haven</span>, Where Every Bite is an
            Adventure!
        </h1>
        <h4>
            Indulge in our culinary creations and satisfy your cravings with every
            dish.
        </h4>
        <div class="buttons">
    <button class="btn-book" onclick="window.location.href='booking.php'">Book a Table</button>
</div>
        <div class="header-img">
            <img src="img/offer2.png" alt="floating dish" />
        </div>
    </div>
</header>
<!-- Hero Ends -->
 <!-- About US Start -->
<div class="divider"></div>
<section class="why-choose-us">
    <div class="container">
        <h2>Why People Choose Us</h2>
        <p>Our core values and exceptional service set us apart.</p>
        <div class="cards">
            <div class="thin-card">
                <i class="fa fa-check-circle"></i>
                <div class="text-content">
                    <h3>Choose your favorite</h3>
                    <p>
                        Lorem ipsum dolor sit amet consectetur adipisicing elit.
                        Consequuntur magnam, accusamus omnis cum fuga maiores?
                    </p>
                </div>
            </div>
            <div class="thin-card">
                <i class="fa fa-star"></i>
                <div class="text-content">
                    <h3>High Quality</h3>
                    <p>
                        Our products and services meet the highest industry standards.
                    </p>
                </div>
            </div>
            <div class="thin-card">
                <i class="fa fa-users"></i>
                <div class="text-content">
                    <h3>Customer Focused</h3>
                    <p>We prioritize your satisfaction in everything we do.</p>
                </div>
            </div>
        </div>
    </div>
</section>
 <!-- About US Ends -->
  <!-- Popular Start -->
<div class="divider"></div>
<section id="popular">
    <div class="content-title">
        <h1>Our Popular Dishes</h1>
        <p>most ordered by our clients</p>
    </div>
    <div class="floating-menu">
        <div class="card">
            <img src="img/d1.png" alt="" />
            <h4>Dish 1</h4>
            <p>Lorem ipsum dolor sit amet consectetur luptatum ex.</p>
            <form action="menu.php" method="get">
    <button type="submit" class="btn-order">Menu</button>
</form>
        </div>
        <div class="card">
            <img src="img/d2.png" alt="" />
            <h4>Dish 2</h4>
            <p>Lorem ipsum dolor sit amet consectetur luptatum ex.</p>
            <form action="menu.php" method="get">
    <button type="submit" class="btn-order">Menu</button>
</form>
        </div>
        <div class="card">
            <img src="img/d3.png" alt="" />
            <h4>Dish 3</h4>
            <p>Lorem ipsum dolor sit amet consectetur luptatum ex.</p>
            <form action="menu.php" method="get">
    <button type="submit" class="btn-order">Menu</button>
</form>
        </div>
    </div>
</section>
<div class="divider"></div>
<!-- Popular ENDs -->
<!-- Schedule Timing Start -->
<section class="schedule">
    <div class="schedule-content">
        <div class="schedule-items">
            <img src="img/breckfastIcon.png" alt="Breakfast" />
            <p><span>Breakfast</span><br />8:00am to 10:00am</p>
        </div>

        <div class="schedule-items">
            <img src="img/lunchIcon.png" alt="" />
            <p><span>Lunch</span><br />4:00pm to 7:00pm</p>
        </div>

        <div class="schedule-items">
            <img src="img/dinnerIcon.png" alt="" />
            <p><span>Dinner</span><br />9:00pm to 1:00am</p>
        </div>

        <div class="schedule-items">
            <img src="img/dessertIcon.png" alt="" />
            <p><span>Dessert</span><br />All day</p>
        </div>
    </div>
</section>
<!-- Schedule Timing End -->
<!-- Footer -->
<div class="divider"></div>
<footer>
    <div class="footer-content">
        <div class="footer-links">
            <ul>
<li><a href="index.php">Home</a></li>
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
<!-- Footer ends -->
<script src="nav.js"></script>
</body>
</html>