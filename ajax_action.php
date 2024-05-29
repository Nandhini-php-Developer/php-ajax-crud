<?php 
include "config.php";

$uid = $_POST["uid"];
$name = mysqli_real_escape_string($con, $_POST["name"]);
$email = mysqli_real_escape_string($con, $_POST["email"]);
$mobile = mysqli_real_escape_string($con, $_POST["mobile"]);

$image_path = "";

if (isset($_FILES["fileToUpload"]) && $_FILES["fileToUpload"]["error"] == 0) {
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $uploadOk = 1;

    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }

    // Check file size (limit to 2MB)
    if ($_FILES["fileToUpload"]["size"] > 2000000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            $image_path = $target_file;
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
} else {
    echo "No file uploaded or there was an error during upload.";
}

if($uid == "0"){
    $sql = "INSERT INTO user (NAME, EMAIL, MOBILE, IMAGE) VALUES ('{$name}', '{$email}', '{$mobile}', '{$image_path}')";
    if($con->query($sql)){
        $uid = $con->insert_id;
        echo "<tr class='{$uid}'>
            <td>{$name}</td>
            <td>{$email}</td>
            <td>{$mobile}</td>
            <td><img src='{$image_path}' width='50' height='50'></td>
            <td><a href='#' class='btn btn-primary edit' uid='{$uid}'>Edit</a></td>
            <td><a href='#' class='btn btn-danger del' uid='{$uid}'>Delete</a></td>
        </tr>";
    }
} else {
    if (!empty($image_path)) {
        $sql = "SELECT IMAGE FROM user WHERE UID='{$uid}'";
        $result = $con->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (file_exists($row['IMAGE'])) {
                unlink($row['IMAGE']);
            }
        }
    }

    $sql = "UPDATE user SET NAME='{$name}', EMAIL='{$email}', MOBILE='{$mobile}', IMAGE='{$image_path}' WHERE UID='{$uid}'";
    if($con->query($sql)){
        echo "
            <td>{$name}</td>
            <td>{$email}</td>
            <td>{$mobile}</td>
            <td><img src='{$image_path}' width='50' height='50'></td>
            <td><a href='#' class='btn btn-primary edit' uid='{$uid}'>Edit</a></td>
            <td><a href='#' class='btn btn-danger del' uid='{$uid}'>Delete</a></td>
        ";
    }
}
?>
