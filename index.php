<?php
$con = mysqli_connect('localhost', 'root', '', 'registration_form');
if (!$con) {
    die("Failed to connect to database: " . mysqli_connect_error());
}

if (isset($_POST['upload'])) {
  
    $folder = 'uploads/';
    if (!is_dir($folder)) {
        mkdir($folder, 0777, true);
    }

    
    if (isset($_FILES['image'])) {
        $fileCount = count($_FILES['image']['name']); 
        for ($i = 0; $i < $fileCount; $i++) {
            $filename = $_FILES['image']['name'][$i];
            $tmpname = $_FILES['image']['tmp_name'][$i];
            $filepath = $filename;

            if (move_uploaded_file($tmpname, $filepath)) {
                $sql = "INSERT INTO data_form (imagepath) VALUES ('$filepath')";
                $query = mysqli_query($con, $sql);

                if ($query) {
                    echo "<br>Image uploaded to the database as URL: " . $filepath;
                } else {
                    echo "Error: " . mysqli_error($con);
                }
            } else {
                echo "Error uploading the file: " . $filename;
            }
        }

        echo "<br>";
    } else {
        echo "No files were uploaded.";
    }
}

$sql = "SELECT * FROM data_form";
$result = mysqli_query($con, $sql);
$images = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Image store</title>
</head>
<body>
    <form action="" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="image">Profile Image:</label>
            <input type="file" name="image[]" id="image" multiple>
        </div>
        <button type="submit" class="btn btn-primary" name="upload">Upload</button>
    </form>

    <?php
    if (!empty($images)) {
        foreach ($images as $image) {
            echo "<img width='300px' height='300px' src='{$image["imagepath"]}' alt='img'>";
            echo "<br><hr>";
        }
    } else {
        echo "No images stored.";
    }
    ?>

</body>
</html>
