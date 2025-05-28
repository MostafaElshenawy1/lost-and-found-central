<?php
// Load items from combined JSON file and filter for found items
$json = file_get_contents('../lib/items.json');
$all_items = json_decode($json, true);
$items = array_filter($all_items, function ($item) {
  return $item['type'] === 'found';
});
// Reindex array after filtering
$items = array_values($items);

// Build content HTML
$items_html = '';

foreach ($items as $item) {
  $items_html .= '<div class="item-card" onclick="window.location.href=\'detail.php?id=' . htmlspecialchars($item['id']) . '\'">';
  $items_html .= '<img src="../assets/images/' . htmlspecialchars($item['image']) . '" alt="' . htmlspecialchars($item['title']) . '" />';
  $items_html .= '<p><strong>' . htmlspecialchars($item['title']) . '</strong></p>';
  $items_html .= '<p>' . htmlspecialchars($item['description']) . '</p>';
  $items_html .= '<p><em>Location:</em> ' . htmlspecialchars($item['location']) . '</p>';
  $items_html .= '<p><em>Contact:</em> <a href="mailto:' . htmlspecialchars($item['contact']) . '">' . htmlspecialchars($item['contact']) . '</a></p>';
  $items_html .= '</div>';
}

// Load the unified template
$template = file_get_contents('../templates/LostFound.html');

// Replace placeholders with content
$template = str_replace('<!-- PAGE_TITLE -->', 'Found Items', $template);
$template = str_replace('<!-- PAGE_HEADING -->', '🎉 Found Items', $template);
$template = str_replace('<!-- PAGE_SUBTITLE -->', 'Items that have been found and are waiting to be claimed. Check if any of these belong to you or someone you know.', $template);
$template = str_replace('<!-- LOST_ACTIVE -->', '', $template);
$template = str_replace('<!-- FOUND_ACTIVE -->', 'active', $template);
$template = str_replace('<!-- ITEMS_CONTENT -->', $items_html, $template);

// Output the final HTML
echo $template;
?>

