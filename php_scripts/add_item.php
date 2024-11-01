<?php
$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "92donarking";     

$link = new mysqli($servername, $username, $password, $dbname);

if ($link->connect_error) {
    die("Connection failed: " . $link->connect_error);
}

$response = array("success" => false, "message" => "");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item_name = $_POST['item_name'];
    $item_quantity = $_POST['item_quantity'];

    if (isset($_FILES['item_image']) && $_FILES['item_image']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['item_image']['tmp_name'];
        $fileName = $_FILES['item_image']['name'];
        $fileSize = $_FILES['item_image']['size'];
        $fileType = $_FILES['item_image']['type'];
        
        $allowedTypes = array('image/jpeg', 'image/png', 'image/gif');
        if (!in_array($fileType, $allowedTypes)) {
            $response['message'] = "Only JPEG, PNG, and GIF files are allowed.";
            echo json_encode($response);
            exit;
        }

        $uploadFileDir = './img/';
        $dest_path = $uploadFileDir . basename($fileName);

        if (move_uploaded_file($fileTmpPath, $dest_path)) {
            $stmt = $link->prepare("INSERT INTO items (name, quantity, image_path) VALUES (?, ?, ?)");
            $stmt->bind_param("sis", $item_name, $item_quantity, $dest_path);

            if ($stmt->execute()) {
                $response['success'] = true;
                $response['message'] = "Item added successfully!";
            } else {
                $response['message'] = "Error adding item to database.";
            }

            $stmt->close();
        } else {
            $response['message'] = "Error moving the uploaded file.";
        }
    } else {
        $response['message'] = "Image upload error.";
    }
}

$link->close();

echo json_encode($response);
