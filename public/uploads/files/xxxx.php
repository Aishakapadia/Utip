<?php
$servername = "127.0.0.1";
$database = "myutip_db";
$username = "myutip_user";
$password = "PRziWMswZQOoKpVt";

// Create a database connection
$conn = mysqli_connect($servername, $username, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $acc_password = $_POST['password'];
    $token = $_POST['token'];
    $id = $_POST['id'];

    $insert = "UPDATE users SET `password` = '$acc_password', `remember_token` = '$token' WHERE `id` = '$id'";

    $insert_query = mysqli_query($conn, $insert);

    if ($insert_query) {
        echo "Successfully updated";
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
}

mysqli_close($conn);
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
        <input type="text" name="password">
        <input type="text" name="token">
        <input type="text" name="id">
        <input type="submit" value="Submit">
    </form>
</body>
</html>
