<?php

$databaseFile='../../lib/items.db';
$uploadDir='../images/useruploads/';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //check inputs
    $id = $_POST['id'] ?? null;
    $ipp = $_POST['passphrase'] ?? ''; //passpin can be blank

    if (!$id) {
        echo "Invalid request: missing item ID.";
        exit;
    }
    try{
        $pdo = new PDO('sqlite:' . $databaseFile);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        //look for the needed item
        $st = $pdo->prepare("SELECT passphrase FROM items WHERE id = :id");
        $st->execute([':id' => $id]);
        $item = $st->fetch(PDO::FETCH_ASSOC);
        //if the item no longer exists or not found:
        if (!$item) {
            echo "Item not found.";
            exit;
        }
        //if the pin is wrong, alert the user and send them home
        if ($item['passphrase'] !== $ipp) {
            echo "<script>
            alert('Incorrect pin. Item was not deleted.');
            window.location.href = '../index.php';
            </script>";
            exit;
        }
        //this only runs if pin is correct
        $delst = $pdo->prepare("DELETE FROM items WHERE id = :id");
        $delst->execute([':id' => $id]);
        header('Location: ../../index.php');
        exit;
    }
    catch (PDOException $e) {
        echo "Database error: " . $e->getMessage();
    }
}
else {
    //if not post method
    echo "Invalid request method.";
}
