<?php
$item = null;

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        $pdo = new PDO('sqlite:../lib/items.db');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare("SELECT * FROM items WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $item = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Database error: " . $e->getMessage();
        exit;
    }
}

// Set page title and back link based on item type
$page_title = "Item Details";
$back_link = "../index.php";
$back_text = "Home";

if ($item) {
    if ($item['type'] === 'lost') {
        $page_title = "Lost Item Details";
        $back_link = "lost.php";
        $back_text = "Back to Lost Items";
    } else if ($item['type'] === 'found') {
        $page_title = "Found Item Details";
        $back_link = "found.php";
        $back_text = "Back to Found Items";
    }
}

// Build item content HTML
$item_content = '';

if ($item) {
    $item_content .= '<div class="detail-container">';
    $item_content .= '<img src="../assets/images/useruploads/' . htmlspecialchars($item['image']) . '" alt="' . htmlspecialchars($item['title']) . '" />';
    $item_content .= '<p><strong>' . htmlspecialchars($item['title']) . '</strong></p>';
    $item_content .= '<p>' . htmlspecialchars($item['description']) . '</p>';
    $item_content .= '<p><em>Status:</em> <span class="status ' . htmlspecialchars($item['type']) . '">' . ucfirst(htmlspecialchars($item['type'])) . '</span></p>';
    $item_content .= '<p><em>Location:</em> ' . htmlspecialchars($item['location']) . '</p>';
    $item_content .= '<p><em>Contact:</em> <a href="mailto:' . htmlspecialchars($item['contact']) . '">' . htmlspecialchars($item['contact']) . '</a></p>';

    $item_content .= '<hr>';
    $item_content .= '<form action="../assets/php/delete_item.php" method="POST" enctype="multipart/form-data" >';
    $item_content .= '<input type="hidden" name="id" value="' . htmlspecialchars($item['id']) . '">';
    $item_content .= '<label><strong>Item claimed? Enter pin:</strong> ';
    $item_content .= '<br>';
    $item_content .= '<input type="password" name="pin" maxlength="9" pattern="\\d{1,9}" required placeholder="Max 9-digit PIN">';
    $item_content .= '<br>';
    $item_content .= '</label>';

    $item_content .= '<br><button type="submit" class="delete-btn">Item Found!</button>';
    $item_content .= '</form>';

    $item_content .= '</div>';
} else {
    $item_content .= '<div class="error-container">';
    $item_content .= '<h2>Item not found</h2>';
    $item_content .= '<p>The item you are looking for does not exist.</p>';
    $item_content .= '<a href="../index.php" class="view-all-btn">Back to Home</a>';
    $item_content .= '</div>';
}

// Load template
$template = file_get_contents('../templates/detail.html');

// Replace placeholders
$template = str_replace('<!-- PAGE_TITLE -->', htmlspecialchars($page_title), $template);
$template = str_replace('<!-- BACK_LINK -->', htmlspecialchars($back_link), $template);
$template = str_replace('<!-- BACK_TEXT -->', htmlspecialchars($back_text), $template);
$template = str_replace('<!-- CSS_INCLUDE -->', '', $template);
$template = str_replace('<!-- ITEM_CONTENT -->', $item_content, $template);

// Output the final HTML
echo $template;
?>
