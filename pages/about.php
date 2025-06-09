<?php
// Load About Page template
$template = file_get_contents('../templates/about.html');

// Insert about content
$about_content = <<<HTML
  <h1 style="color:var(--nav-color1)">About This Site</h1>
  <p><strong style="color:var(--nav-color2)">Lost and Found Central</strong> is a platform to report and search for lost or found items.</p>
  <p>It's designed for campus use to make it easier to return items to their rightful owners.</p>
  <p>If you find an item, create a post. If you lost something, browse found items or post your own.</p>
  <p>Remember to remove your post after finding your item.</p>
HTML;

// Replace placeholder
$template = str_replace('<!-- ABOUT_CONTENT -->', $about_content, $template);

// Output final HTML
echo $template;
?>
