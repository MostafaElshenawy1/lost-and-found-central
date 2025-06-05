<?php
$databaseFile = __DIR__.'/../items.db';
//this makes a db that is full of sample items from a json for testing purposes
//normally, we would set up with the other script because none of these items were actually lost or found by real people
if (file_exists($databaseFile)) {
    if (unlink($databaseFile)) {
        echo "Existing database 'items.db' deleted.<br>";
    } else {
        echo "Failed to delete existing database.<br>";
    }
}

try {
    //making a new database also deletes any leftover images made by users
    $imagesDir = __DIR__ . '/../../assets/images/useruploads';
    if (is_dir($imagesDir)) {
        $files = glob($imagesDir . '/*');
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file); // delete file
            }
        }
        echo "All files in assets/images/useruploads deleted.<br>";
    } else {
        echo "Directory assets/images/useruploads does not exist.<br>";
    }
    
    $sourceDir = '../../assets/images/';
    $targetDir = '../../assets/images/useruploads/';
    $imageName = 'image.jpg';
    $sourcePath = $sourceDir . $imageName;
    $targetPath = $targetDir . $imageName;
    if (file_exists($sourcePath)) {
        if (copy($sourcePath, $targetPath)) {
            echo "Image copied successfully from $sourcePath to $targetPath";
        } else {
            echo "Failed to copy image.";
        }
    } else {
        echo "Source image does not exist.";
    }

    $pdo = new PDO('sqlite:' . $databaseFile);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //im making this db using a similar strategy to lab 5
    $qry = "
    CREATE TABLE IF NOT EXISTS items (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        type VARCHAR(10) NOT NULL,
        title VARCHAR(100) NOT NULL,
        image VARCHAR(255) NOT NULL,
        contact VARCHAR(150) NOT NULL,
        location VARCHAR(250),
        description VARCHAR(350),
        passphrase VARCHAR(100),
        date_posted DATETIME DEFAULT CURRENT_TIMESTAMP
        );
    ";
    $pdo->exec($qry);
    echo "db was created succesfully!<br>";

    if (!file_exists('../items.json')) {
        echo "Error: JSON file not found";
        exit;
    }

    $itemsJSON = file_get_contents('../items.json');
    $itemsD = json_decode($itemsJSON, true);

    
    $insertQry = "INSERT INTO items (type, title, image, contact, location, description, passphrase, date_posted ) VALUES (:type, :title, :image, :contact, :location, :description, :passphrase, :date_posted )";
    $st = $pdo->prepare($insertQry);

    foreach ($itemsD as $item) {
        
        $type = $item['type'];
        $title = $item['title'];
        $image = $item['image'];
        $contact = $item['contact'];
        $location = $item['location'];
        $description = $item['description'];
        $passphrase = $item['passphrase'];
        $date_posted = $item['date_posted'];
        
        $st->bindParam(':type', $type);
        $st->bindParam(':title', $title);
        $st->bindParam(':image', $image);
        $st->bindParam(':contact', $contact);
        $st->bindParam(':location', $location);
        $st->bindParam(':description', $description);
        $st->bindParam(':passphrase', $passphrase);
        $st->bindParam(':date_posted', $date_posted);

        $st->execute();

       echo "Inserted item: \"$title\"<br>";
    }
    $testQ = $pdo->query("SELECT * FROM items");
    $r = $testQ->fetchAll(PDO::FETCH_ASSOC);
    echo "<br>Result of test:<br>";
    var_dump($r); 
}
catch (PDOException $e){
    echo "error ".$e->getMessage();
}
