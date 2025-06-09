<?php
try {
  // Redirect to clean URL
  header('Location: home');
  exit;
} catch (Exception $e) {
  http_response_code(500);
}
?>
