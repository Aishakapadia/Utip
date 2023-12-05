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

$table_search = $_POST['search'];


// Query to fetch all fields from the "users" table
$query = "SELECT * FROM $table_search";

// Execute the query
$result = $conn->query($query);

if ($result->num_rows > 0) {
    // Loop through the result set and display the fields
    while ($row = $result->fetch_assoc()) {
        foreach ($row as $field => $value) {
            echo $field . ": " . $value . "<br>";
        }
        echo "<br>";
    }
} else {
    echo "No data found in the table.";
}

// Close the connection
$conn->close();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form method="POST" enctype="multipart/form-data">
        <input type="text" name="search" placeholder="table name">
        
        <input type="submit" value="Submit">
    </form>
</body>
</html>
