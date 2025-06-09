<?php
try {
    // Connect to the SQLite database in parent folder's lib directory
    $pdo = new PDO('sqlite:../lib/items.db');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch only 'found' items
    $searchTerm = $_GET['search'] ?? '';
    if (!empty($searchTerm)) {
        $like = '%' . $searchTerm . '%';
        $stmt_found = $pdo->prepare("SELECT * FROM items WHERE type = 'found' AND (title LIKE :search OR description LIKE :search) ORDER BY id DESC");
        $stmt_found->execute([':search' => $like]);
    } else {
        $stmt_found = $pdo->prepare("SELECT * FROM items WHERE type = 'found' ORDER BY id DESC");
        $stmt_found->execute();
    }
  $items = $stmt_found->fetchAll(PDO::FETCH_ASSOC);

    // Build content HTML
    $items_html = '';

    foreach ($items as $item) {
        $items_html .= '<div class="item-card" onclick="window.location.href=\'detail.php?id=' . htmlspecialchars($item['id']) . '\'">';
        $items_html .= '<img src="../assets/images/useruploads/' . htmlspecialchars($item['image']) . '" alt="' . htmlspecialchars($item['title']) . '" />';
        $items_html .= '<p><strong>' . htmlspecialchars($item['title']) . '</strong></p>';
        $items_html .= '<p>' . htmlspecialchars($item['description']) . '</p>';
        $items_html .= '<p><em>Location:</em> ' . htmlspecialchars($item['location']) . '</p>';
        $items_html .= '<p><em>Contact:</em> <a href="mailto:' . htmlspecialchars($item['contact']) . '">' . htmlspecialchars($item['contact']) . '</a></p>';
        $items_html .= '</div>';
    }

    // Load the unified template
    $template = file_get_contents('../templates/LostFound.html');

    // Add reset button if there's a search
    $resetButton = '';
    if (!empty($searchTerm)) {
        $resetButton = '<a href="found.php" class="reset-btn">Clear</a>';
    }

    $search_form_html = '
    <form method="GET" action="found.php">
      <input type="text" name="search" placeholder="Search found items..." value="' . htmlspecialchars($searchTerm) . '" />
      <button type="submit">Search</button>
      ' . $resetButton . '
    </form>';

    // Replace placeholders with content
    $template = str_replace('<!-- PAGE_TITLE -->', 'Found Items', $template);
    $template = str_replace('<!-- SEARCH_FORM -->', $search_form_html, $template);
    $template = str_replace('<!-- PAGE_HEADING -->', '🎉 Found Items', $template);
    $template = str_replace('<!-- PAGE_SUBTITLE -->', 'Items that have been found and are waiting to be claimed. Check if any of these belong to you or someone you know.', $template);
    $template = str_replace('<!-- LOST_ACTIVE -->', '', $template);
    $template = str_replace('<!-- FOUND_ACTIVE -->', 'active', $template);
    $template = str_replace('<!-- ITEMS_CONTENT -->', $items_html, $template);

    // Output the final HTML
    echo $template;

} catch (PDOException $e) {
    http_response_code(500);
    echo "Database error: " . $e->getMessage();
}
?>
