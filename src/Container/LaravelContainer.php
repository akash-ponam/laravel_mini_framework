<?php 


namespace App\Container;


class LaravelContainer{

    protected $enteries = [];
    
    public function set($key,callable $callable){

        $this->enteries[$key] = $callable;
    

    }

    public function get($key){

            
        if(!$this->enteries[$key]){
        
            throw new Exception(" no entry found for  {$key} ");

        }

        return $this->enteries[$key]($this);





    }

    public function has($key){

        return isset($this->enteries[$key]);

    }



    
}

