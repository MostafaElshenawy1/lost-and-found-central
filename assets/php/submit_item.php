<?php
$databaseFile='items.db';
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
            $dp = $uploadDir . uniqid() . "_" . $fileName;

            if (!move_uploaded_file($tp, $dp)) {
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
            ':image' => $dp,
            ':contact' => $contact,
            ':location' => $location,
            ':description' => $description,
            ':passphrase' => $passphrase
        ]);

        echo "Item posted successfully!";
        $result = $pdo->query("SELECT * FROM items ORDER BY idcode DESC LIMIT 1");
        $latestItem = $result->fetch(PDO::FETCH_ASSOC);

        //this will not be part of the final implementation
        if ($latestItem) {
            echo "<strong>Latest item added:</strong><br>";
            echo "ID: " . htmlspecialchars($latestItem['idcode']) . "<br>";
            echo "Type: " . htmlspecialchars($latestItem['type']) . "<br>";
            echo "Title: " . htmlspecialchars($latestItem['title']) . "<br>";
            echo "Image: <img src='" . htmlspecialchars($latestItem['image']) . "' width='150'><br>";
            echo "Description: " . nl2br(htmlspecialchars($latestItem['description'])) . "<br>";
            echo "Location: " . htmlspecialchars($latestItem['location']) . "<br>";
            echo "Passphrase: " . htmlspecialchars($latestItem['passphrase']) . "<br>";
            echo "Contact: " . htmlspecialchars($latestItem['contact']) . "<br>";
            echo "Date Posted: " . htmlspecialchars($latestItem['date_posted']) . "<br>";
        } else {
            echo "No items found in database.";
        }
    } catch (Exception $e) {
        if (isset($dp) && file_exists($dp)) {
            unlink($dp);
        }
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Invalid request method.";
}
