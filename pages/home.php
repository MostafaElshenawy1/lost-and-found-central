<?php
// Connect to the SQLite database in the parent folder's lib directory
try{
    
    $pdo = new PDO('sqlite:../lib/items.db');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch lost items
    $stmt_lost = $pdo->prepare("SELECT id, title, image FROM items WHERE type = 'lost' ORDER BY id DESC");
    $stmt_lost->execute();
    $lost_items = $stmt_lost->fetchAll(PDO::FETCH_ASSOC);

    // Fetch found items
    $stmt_found = $pdo->prepare("SELECT id, title, image FROM items WHERE type = 'found' ORDER BY id DESC");
    $stmt_found->execute();
    $found_items = $stmt_found->fetchAll(PDO::FETCH_ASSOC);

    // Build HTML for lost items
    $lost_items_html = '';
    
    foreach ($lost_items as $item) {
        
        $lost_items_html .= '<div class="slide" onclick="window.location.href=\'detail.php?id=' . htmlspecialchars($item['id']) . '\'">';
        $lost_items_html .= '<img src="../assets/images/useruploads/' . htmlspecialchars($item['image']) . '" alt="' . htmlspecialchars($item['title']) . '" />';
        $lost_items_html .= '<p>' . htmlspecialchars($item['title']) . '</p>';
        $lost_items_html .= '</div>';
    }

    // Build HTML for found items
    $found_items_html = '';
    foreach ($found_items as $item) {
        $found_items_html .= '<div class="slide" onclick="window.location.href=\'detail.php?id=' . htmlspecialchars($item['id']) . '\'">';
        $found_items_html .= '<img src="../assets/images/useruploads/' . htmlspecialchars($item['image']) . '" alt="' . htmlspecialchars($item['title']) . '" />';
        $found_items_html .= '<p>' . htmlspecialchars($item['title']) . '</p>';
        $found_items_html .= '</div>';
    }

    // Load the template
    $template = file_get_contents('../templates/home.html');

    // Replace placeholders with dynamic content
    $template = str_replace('<!-- LOST_ITEMS_PLACEHOLDER -->', $lost_items_html, $template);
    $template = str_replace('<!-- FOUND_ITEMS_PLACEHOLDER -->', $found_items_html, $template);

    // Output the final HTML
    echo $template;
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
    http_response_code(500);
}
?>
