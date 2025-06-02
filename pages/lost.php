<?php
// Connect to the SQLite database
try{
  $pdo = new PDO('sqlite:../lib/items.db');
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // Fetch all lost items
  $stmt = $pdo->prepare("SELECT id, title, image, description, contact, location FROM items WHERE type = 'lost' ORDER BY id DESC");
  $stmt->execute();
  $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

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

  // Replace placeholders with content
  $template = str_replace('<!-- PAGE_TITLE -->', 'Lost Items', $template);
  $template = str_replace('<!-- PAGE_HEADING -->', '🔍 Lost Items', $template);
  $template = str_replace('<!-- PAGE_SUBTITLE -->', 'Help reunite people with their missing belongings. Browse through items that people have lost and see if you can help.', $template);
  $template = str_replace('<!-- LOST_ACTIVE -->', 'active', $template);
  $template = str_replace('<!-- FOUND_ACTIVE -->', '', $template);
  $template = str_replace('<!-- ITEMS_CONTENT -->', $items_html, $template);

  // Output the final HTML
  echo $template;
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
}
?>
