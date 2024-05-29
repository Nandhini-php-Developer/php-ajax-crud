<?php 
include "config.php";

$uid = $_POST["id"];

if ($uid != "0") {
    // Step 1: Select the user record to get the image path
    $sql = "SELECT IMAGE FROM user WHERE UID='{$uid}'";
    $result = $con->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Step 2: Delete the image file if it exists
        if (file_exists($row['IMAGE'])) {
            unlink($row['IMAGE']);
        }
    }

    // Step 3: Delete the user record from the database
    $sql = "DELETE FROM user WHERE UID='{$uid}'";
    if ($con->query($sql)) {
        echo true;
    } else {
        echo false;
    }
}
?>
