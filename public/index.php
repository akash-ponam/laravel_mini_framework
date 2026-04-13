
<?php 

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/* echo "current directory is " . __DIR__; */

require __DIR__.'/../src/autoload.php';


use App\Http\Request;
use App\Http\Router;

use App\Controllers\ProductController;


$request = new Request();
$router = new Router();

$router->get('/products',[ProductController::class,'index']);

echo $router->resolve($request->url(),$request->method());















?>
