
<?php 




ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require __DIR__.'/../src/autoload.php';

if (php_sapi_name() === 'cli-server') {
    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    if (is_file(__DIR__ . $path)) {
        return false;
    }
}



use App\Http\Request;
use App\Http\Router;

use App\Container\LaravelContainer;


use App\Controllers\ProductController;
use App\Controllers\UserController;
use App\Data\Database;
use App\Middleware\AuthMiddleware;

$container  = new LaravelContainer();


$container->bind(Request::class,function(){

    
    return new Request();


});

$container->bind(Router::class,function($c){

return new Router($c);

});


$container->singleton(Database::class ,function() {

    $settings = require __DIR__ .'/../src/Data/db_config.php';

    return new Database($settings);




});


$router = $container->get(Router::class);

$request = $container->get(Request::class);


$router ->get ('/',function(){

    
    require __DIR__ .'/index.html';

});


$router->get('/add_product',function(){



    require __DIR__.'/add_product.html';

});






$router->get('/api/products',[ProductController::class,'index'])->middleware(AuthMiddleware::class);

$router->get('/api/logout',[UserController::class,'logout']);


$router->post('/api/add_product',[ProductController::class,'addProduct']);

$router->get('/api/session',[AuthMiddleware::class,'check_session']);


$router->post('/api/register',[UserController::class,'register']);


$router->post('/api/login',[UserController::class,'login']);


$router->get('/api/user',[UserController::class,'getUser']);


echo $router->resolve($request->url(),$request->method());

?>
