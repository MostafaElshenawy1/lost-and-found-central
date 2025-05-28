<?php
// Load items from combined JSON file and filter for found items
$json = file_get_contents('../lib/items.json');
$all_items = json_decode($json, true);
$items = array_filter($all_items, function($item) {
    return $item['type'] === 'found';
});
// Reindex array after filtering
$items = array_values($items);

// Build content HTML
$items_html = '';

if (count($items) > 0) {
    foreach ($items as $item) {
        $items_html .= '<div class="slide" onclick="window.location.href=\'detail.php?id=' . htmlspecialchars($item['id']) . '\'">';
        $items_html .= '<img src="../assets/images/' . htmlspecialchars($item['image']) . '" alt="' . htmlspecialchars($item['title']) . '" />';
        $items_html .= '<p><strong>' . htmlspecialchars($item['title']) . '</strong></p>';
        $items_html .= '<p>' . htmlspecialchars($item['description']) . '</p>';
        $items_html .= '<p><em>Location:</em> ' . htmlspecialchars($item['location']) . '</p>';
        $items_html .= '<p><em>Contact:</em> <a href="mailto:' . htmlspecialchars($item['contact']) . '">' . htmlspecialchars($item['contact']) . '</a></p>';
        $items_html .= '</div>';
    }
} else {
    $items_html = '<div style="text-align:center;width:100%;padding:2rem;"><p>No found items found.</p></div>';
}

// Load the template
$template = file_get_contents('../templates/found.html');

// Replace placeholder with content
$template = str_replace('<!-- FOUND_ITEMS_CONTENT -->', $items_html, $template);

// Output the final HTML
echo $template;
?>
