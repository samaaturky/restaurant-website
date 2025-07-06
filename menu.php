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

// Handle item addition
if (isset($_POST['add_item'])) {
    $name = htmlspecialchars($_POST['name']);
    $category = htmlspecialchars($_POST['category']);
    $price = $_POST['price'];
    $description = htmlspecialchars($_POST['description']);
    $image = 'img/' . basename($_FILES['image']['name']);

    // Move uploaded file
    if (move_uploaded_file($_FILES['image']['tmp_name'], $image)) {
        $stmt = $conn->prepare("INSERT INTO menus (name, category, price, description, image) VALUES (?, ?, ?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("ssiss", $name, $category, $price, $description, $image);
            $stmt->execute();
            $stmt->close();
            header("Location: menu.php");
            exit();
        } else {
            echo "<p>Error: Failed to insert the item into the database.</p>";
        }
    } else {
        echo "<p>Error uploading item image.</p>";
    }
}

// Handle new category addition
if (isset($_POST['add_category'])) {
    $newCategory = htmlspecialchars($_POST['new_category']);
    $categoryImage = 'img/' . basename($_FILES['category_image']['name']);

    // Check if category already exists
    $stmt = $conn->prepare("SELECT COUNT(*) FROM menus WHERE category = ?");
    $stmt->bind_param("s", $newCategory);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count <= 0) {
        // Move uploaded file
        if (move_uploaded_file($_FILES['category_image']['tmp_name'], $categoryImage)) {
            $stmt = $conn->prepare("INSERT INTO menus (category, category_image) VALUES (?, ?)");
            $stmt->bind_param("ss", $newCategory, $categoryImage);
            $stmt->execute();
            $stmt->close();
            header("Location: menu.php");
            exit();
        } else {
            echo "<p>Error uploading category image.</p>";
        }
    } else {
        echo "<p>Category already exists.</p>";
    }
}

// Handle category deletion
if (isset($_POST['delete_category'])) {
    $categoryToDelete = $_POST['delete_category'];

    // Delete all menu items under the category
    $stmt = $conn->prepare("DELETE FROM menus WHERE category = ?");
    $stmt->bind_param("s", $categoryToDelete);
    if ($stmt->execute()) {
        $stmt->close();
        header("Location: menu.php");
        exit();
    } else {
        echo "<p>Error: Could not delete category and its items.</p>";
    }
}

// Handle item deletion
if (isset($_POST['delete_item'])) {
    $itemId = $_POST['item_id'];
    $stmt = $conn->prepare("DELETE FROM menus WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $itemId);
        if ($stmt->execute()) {
            $stmt->close();
            header("Location: menu.php");
            exit();
        } else {
            echo "<p>Error: Could not delete item.</p>";
        }
    }
}



// Fetch user role



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Restaurant Menu</title>
    <link rel="stylesheet" href="nav.css" />
    <link rel="stylesheet" href="footer.css" />
    <link rel="stylesheet" href="menu.css" />
    <link rel="stylesheet" href="global.css"/>
    
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" />
        <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css"
      integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />
</head>
<body>
<header class="menu-header">
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
    <h1>Menu</h1>
</header>

<!-- Display Menu Cards (Accessible to All Users) -->
<div class="menu-container">
<section class="menu-cards">
    <?php
    $categoriesQuery = "SELECT DISTINCT category, category_image FROM menus WHERE category IS NOT NULL AND category_image IS NOT NULL";
    $categoriesResult = $conn->query($categoriesQuery);

    while ($category = $categoriesResult->fetch_assoc()) {
        $currentCategory = $category['category'];
        $categoryImage = $category['category_image'] ?? 'img/default_category.jpg';

        echo "<div class='menu-card' data-category='" . htmlspecialchars($currentCategory) . "'>";
        echo "<img src='" . htmlspecialchars($categoryImage) . "' alt='" . htmlspecialchars($currentCategory) . "' />";
        echo "<div class='card-header'>" . htmlspecialchars(ucfirst($currentCategory)) . "</div>";
        // Delete Category Button
        if ($isAdmin) {
            echo "<form method='POST' style='display:inline;'>";
            echo "<input type='hidden' name='delete_category' value='" . htmlspecialchars($currentCategory) . "'>";
            echo "<button type='submit' class='delete-button'>Delete Category</button>";
            echo "</form>";
        }

        $itemsQuery = "SELECT * FROM menus WHERE category = ? AND name IS NOT NULL AND image IS NOT NULL";
        $itemsStmt = $conn->prepare($itemsQuery);
        $itemsStmt->bind_param("s", $currentCategory);
        $itemsStmt->execute();
        $itemsResult = $itemsStmt->get_result();
        echo "<div class='menu-items-container' style='display: none;'>";
        while ($item = $itemsResult->fetch_assoc()) {
            echo "<div class='menu-item'>";
            echo "<img src='" . htmlspecialchars($item['image']) . "' alt='" . htmlspecialchars($item['name']) . "' />";
            echo "<h3>" . htmlspecialchars($item['name']) . "</h3>";
            echo "<p>$" . htmlspecialchars($item['price']) . "</p>";
            echo "<p>" . htmlspecialchars($item['description']) . "</p>";

            // Delete Item Button
            if ($isAdmin) {
                echo "<form method='POST' style='display:inline;'>";
                echo "<input type='hidden' name='item_id' value='" . htmlspecialchars($item['id']) . "'>";
                echo "<button type='submit' name='delete_item' class='delete-button'>Delete Item</button>";
                echo "</form>";

            }
            echo "</div>";
        }
        echo "</div></div>";
        $itemsStmt->close();
    }
    ?>
    
</section>
</div>
<script>
document.addEventListener("DOMContentLoaded", () => {
    // Select all menu cards
    const menuCards = document.querySelectorAll(".menu-card");

    menuCards.forEach((card) => {
        card.addEventListener("click", () => {
            const itemsContainer = card.querySelector(".menu-items-container");

            // Close all other menu items
            menuCards.forEach((otherCard) => {
                const otherItemsContainer = otherCard.querySelector(".menu-items-container");
                if (otherItemsContainer !== itemsContainer) {
                    otherItemsContainer.style.display = "none";
                }
            });

            // Toggle visibility of the clicked card's menu items
            if (itemsContainer.style.display === "none" || !itemsContainer.style.display) {
                itemsContainer.style.display = "block";
            } else {
                itemsContainer.style.display = "none";
            }
        });
    });
});

</script>

<?php if ($isAdmin): ?>
<div class="admin-actions">
    <!-- Add New Category -->
    <div style="margin-right: 20px; flex: 1;">
        <h2 style="font-weight: bold;">Add New Category</h2>
        <form action="menu.php" method="POST" enctype="multipart/form-data" style="display: flex; flex-direction: column;">
            <label for="new_category">Category Name:</label>
            <input type="text" id="new_category" name="new_category" required><br>

            <label for="category_image">Category Image:</label>
            <input type="file" id="category_image" name="category_image" accept="image/*" required><br>
            <img id="category_image_preview" style="display:none; width:100px;"/><br>
            <button type="submit" name="add_category">Add Category</button>
        </form>
    </div>
    <!-- Add New Item -->
    <div style="flex: 1;">
        <h2 style="font-weight: bold;">Add New Item</h2>
        <form action="menu.php" method="POST" enctype="multipart/form-data" style="display: flex; flex-direction: column;">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required><br>

            <label for="category">Category:</label>
            <select id="category" name="category" required>
                <option value="">-- Select Category --</option>
                <?php
                $categoriesQuery = "SELECT DISTINCT category FROM menus WHERE category IS NOT NULL";
                $categoriesResult = $conn->query($categoriesQuery);
                while ($row = $categoriesResult->fetch_assoc()):
                ?>
                    <option value="<?= htmlspecialchars($row['category']) ?>"><?= htmlspecialchars(ucfirst($row['category'])) ?></option>
                <?php endwhile; ?>
            </select><br>

            <label for="price">Price:</label>
            <input type="number" step="0.01" id="price" name="price" required><br>

            <label for="description">Description:</label>
            <textarea id="description" name="description" required></textarea><br>

            <label for="image">Image:</label>
            <input type="file" id="image" name="image" accept="image/*" required><br>

            <button type="submit" name="add_item">Add Item</button>
        </form>
    </div>
</div>
<?php endif; ?>
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

</body>
</html>