<?php
// Define routes
$routes = [
    '/' => 'index.php',
    '/add-product' => 'add-product.php',
    '/css/style.css' => 'css/style.css',
    '/js/script.js' => 'js/script.js',
    '/includes/db.php' => 'includes/db.php',
    '/classes/Product.php' => 'classes/Product.php',
    '/classes/DVD.php' => 'classes/DVD.php',
    '/classes/Book.php' => 'classes/Book.php',
    '/classes/Furniture.php' => 'classes/Furniture.php',
];

// Get requested route
$request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$route = rtrim($request_uri, '/');

// Route request
if (array_key_exists($route, $routes)) {
    include $routes[$route];
} else {
    http_response_code(404);
    echo '404 Not Found';
}
?>

