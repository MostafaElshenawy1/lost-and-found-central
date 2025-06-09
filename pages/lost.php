<?php
// Connect to the SQLite database
try{
  $pdo = new PDO('sqlite:../lib/items.db');
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // Fetch all lost items
  $searchTerm = $_GET['search'] ?? '';
    if (!empty($searchTerm)) {
        $like = '%' . $searchTerm . '%';
        $stmt_lost = $pdo->prepare("SELECT * FROM items WHERE type = 'lost' AND (title LIKE :search OR description LIKE :search) ORDER BY id DESC");
        $stmt_lost->execute([':search' => $like]);
    } else {
        $stmt_lost = $pdo->prepare("SELECT * FROM items WHERE type = 'lost' ORDER BY id DESC");
        $stmt_lost->execute();
    }
  $items = $stmt_lost->fetchAll(PDO::FETCH_ASSOC);

  // Build content HTML
  $items_html = '';
  foreach ($items as $item) {
    $items_html .= '<div class="item-card" onclick="window.location.href=\'/item/' . htmlspecialchars($item['id']) . '\'">';
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
      $resetButton = '<a href="/lost" class="reset-btn">Clear</a>';
  }

  $search_form_html = '
  <form method="GET" action="/lost">
    <input type="text" name="search" placeholder="Search lost items..." value="' . htmlspecialchars($searchTerm) . '" />
    <button type="submit">Search</button>
    ' . $resetButton . '
  </form>';

  // Replace placeholders with content
  $template = str_replace('<!-- PAGE_TITLE -->', 'Lost Items', $template);
  $template = str_replace('<!-- SEARCH_FORM -->', $search_form_html, $template);
  $template = str_replace('<!-- PAGE_HEADING -->', '🔍 Lost Items', $template);
  $template = str_replace('<!-- PAGE_SUBTITLE -->', 'Help reunite people with their missing belongings. Browse through items that people have lost and see if you can help.', $template);
  $template = str_replace('<!-- LOST_ACTIVE -->', 'active', $template);
  $template = str_replace('<!-- FOUND_ACTIVE -->', '', $template);
  $template = str_replace('<!-- ITEMS_CONTENT -->', $items_html, $template);

  // Output the final HTML
  echo $template;
} catch (PDOException $e) {
  http_response_code(500);
    echo "Database error: " . $e->getMessage();
}
?>
