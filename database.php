
<?php
$servername = "localhost"; // or your server's IP address
$username = "root"; // your database username
$password = ""; // your database password
$dbname = "project-copyf"; // replace with your database name


$conn = new mysqli($servername, $username, $password, $dbname);

// Check if the connection was successful
if ($conn->connect_error) {
    // If connection fails, display a more detailed error
    die("Connection failed: " . mysqli_connect_error());
} 
mysqli_set_charset($conn, "utf8");
?>

