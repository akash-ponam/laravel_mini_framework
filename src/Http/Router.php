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

    
    $pattern = preg_replace('/\{[a-zA-Z0-9_]+\}/','([a-zA-Z0-9_-]+)',$uri);

    
    $full_url = "#^" . $pattern . "$#" ; 



    /* $this->routes['GET'][$uri] = ['callback'=>$callback,'middlewares' => [] ]; */


    $this->routes['GET'][$full_url] = ['callback'=>$callback,'middlewares'=> [] ] ;


        
    return $this;

}


    public function post($uri,$callback){

    /* $this->routes['POST'][$uri] = ['callback'=>$callback,'middlewares' => [] ]; */

    
    
    $pattern = preg_replace('/\{[a-zA-Z0-9_]+\}/','([a-zA-Z0-9_-]+)',$uri);

    
    $full_url = "#^" . $pattern . "$#" ; 



    /* $this->routes['GET'][$uri] = ['callback'=>$callback,'middlewares' => [] ]; */


    $this->routes['POST'][$full_url] = ['callback'=>$callback,'middlewares'=> [] ] ;


        
    return $this;

}

    public function delete($uri,$callback){

    /* $this->routes['DELETE'][$uri] = ['callback'=>$callback,'middlewares' => [] ]; */

    
    
    $pattern = preg_replace('/\{[a-zA-Z0-9_]+\}/','([a-zA-Z0-9_-]+)',$uri);

    
    $full_url = "#^" . $pattern . "$#" ; 



    /* $this->routes['GET'][$uri] = ['callback'=>$callback,'middlewares' => [] ]; */


    $this->routes['DELETE'][$full_url] = ['callback'=>$callback,'middlewares'=> [] ] ;


        
    return $this;

}










    public function middleware($middleware_class){

        
        $last_method = array_key_last($this->routes);  // GET POST 

        $last_url = array_key_last($this->routes[$last_method]);


        $this->routes[$last_method][$last_url]['middlewares'][] = $middleware_class;




        return $this;



        

    }




    public function resolve($uri,$method) {


        $route_info = null;

        $captured_params = [];

        foreach($this->routes[$method] as $pattern => $info) {

        if(preg_match($pattern,$uri,$matches)){

        $route_info = $info;

        array_shift($matches);

        $captured_params = $matches;

        break;

        }



    }

    if(!$route_info){

        http_response_code(400);
        
        return "404 not found";
    
    
    }


    $callback = $route_info['callback'];
    
    $middlewares = $route_info['middlewares']??[];

    $core_action  = function() use ($callback,$captured_params){

        if(is_callable($callback)){

            return call_user_func_array($callback,$captured_params);


        }

        if(is_array($callback)){
    
        [$class,$method_name] = $callback;

        $controller = $this->container->get($class);

        $reflection_method = new \ReflectionMethod($controller,$method_name);

        $parameters = $reflection_method->getParameters();

        $dependencies = [];

        foreach ($parameters as $param) {

        $type =  $param->getType();

        if($type  && !$type->isBuiltin()){

            
            $dependencies[] = $this->container->get($type->getName());

        }else{

                // normal parmeter type 

                $dependencies[] = array_shift($captured_params);


        }
    



        }


        return $reflection_method->invokeArgs($controller,$dependencies);

        

        
    



    }


    return " invalid callback logic";


    

    }; //end of core action logic 


    $pipeline = array_reduce(array_reverse($middlewares), 

    function ($next_layer, $middleware_class) {

    return function()   use ($next_layer,$middleware_class) {


        $instance = $this->container->get($middleware_class);

        return $instance->handle($this->container->get(Request::class),$next_layer);


};



}


   ,$core_action);

$response = $pipeline();


if(is_array($response) || is_object($response)) {


    
 header('Content-Type: application/json');

echo json_encode($response);

return;


}


    return $response; 
        

} 





}


