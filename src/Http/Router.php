<?php 

namespace App\Http;

use App\Container\LaravelContainer;

class Router {

    protected $routes = [] ;

    protected LaravelContainer $container;



    public function __construct(LaravelContainer $container)
    {
        $this->container=$container;
    }




    public function get($uri,$callback){

    $this->routes['GET'][$uri] = $callback;    // $routes['GET']['/products'] = fetch_products // 


}

    public function resolve($uri,$method) {

        $callback = $this->routes[$method][$uri]?? null;  // $routes['GET']['/products']

        if(!$callback){
        
        /* header('HTTP/1.0 404 NOT FOUND'); */
        http_response_code(404);
        return "404 - Route not found ";
    

    }

    if(is_callable($callback)){

        return call_user_func($callback);

    }

    if(is_array($callback)) {
    
    [$class,$method] = $callback;

    $controller = $this->container->has($class)?$this->container->get($class):new $class();

    $reflection_method = new \ReflectionMethod($controller,$method);

    $params = $reflection_method->getParameters();

    $dependencies = [];

    foreach ($params as $param) {

        $type= $param->getType();
        
        if($type && !$type->isBuiltin()){

            $type_name  = $type->getName();

            $dependencies [] = $this->container->get($type_name);

            


        }



    }

        
    return $reflection_method->invokeArgs($controller,$dependencies); 


}



    return " Invalid callback";



}





}


