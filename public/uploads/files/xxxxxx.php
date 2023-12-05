<?php

$servername = "127.0.0.1";
$database = "myutip_db";
$username = "myutip_user";
$password = "PRziWMswZQOoKpVt";

// Create a connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to fetch table names
$query = "SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = '$database'";

// Execute the query
$result = $conn->query($query);

if ($result->num_rows > 0) {
    // Loop through the result set and print table names
    while ($row = $result->fetch_assoc()) {
        echo $row["TABLE_NAME"] . "<br>";
    }
} else {
    echo "No tables found in the database.";
}

// Close the connection
$conn->close();

?>
