<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" href="img/lunchIcon.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="register.css">
        <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css"
      integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />
</head>
<body>
    <div class="container">
        <div class="form_container">
            <div class="logo" onclick="window.location.href='index.php'">
                <img src="img/lunchIcon.png" alt="" />
                <h1>Flavor Haven</h1>
            </div>
            <h2>Create an Account</h2>
            <h4>Fill in the fields below to register:</h4>
            <?php

//             require_once 'database.php';

// session_start();

// $error = ''; // Initialize error message variable

// // Insert admin user (this should likely be done separately)
// $hashedPassword = password_hash('12345678', PASSWORD_DEFAULT);
// $sqll = "INSERT INTO login (First, Last, Email, Phone, Password, Role) 
//          VALUES ('Lujain', 'Baher', 'lujainzakareya@gmail.com', '01124447205', '$hashedPassword', 'admin')";
// if (mysqli_query($conn, $sqll)) {
//     echo "Admin user inserted successfully.";
// } else {
//     echo "Error inserting admin user: " . mysqli_error($conn);
// }
            
if (isset($_POST['submit'])) {
    // Fetch form data
    $firstName = $_POST['firstname'];
    $lastName = $_POST['lastname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    // Initialize error array
    $errors = array();

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        array_push($errors, "<span style='color: red;'>Email is not valid.</span>");
    }

    // Validate password confirmation
    if ($confirm !== $password) {
        array_push($errors, "<span style='color: red;'>Passwords do not match.</span>");
    }

    // Display errors if any
    if (count($errors) > 0) {
        foreach ($errors as $error) {
            echo "<div class='alert alert-danger'>$error</div>";
        }
    } else {
        // Include database connection
        require_once 'database.php';

        // Check if email or phone already exists
        $checkQuery = "SELECT * FROM login WHERE Email = ? OR Phone = ?";
        $stmt = mysqli_stmt_init($conn);

        if (mysqli_stmt_prepare($stmt, $checkQuery)) {
            // Bind email and phone parameters
            mysqli_stmt_bind_param($stmt, "ss", $email, $phone);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($result) > 0) {
                // Email or phone already exists
                echo "<div class='alert alert-danger'><span style='color: red;'>An account with this email or phone number already exists.</div>";
            } else {
                 // Hash the password before saving it to the database
                 
                // Proceed with inserting new user data
                $sql = "INSERT INTO login (First, Last, Email, Phone, Password) VALUES (?, ?, ?, ?, ?)";
                if (mysqli_stmt_prepare($stmt, $sql)) {
                    // Bind parameters for insertion (all are strings)
                    mysqli_stmt_bind_param($stmt, "sssss", $firstName, $lastName, $email, $phone,  $hashedPassword);

                    // Execute the query
                    if (mysqli_stmt_execute($stmt)) {
                        echo "<div class='alert alert-success'>Registered Successfully</div>";
                        // Redirect to Signin page after successful registration
                        header("Location: Signin.php"); // Redirect after successful registration
                        exit();
                    } else {
                        echo "<div class='alert alert-danger'>Error executing query: " . mysqli_error($conn) . "</div>";
                    }
                } else {
                    echo "<div class='alert alert-danger'>Error preparing the insert statement: " . mysqli_error($conn) . "</div>";
                }
            }

            // Close the statement
            mysqli_stmt_close($stmt);
        } else {
            echo "<div class='alert alert-danger'>Error preparing the check statement: " . mysqli_error($conn) . "</div>";
        }
    }
}
?>

<form action="#" id="register-form" method="post">
            <label for="firstname">First Name</label>
                <input type="text" id="firstname" name="firstname" placeholder="Enter your First Name" required >
                <label for="lastname">Last Name</label>
                <input type="text" id="lastname" name="lastname" placeholder="Enter your Last Name" required>

                <label for="email">Email</label>
                <input type="email" id= 'email' name="email" placeholder="Enter your Email" required>

                <label for="Phone">Phone Number</label>
                <input type="tel" id= 'Phone' name="phone" placeholder="Enter your phone number"  pattern="[0-9]{11}" required>

                <div class="show_password">
                                <!-- Password Field -->
                                <label for="password">Password</label>
                                <input type="password" id="password" name="password" placeholder="Enter your Password" minlength="8" required>
                                <label for="show_password" class="checkbox-container">
                                <input type="checkbox" id="show_password" class="checkbox-input">
                                <span class="checkbox-label">Show Password</span>
                                </label>
                </div>
                
                <button type="submit" class="btn" name='submit'>Register</button>
            </form>
            <p>Already have an account? <a href="Signin.php">Login Here</a></p>
        </div>
    </div>
    <script src="register.js"></script>

</body>
</html>