
<?php 

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/* echo "current directory is " . __DIR__; */

require __DIR__.'/../src/autoload.php';


use App\Http\Request;
use App\Http\Router;

use App\Container\LaravelContainer;


use App\Controllers\ProductController;

$container  = new LaravelContainer();


$container->set(Request::class,function(){

    
    return new Request();


});

$container->set(Router::class,function($c){

return new Router($c);

});

/* $request = new Request(); */
/* $router = new Router(); */

$router = $container->get(Router::class);

$request = $container->get(Request::class);




$router->get('/products',[ProductController::class,'index']);

echo $router->resolve($request->url(),$request->method());

?>
