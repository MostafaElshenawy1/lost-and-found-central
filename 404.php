<?php
http_response_code(404);

// Load the template
$template = file_get_contents('templates/LostFound.html');

// Replace placeholders with 404 content
$template = str_replace('<!-- PAGE_TITLE -->', '404 - Not Found', $template);
$template = str_replace('<!-- SEARCH_FORM -->', '', $template);
$template = str_replace('<!-- PAGE_HEADING -->', '🚫 Page Not Found', $template);
$template = str_replace('<!-- PAGE_SUBTITLE -->', 'The page you are looking for does not exist or has been moved.', $template);
$template = str_replace('<!-- LOST_ACTIVE -->', '', $template);
$template = str_replace('<!-- FOUND_ACTIVE -->', '', $template);

$not_found_html = '
<div class="error-container">
  <h2>404 - Not Found</h2>
  <p>We couldn’t find the page you requested.</p>
  <a href="../index.php" class="view-all-btn">Back to Home</a>
</div>';

$template = str_replace('<!-- ITEMS_CONTENT -->', $not_found_html, $template);

echo $template;
?>
