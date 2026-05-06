<?php 

namespace App\Http;

use App\Container\LaravelContainer;
use App\Middleware\Middleware;

class Router {

    protected $routes = [] ;

    protected LaravelContainer $container;



    public function __construct(LaravelContainer $container)
    {
        $this->container=$container;
    }




    public function get($uri,$callback){

    
    $pattern = preg_replace('/\{[a-zA-Z0-9_]+\}','([a-zA-Z0-9_-]+)',$uri);

    
    $full_url = "#^" . $pattern . "$#" ; 



    /* $this->routes['GET'][$uri] = ['callback'=>$callback,'middlewares' => [] ]; */


    $this->routes['GET'][$full_url] = ['callback'=>$callback,'middlewares'=> [] ] ;


        
    return $this;

}


    public function post($uri,$callback){

    $this->routes['POST'][$uri] = ['callback'=>$callback,'middlewares' => [] ];

        
    return $this;

}





    public function middleware($middleware_class){

        
        $last_method = array_key_last($this->routes);  // GET POST 

        $last_url = array_key_last($this->routes[$last_method]);


        $this->routes[$last_method][$last_url]['middlewares'][] = $middleware_class;




        return $this;



        

    }




    public function resolve($uri,$method) {


        $route_info  = $this->routes[$method][$uri]?? null;

        if(!$route_info){
            
                http_response_code(404);
                return " 404  not found";

        }


        $callback = $route_info['callback'];

        $middlewares =  $route_info['middlewares']??[];


        $core_action = function()  use ($callback) {

        if(is_callable($callback)){
            

            return call_user_func($callback);


        }


        if(is_array($callback)){

        [$class,$method_name]  =  $callback;

        $controller = $this->container->get($class);

        $reflection_method = new \ReflectionMethod($controller,$method_name);

        $params = $reflection_method->getParameters();

        $dependencies = [];

        
        foreach ($params as $param) {

        
            $type= $param->getType();

            if($type && !$type->isBuiltin()){

                $type_name = $type->getName();
                
                $dependencies[] = $this->container->get($type_name);
                
            }



        }

        return $reflection_method->invokeArgs($controller,$dependencies);
        }


            return "Invalid callback";

    };
        

    $pipeline = array_reduce(
    
    array_reverse($middlewares), // array passed 
    function($next_layer,$middlware_class){ // reducer 
    
        return function() use ($next_layer,$middlware_class){

        $middle_ware_instance = $this->container->get($middlware_class);

        return $middle_ware_instance->handle($this->container->get(Request::class),$next_layer


        ); 

    


    };
            
},

                    
                




    $core_action  // initial layer 





    );



    $response = $pipeline();

    if(is_array($response) || is_object($response) ) {
    
    header('Content-Type: application/json');

    echo json_encode($response);

    return;


    }
        
    return $response;




}





}


