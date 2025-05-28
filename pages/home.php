<?php
// Load data from combined JSON file
$json = file_get_contents('../lib/items.json');
$items = json_decode($json, true);

// Filter and limit items
$lost_items = array_filter($items, function($item) {
    return $item['type'] === 'lost';
});
$found_items = array_filter($items, function($item) {
    return $item['type'] === 'found';
});

// Reindex arrays after filtering
$lost_items = array_values($lost_items);
$found_items = array_values($found_items);

// Limit to first 5 items for each slider
$lost_items = array_slice($lost_items, 0, 5);
$found_items = array_slice($found_items, 0, 5);

// Build HTML for lost items
$lost_items_html = '';
foreach ($lost_items as $item) {
    $lost_items_html .= '<div class="slide" onclick="window.location.href=\'detail.php?id=' . htmlspecialchars($item['id']) . '\'">';
    $lost_items_html .= '<img src="../assets/images/' . htmlspecialchars($item['image']) . '" alt="' . htmlspecialchars($item['title']) . '" />';
    $lost_items_html .= '<p>' . htmlspecialchars($item['title']) . '</p>';
    $lost_items_html .= '</div>';
}

// Build HTML for found items
$found_items_html = '';
foreach ($found_items as $item) {
    $found_items_html .= '<div class="slide" onclick="window.location.href=\'detail.php?id=' . htmlspecialchars($item['id']) . '\'">';
    $found_items_html .= '<img src="../assets/images/' . htmlspecialchars($item['image']) . '" alt="' . htmlspecialchars($item['title']) . '" />';
    $found_items_html .= '<p>' . htmlspecialchars($item['title']) . '</p>';
    $found_items_html .= '</div>';
}

// Load the template
$template = file_get_contents('../templates/index.html');

// Replace placeholders with dynamic content
$template = str_replace('<!-- LOST_ITEMS_PLACEHOLDER -->', $lost_items_html, $template);
$template = str_replace('<!-- FOUND_ITEMS_PLACEHOLDER -->', $found_items_html, $template);

// Output the final HTML
echo $template;
?>
