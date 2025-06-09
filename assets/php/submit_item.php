<?php

$databaseFile='../../lib/items.db';
$uploadDir='../images/useruploads/';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo = new PDO('sqlite:' . $databaseFile);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        //these should usually not be null but just in case...
        $type = $_POST['type'] ?? 'unknown';
        $title = $_POST['title'] ?? '';
        $description = $_POST['description'] ?? '';
        $passphrase = $_POST['passphrase'] ?? '';
        $location = $_POST['location'] ?? '';
        $contact = $_POST['contact'] ?? '';

        //deals with the uploaded image, giving it a unique name and placing it in correct dir
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $tp = $_FILES['image']['tmp_name'];
            $fileName = basename($_FILES['image']['name']);
            $uniqueFileName = uniqid() . "_" . $fileName;
            $fullPath = $uploadDir . $uniqueFileName;

            if (!move_uploaded_file($tp, $fullPath)) {
                throw new Exception('Failed to move uploaded image.');
            }
        } else {
            throw new Exception('Image upload error.');
        }

        // insert the item to db
        $stmt = $pdo->prepare("
            INSERT INTO items (type, title, image, contact, location, description, passphrase)
            VALUES (:type, :title, :image, :contact, :location, :description, :passphrase)
        ");
        $stmt->execute([
            ':type' => $type,
            ':title' => $title,
            ':image' => $uniqueFileName,
            ':contact' => $contact,
            ':location' => $location,
            ':description' => $description,
            ':passphrase' => $passphrase
        ]);
/* // THIS IS FOR DEBUG
        echo "Item posted successfully!";
        $result = $pdo->query("SELECT * FROM items ORDER BY id DESC LIMIT 1");
        $latestItem = $result->fetch(PDO::FETCH_ASSOC);

        if ($latestItem) {
            echo "<strong>Latest item added:</strong><br>";
            echo "ID: " . htmlspecialchars($latestItem['id']) . "<br>";
            echo "Type: " . htmlspecialchars($latestItem['type']) . "<br>";
            echo "Title: " . htmlspecialchars($latestItem['title']) . "<br>";
            $imagePath = '../images/useruploads/' . $latestItem['image'];
            echo "Image: <img src='" . htmlspecialchars($imagePath) . "' width='150'><br>";

            echo "Description: " . nl2br(htmlspecialchars($latestItem['description'])) . "<br>";
            echo "Location: " . htmlspecialchars($latestItem['location']) . "<br>";
            echo "Passphrase: " . htmlspecialchars($latestItem['passphrase']) . "<br>";
            echo "Contact: " . htmlspecialchars($latestItem['contact']) . "<br>";
            echo "Date Posted: " . htmlspecialchars($latestItem['date_posted']) . "<br>";
        } else {
            echo "No items found in database.";
        }
            */

        header('Location: ../../index.php');
        exit;
    } catch (Exception $e) {
        if (isset($fullPath) && file_exists($fullPath)) {
            unlink($fullPath);
        }
        http_response_code(500);
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Invalid request method.";
}
