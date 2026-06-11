
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


$router->get('/products/{id}', function(){


    
    require __DIR__ .'/product_detail.html';



});

$router->get('/products',function(){

    
    require __DIR__ .'/products.html';



});


$router->get('/carts/{id}',function(){


    require __DIR__ .'/cart.html';


});



$router->get('/api/query_product',[ProductController::class,'search']);

$router->get('/api/products/{id}',[ProductController::class,'show']);

$router->get('/api/products',[ProductController::class,'index'])->middleware(AuthMiddleware::class);

$router->get('/api/logout',[UserController::class,'logout']);

$router->post('/api/add_product',[ProductController::class,'addProduct']);



$router->post('/api/add_to_cart',[ProductController::class,'add_to_cart']);

$router->get('/api/session',[AuthMiddleware::class,'check_session']);


$router->post('/api/register',[UserController::class,'register']);


$router->post('/api/login',[UserController::class,'login']);


$router->get('/api/user',[UserController::class,'getUser']);


$router->get('/api/cart_info',[ProductController::class,'cart_info']);


$router->delete('/api/carts/delete/{cart_id}/{product_id}',[ProductController::class,'remove_from_cart']);


$router->get('/api/carts/{id}',[ProductController::class,'cart']);

$router->post('/api/place_order',[ProductController::class,'place_order']);

$router->get('/api/get_orders',[ProductController::class,'get_orders']);

$router->get('/api/orders/{order_id}',[ProductController::class,'order_details']);


echo $router->resolve($request->url(),$request->method());

?>
