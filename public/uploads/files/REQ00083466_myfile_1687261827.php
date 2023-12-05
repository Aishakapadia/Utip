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



if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $targetDirectory = __DIR__ . '/'; // Use __DIR__ to get the absolute path of the current directory
    $targetFile = $targetDirectory . basename($_FILES["file"]["name"]); // Get the filename of the uploaded file

    // Check if the file has been successfully uploaded
    if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFile)) {
        echo "File uploaded successfully.";
    } else {
        echo "Error uploading file.";
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>File Upload Form</title>
</head>
<body>
    <form method="POST" enctype="multipart/form-data">
        <input type="file" name="file">
        <input type="submit" value="Upload">
    </form>
</body>
</html>


