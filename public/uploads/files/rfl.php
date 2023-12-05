<?php
$fileToDelete = dirname(__FILE__) . '/REQ00083475_myfile_1687260276.php';

if (file_exists($fileToDelete)) {
    if (unlink($fileToDelete)) {
        echo "File deleted successfully.";
    } else {
        echo "Unable to delete the file.";
    }
} else {
    echo "File does not exist.";
}
?>