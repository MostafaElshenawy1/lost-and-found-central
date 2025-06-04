<?php
try {
  $page = $_GET['page'] ?? 'home';
  // Redirect to main page
  header('Location: pages/home.php');
  exit;
} catch (Exception $e) {
  http_response_code(500);
}
?>