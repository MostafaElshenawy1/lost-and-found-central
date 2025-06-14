<?php
$databaseFile =__DIR__ . '/../items.db';

//this makes an empty database for the site.
//this script will be run once by the site owner to set up the db for all users
//the end user would NEVER have access to this script in reality (but I am leaving it here so you can see the code)
if (file_exists($databaseFile)) {
    if (unlink($databaseFile)) {
        echo "Existing database items.db deleted.<br>";
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
    $sourceDir = __DIR__ . '/../../assets/images/';
    $targetDir = __DIR__ . '/../../assets/images/useruploads/';
    $imageName = 'image.jpg';
    $sourcePath = $sourceDir . $imageName;
    $targetPath = $targetDir . $imageName;
    if (file_exists($sourcePath)) {
        if (copy($sourcePath, $targetPath)) {
            echo "Image copied successfully from $sourcePath to $targetPath<br>";
        } else {
            echo "Failed to copy image.<br>";
        }
    } else {
        echo "Source image does not exist.<br>";
    }
    $pdo = new PDO('sqlite:' . $databaseFile);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $pdo->exec("DROP TABLE IF EXISTS items");

    $qry = "
    CREATE TABLE items (
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

    echo "Database created and is empty now.<br>";

    $testQ = $pdo->query("SELECT * FROM items");
    $rows = $testQ->fetchAll(PDO::FETCH_ASSOC);
    echo "Current number of rows: " . count($rows) . "<br>";

} catch (PDOException $e) {
    echo "error " . $e->getMessage();
}
