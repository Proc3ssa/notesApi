<?php
// 🌐 GLOBAL HEADERS — Allow CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Content-Type: application/json");

// ⚙️ Preflight check for OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// 🧭 Parse the route
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode('/', trim($uri, '/')); // ['api', 'notes', '4']

$resource = $uri[1] ?? null; // 'notes'
$id = $uri[2] ?? null;

// 🔁 RESTful routing
switch ($resource) {
    case 'notes':
        switch ($_SERVER['REQUEST_METHOD']) {
            case 'GET':
                if ($id) {
                    $_GET['id'] = $id;
                    require 'notes/show.php';
                } else {
                    require 'notes/get.php';
                }
                break;
            case 'POST':
                require 'notes/post.php';
                break;
            case 'PUT':
                $_GET['id'] = $id;
                require 'notes/put.php';
                break;
            case 'DELETE':
                $_GET['id'] = $id;
                require 'notes/delete.php';
                break;
        }
        break;

    case 'users':
        switch ($_SERVER['REQUEST_METHOD']) {
            case 'GET':
                require 'users/get.php';
                break;
            case 'POST':
                require 'users/register.php';
                break;
        }
        break;

    default:
        http_response_code(404);
        echo json_encode(['error' => 'Route not found']);
}
