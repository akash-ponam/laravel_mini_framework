<?php 

namespace App\Http;

class Router {

    protected $routes = [] ;

    public function get($uri,$callback){

    $this->routes['GET'][$uri] = $callback;    // $routes['GET']['/products'] = fetch_products // 


}

    public function resolve($uri,$method) {

        $callback = $this->routes[$method][$uri]?? null;  // $routes['GET']['/products']

        if(!$callback){
        
        header('HTTP/1.0 404 NOT FOUND');

        return "404 - Route not found ";
    

    }

    if(is_callable($callback)){

        return call_user_func($callback);

    }



    return " Invalid callback";



}





}


