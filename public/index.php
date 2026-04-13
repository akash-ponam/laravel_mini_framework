
<?php 

/* echo "current directory is " . __DIR__; */

require __DIR__.'/../src/autoload.php';


use App\Http\Request;
use App\Http\Router;

$request = new Request();
$router = new Router();


echo  " REQUESTED PATH IS ".$request->url().PHP_EOL;

$router->get('/',fn()=>'Welcome to home page');

$router->get('/products',fn()=>'Welcome  to products page');


 echo $router->resolve($request->url(),$request->method());











?>
