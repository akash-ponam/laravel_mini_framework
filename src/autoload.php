<?php 

spl_autoload_register(function ($class){

$prefix = 'App\\';                // namespace App\Controllers  class App\Controllers\UserController

$base_dir = __DIR__ .'/';        //  base _dir = src 

if(strncmp($prefix,$class,strlen($prefix))!==0){ 
                
                return;    // if  prefix does not match  then return 

}

$relative_class  = substr($class,strlen($prefix)); //  extract  Controllers\UserController 

$file = $base_dir . str_replace('\\','/',$relative_class) .'.php'  ; // final file = src/Controllers/UserController


if(file_exists($file)){ 

    // if file exists then import it 
    require $file;

}


} );
