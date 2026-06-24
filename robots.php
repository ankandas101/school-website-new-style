<?php
header('Content-Type: text/plain');

$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'];

echo "User-agent: *\n";
echo "Allow: /\n\n";

echo "Disallow: /admin/\n";
echo "Disallow: /includes/\n\n";

echo "Sitemap: {$protocol}://{$host}/sitemap.php";